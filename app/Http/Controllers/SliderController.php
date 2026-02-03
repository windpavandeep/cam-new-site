<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SliderSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SliderController extends Controller
{
    /** Recommended dimensions for slider images (displayed in upload form). */
    public const RECOMMENDED_WIDTH = 1920;

    public const RECOMMENDED_HEIGHT = 600;

    public function index(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $slides = SliderSlide::query()->orderBy('sort_order')->orderBy('id')->get();

        return view('dashboard.slider.index', [
            'slides' => $slides,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // 5MB max
        ], [
            'image.mimes' => 'Only JPG and PNG images are allowed.',
        ]);

        $file = $request->file('image');
        $dir = public_path('slider');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            $ext = 'jpg';
        }
        $filename = Str::random(40) . '.' . $ext;
        $file->move($dir, $filename);
        $path = 'slider/' . $filename;

        $maxOrder = (int) SliderSlide::query()->max('sort_order');
        SliderSlide::create([
            'image' => $path,
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()->route('dashboard.slider.index')->with('success', 'Slide added.');
    }

    public function destroy(SliderSlide $slide): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $fullPath = public_path($slide->image);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
        $slide->delete();

        return redirect()->route('dashboard.slider.index')->with('success', 'Slide removed.');
    }
}
