<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\VideoCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoCategoryController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $categories = VideoCategory::query()
            ->withCount('videos')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('dashboard.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $max_order = (int) VideoCategory::query()->max('sort_order');
        VideoCategory::create([
            'name' => $validated['name'],
            'sort_order' => $max_order + 1,
        ]);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category added.');
    }

    public function update(Request $request, VideoCategory $category): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $category->update(['name' => $validated['name']]);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category updated.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:video_categories,id'],
        ]);

        foreach ($validated['order'] as $position => $id) {
            VideoCategory::query()->where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return redirect()->route('dashboard.categories.index')->with('success', 'Order saved.');
    }

    public function destroy(VideoCategory $category): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        if ($category->videos()->exists()) {
            return redirect()->route('dashboard.categories.index')
                ->with('error', 'Cannot delete: category has videos. Move or remove videos first.');
        }

        $category->delete();
        // Re-normalize sort_order for remaining categories
        VideoCategory::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(function (VideoCategory $cat, int $index): void {
                $cat->update(['sort_order' => $index + 1]);
            });

        return redirect()->route('dashboard.categories.index')->with('success', 'Category removed.');
    }
}
