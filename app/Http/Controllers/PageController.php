<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\SliderSlide;
use App\Models\Video;
use App\Models\VideoCategory;

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
            ->orderBy('title')
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

        return $result;
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
        return view('contact');
    }

    public function models(): \Illuminate\Contracts\View\View
    {
        $categories = VideoCategory::query()->orderBy('sort_order')->orderBy('id')->get()->keyBy('id');
        $models = Video::query()
            ->with(['media', 'category'])
            ->where(fn ($q) => $q->whereNotNull('pdf')->orWhereNotNull('media_id'))
            ->orderBy('title')
            ->get()
            ->map(static function (Video $video) use ($categories): array {
                $media = $video->media;
                $category = $video->category;
                return [
                    'title' => $video->title,
                    'filename' => $media ? $media->original_name : $video->pdf,
                    'category_id' => $video->category_id,
                    'category_name' => $category?->name,
                    'download_path' => $video->download_path,
                    'tooltip' => $media?->tooltip,
                    'thumbnail_url' => $media?->thumbnail_url,
                ];
            })
            ->values()
            ->all();

        if (empty($models)) {
            $first_category = $categories->first();
            $models = array_map(
                static function (array $item) use ($first_category): array {
                    return [
                        'title' => $item['title'],
                        'filename' => $item['pdf'],
                        'category_id' => $first_category?->id,
                        'category_name' => $first_category?->name ?? 'Milling',
                        'download_path' => 'file/' . $item['pdf'],
                        'tooltip' => null,
                        'thumbnail_url' => null,
                    ];
                },
                self::FALLBACK_VIDEOS
            );
        }

        $categories_map = $categories->pluck('name', 'id')->all();

        return view('models', [
            'models' => $models,
            'categories' => $categories_map,
        ]);
    }

    /**
     * Download model file. Path is "file/{filename}" (legacy, from public/models) or "media/{id}" (media library).
     */
    public function downloadModel(string $path): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (str_starts_with($path, 'media/')) {
            $id = substr($path, 6);
            if (! ctype_digit($id)) {
                abort(400, 'Invalid media path.');
            }
            $media = Media::find($id);
            if (! $media || ! is_file(public_path($media->path))) {
                abort(404, 'Model file not found.');
            }

            return response()->download(
                public_path($media->path),
                $media->original_name,
                ['Content-Type' => $media->mime_type ?? 'application/octet-stream']
            );
        }

        if (str_starts_with($path, 'file/')) {
            $filename = basename($path);
            if (! preg_match('/^[a-zA-Z0-9_.-]+\\.pdf$/', $filename)) {
                abort(400, 'Invalid filename.');
            }
            $full_path = public_path('models/' . $filename);
            if (! is_file($full_path)) {
                abort(404, 'Model file not found.');
            }

            return response()->download($full_path, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        abort(400, 'Invalid path.');
    }
}
