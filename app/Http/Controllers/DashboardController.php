<?php

namespace App\Http\Controllers;

use App\Models\Video;
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

        $videos = Video::query()
            ->orderBy('category')
            ->orderBy('title')
            ->get()
            ->groupBy('category');

        return view('dashboard.index', [
            'videos_by_category' => [
                Video::CATEGORY_MILLING => $videos->get(Video::CATEGORY_MILLING, collect()),
                Video::CATEGORY_MULTI_AXIS => $videos->get(Video::CATEGORY_MULTI_AXIS, collect()),
                Video::CATEGORY_TURNING => $videos->get(Video::CATEGORY_TURNING, collect()),
            ],
            'categories' => Video::categories(),
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
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'category' => ['required', 'string', 'in:milling,multi_axis,turning'],
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
            'pdf' => $pdf_filename,
            'category' => $validated['category'],
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
