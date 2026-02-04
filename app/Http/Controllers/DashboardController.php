<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $categories = VideoCategory::query()->orderBy('sort_order')->orderBy('id')->get();
        $videos = Video::query()
            ->with(['media', 'category'])
            ->orderBy('title')
            ->get()
            ->groupBy('category_id');

        $media = Media::query()->orderBy('original_name')->get();

        return view('dashboard.index', [
            'categories' => $categories,
            'videos_by_category' => $videos,
            'media' => $media,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'youtube_id' => ['required', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:255'],
            'media_id' => ['nullable', 'integer', 'exists:media,id'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'category_id' => ['required', 'integer', 'exists:video_categories,id'],
        ]);

        $pdf_filename = null;
        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $pdf_filename = Str::ulid() . '.pdf';
            $file->move(public_path('models'), $pdf_filename);
        }

        Video::create([
            'youtube_id' => $validated['youtube_id'],
            'title' => $validated['title'],
            'media_id' => $validated['media_id'] ?? null,
            'pdf' => $pdf_filename,
            'category_id' => $validated['category_id'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Video added.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $video->delete();

        return redirect()->route('dashboard')->with('success', 'Video removed.');
    }
}
