<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\ContactSetting;
use App\Models\DownloadCounter;
use App\Models\Media;
use App\Models\SliderSlide;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Support\PublicAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Handles simple page views for the CNC learning website.
 */
class PageController extends Controller
{
    /**
     * Fallback videos when first category has none (thumbnail: https://img.youtube.com/vi/{id}/mqdefault.jpg).
     *
     * @var array<int, array{id: string, title: string, pdf: string}>
     */
    private const array FALLBACK_VIDEOS = [
        ['id' => 'jN7UH0_4dW4', 'title' => 'Mastercam 2D Milling Basics', 'pdf' => 'mill-basics-model.pdf'],
        ['id' => 'wixpacygYig', 'title' => 'CNC Milling Toolpaths Explained', 'pdf' => 'toolpaths-model.pdf'],
        ['id' => '2F6shnzR2h4', 'title' => 'Pocket Milling in Mastercam', 'pdf' => 'pocket-milling-model.pdf'],
        ['id' => 'RlXbRVOQqy8', 'title' => 'Contour and Drilling Operations', 'pdf' => 'contour-drill-model.pdf'],
        ['id' => '9RBz2xq2uR4', 'title' => 'Advanced 3-Axis Milling', 'pdf' => 'advanced-3axis-model.pdf'],
    ];

    /**
     * @return array<int, array{id: \App\Models\VideoCategory, name: string, slug: string, videos: array<int, array>}>
     */
    private function getCategoriesWithVideos(): array
    {
        $categories = VideoCategory::query()->orderBy('sort_order')->orderBy('id')->get();
        $videos = Video::query()
            ->with('media')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->orderBy('id')
            ->get()
            ->groupBy('category_id');

        $result = [];
        foreach ($categories as $index => $category) {
            $items = $videos->get($category->id, collect())
                ->map(fn (Video $v) => $v->toHomeArray())
                ->values()
                ->all();
            if ($index === 0 && empty($items)) {
                $items = array_map(
                    static fn (array $item): array => array_merge($item, [
                        'download_path' => 'file/' . $item['pdf'],
                        'tooltip' => null,
                        'thumbnail_url' => null,
                    ]),
                    self::FALLBACK_VIDEOS
                );
            }
            $result[] = [
                'id' => $category,
                'name' => $category->name,
                'slug' => $category->slug,
                'videos' => $items,
            ];
        }

        return $this->mergeDownloadCountsIntoCategories($result);
    }

    /**
     * @param  array<int, array{name: string, slug: string, videos: array<int, array<string, mixed>>}>  $categories
     * @return array<int, array{name: string, slug: string, videos: array<int, array<string, mixed>>}>
     */
    private function mergeDownloadCountsIntoCategories(array $categories): array
    {
        $path_keys = collect($categories)
            ->flatMap(static fn (array $cat): array => array_column($cat['videos'], 'download_path'))
            ->filter()
            ->unique()
            ->values()
            ->all();
        $counts = DownloadCounter::countsForPathKeys($path_keys);

        foreach ($categories as $i => $cat) {
            foreach ($cat['videos'] as $j => $video) {
                $p = $video['download_path'] ?? null;
                $categories[$i]['videos'][$j]['downloads_count'] = $p ? (int) ($counts[$p] ?? 0) : 0;
            }
        }

        return $categories;
    }

    public function home(): \Illuminate\Contracts\View\View
    {
        $categories_with_videos = $this->getCategoriesWithVideos();
        $slider_slides = SliderSlide::query()->orderBy('sort_order')->orderBy('id')->get();

        return view('home', [
            'categories_with_videos' => $categories_with_videos,
            'slider_slides' => $slider_slides,
        ]);
    }

    public function videos(): \Illuminate\Contracts\View\View
    {
        $categories_with_videos = $this->getCategoriesWithVideos();

        return view('videos', [
            'categories_with_videos' => $categories_with_videos,
        ]);
    }

    public function about(): \Illuminate\Contracts\View\View
    {
        return view('about');
    }

    public function contact(): \Illuminate\Contracts\View\View
    {
        return view('contact', [
            'contact' => ContactSetting::current()->toPublicArray(),
        ]);
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
        ]);

        $settings = ContactSetting::current();
        $to = trim((string) ($settings->email ?? ''));
        $site_name = (string) config('site.name', 'CAM Solutions');
        $subject_line = $validated['subject'] !== '' && $validated['subject'] !== null
            ? $validated['subject']
            : 'Message from ' . $site_name . ' contact form';

        $body = "Name: {$validated['name']}\nEmail: {$validated['email']}\n\n{$validated['message']}";

        if ($to !== '') {
            Mail::raw($body, function (\Illuminate\Mail\Message $message) use ($validated, $to, $subject_line): void {
                $message->to($to)
                    ->replyTo($validated['email'], $validated['name'])
                    ->subject($subject_line);
            });
        } else {
            Log::info('Contact form saved; add inbox email in Admin → Contact details to also receive mail.', [
                'from' => $validated['email'],
                'name' => $validated['name'],
            ]);
        }

        return redirect()->route('contact')->with('success', 'Thank you for your message. We will get back to you soon.');
    }

    public function models(): \Illuminate\Contracts\View\View
    {
        $search = request()->string('q')->trim()->value();
        $media_query = Media::query()
            ->with(['videos.category'])
            ->when($search !== '', function ($q) use ($search): void {
                $like = '%' . $search . '%';
                $q->where(function ($nested) use ($like): void {
                    $nested
                        ->where('original_name', 'like', $like)
                        ->orWhere('tooltip', 'like', $like)
                        ->orWhere('mime_type', 'like', $like)
                        ->orWhereHas('videos', function ($video_q) use ($like): void {
                            $video_q->where('title', 'like', $like);
                        });
                });
            })
            ->orderByDesc('id');

        $models = $media_query
            ->get()
            ->map(static function (Media $media): array {
                $first_video = $media->videos->first();
                $category = $first_video?->category;
                return [
                    'title' => $first_video?->title ?? pathinfo((string) $media->original_name, PATHINFO_FILENAME),
                    'filename' => $media->original_name,
                    'category_id' => $category?->id,
                    'category_name' => $category?->name,
                    'download_path' => 'media/' . $media->id,
                    'tooltip' => $media->tooltip,
                    'thumbnail_url' => $media->thumbnail_url,
                    'mime_type' => $media->mime_type,
                ];
            })
            ->values()
            ->all();

        $path_keys = collect($models)->pluck('download_path')->filter()->unique()->values()->all();
        $counts = DownloadCounter::countsForPathKeys($path_keys);
        foreach ($models as $k => $row) {
            $p = $row['download_path'] ?? null;
            $models[$k]['downloads_count'] = $p ? (int) ($counts[$p] ?? 0) : 0;
        }

        return view('models', [
            'models' => $models,
            'search' => $search,
        ]);
    }

    public function trackModelDownload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'path' => ['required', 'string', 'max:512'],
        ]);
        $path = $validated['path'];
        $this->resolveModelDownload($path);
        $count = DownloadCounter::incrementForPath($path);

        return response()->json(['count' => $count]);
    }

    /**
     * Download model file. Path is "file/{filename}" (legacy, from public/models) or "media/{id}" (media library).
     * Count is incremented via {@see trackModelDownload} before navigating here.
     */
    public function downloadModel(string $path): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $resolved = $this->resolveModelDownload($path);

        if ($resolved['kind'] === 'media') {
            $media = $resolved['media'];

            return response()->download(
                PublicAsset::diskPath($media->path),
                $media->original_name,
                ['Content-Type' => $media->mime_type ?? 'application/octet-stream']
            );
        }

        return response()->download($resolved['disk_path'], $resolved['filename'], [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * @return array{kind: 'media', media: Media}|array{kind: 'file', disk_path: string, filename: string}
     */
    private function resolveModelDownload(string $path): array
    {
        if (str_starts_with($path, 'media/')) {
            $id = substr($path, 6);
            if (! ctype_digit($id)) {
                abort(400, 'Invalid media path.');
            }
            $media = Media::find($id);
            if (! $media || ! is_file(PublicAsset::diskPath($media->path))) {
                abort(404, 'Model file not found.');
            }

            return ['kind' => 'media', 'media' => $media];
        }

        if (str_starts_with($path, 'file/')) {
            $filename = basename($path);
            if (! preg_match('/^[a-zA-Z0-9_.-]+\\.pdf$/', $filename)) {
                abort(400, 'Invalid filename.');
            }
            $full_path = PublicAsset::diskPath('models/' . $filename);
            if (! is_file($full_path)) {
                abort(404, 'Model file not found.');
            }

            return ['kind' => 'file', 'disk_path' => $full_path, 'filename' => $filename];
        }

        abort(400, 'Invalid path.');
    }
}
