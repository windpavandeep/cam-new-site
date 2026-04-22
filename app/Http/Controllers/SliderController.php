<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SliderSlide;
use App\Support\PublicAsset;
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

        if (PublicAsset::likelyPhpUploadLimitRejected($request, 'image')) {
            return redirect()->route('dashboard.slider.index')->with(
                'error',
                'The image never reached the application (often PHP limits on cPanel). '.PublicAsset::phpUploadLimitsHint()
            );
        }

        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // 5MB max
        ], [
            'image.mimes' => 'Only JPG and PNG images are allowed.',
        ]);

        $file = $request->file('image');
        $dir_error = PublicAsset::ensureWritablePublicDirectory('slider');
        if ($dir_error !== null) {
            return redirect()->route('dashboard.slider.index')->with('error', $dir_error);
        }
        $dir = PublicAsset::diskPath('slider');
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            $ext = 'jpg';
        }
        $filename = Str::random(40) . '.' . $ext;
        try {
            $file->move($dir, $filename);
        } catch (\Throwable $e) {
            return redirect()->route('dashboard.slider.index')->with('error', 'Could not save the image: '.$e->getMessage());
        }
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

        $fullPath = PublicAsset::diskPath($slide->image);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
        $slide->delete();

        return redirect()->route('dashboard.slider.index')->with('success', 'Slide removed.');
    }
}
