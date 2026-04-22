<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            ->orderBy('sort_order')
            ->orderBy('title')
            ->orderBy('id')
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
            'category_id' => ['required', 'integer', 'exists:video_categories,id'],
        ]);

        $max_order = (int) Video::query()->where('category_id', $validated['category_id'])->max('sort_order');

        Video::create([
            'youtube_id' => $validated['youtube_id'],
            'title' => $validated['title'],
            'media_id' => $validated['media_id'] ?? null,
            'pdf' => null,
            'category_id' => $validated['category_id'],
            'sort_order' => $max_order + 1,
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

    public function reorderVideos(Request $request): JsonResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:video_categories,id'],
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer', 'exists:videos,id'],
        ]);

        $category_id = (int) $validated['category_id'];
        $order = array_map(static fn ($id): int => (int) $id, $validated['order']);
        $expected = Video::query()->where('category_id', $category_id)->pluck('id')->sort()->values()->all();
        $sorted_order = collect($order)->sort()->values()->all();

        if ($expected !== $sorted_order || count($order) !== count($expected)) {
            return response()->json(['message' => 'Invalid order for this category.'], 422);
        }

        foreach ($order as $position => $video_id) {
            Video::query()
                ->where('id', $video_id)
                ->where('category_id', $category_id)
                ->update(['sort_order' => $position + 1]);
        }

        return response()->json(['ok' => true, 'message' => 'Order saved.']);
    }
}
