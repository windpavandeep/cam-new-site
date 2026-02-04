<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Dashboard media library: upload files, set thumbnail and tooltip.
 * Media IDs can be assigned to videos (YouTube) as the model (e.g. PDF).
 */
class MediaController extends Controller
{
    /** Allowed mime types for main file (e.g. PDF for model uploads). */
    private const array ALLOWED_MIMES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /** Max file size in KB (10 MB). */
    private const int MAX_FILE_KB = 10240;

    /** Max thumbnail size in KB (2 MB). */
    private const int MAX_THUMBNAIL_KB = 2048;

    public function index(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $media = Media::query()
            ->withCount('videos')
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.media.index', [
            'media' => $media,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:' . self::MAX_FILE_KB],
            'tooltip' => ['nullable', 'string', 'max:500'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:' . self::MAX_THUMBNAIL_KB],
        ], [
            'file.required' => 'Please select a file to upload.',
        ]);

        $file = $request->file('file');
        $mime = $file->getMimeType();
        if (! in_array($mime, self::ALLOWED_MIMES, true)) {
            return redirect()->route('dashboard.media.index')
                ->withInput()
                ->with('error', 'Allowed file types: PDF, JPG, PNG, GIF, WebP.');
        }

        $size = $file->getSize();
        $original_name = $file->getClientOriginalName();

        $dir = public_path('media');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $ext = $file->getClientOriginalExtension() ?: Str::after($mime, '/');
        $filename = Str::ulid() . '.' . strtolower($ext);
        $file->move($dir, $filename);
        $path = 'media/' . $filename;

        $thumbnail_path = null;
        if ($request->hasFile('thumbnail')) {
            $thumb_dir = public_path('media/thumbnails');
            if (! is_dir($thumb_dir)) {
                mkdir($thumb_dir, 0755, true);
            }
            $thumb_file = $request->file('thumbnail');
            $thumb_ext = $thumb_file->getClientOriginalExtension() ?: 'jpg';
            $thumb_filename = Str::ulid() . '.' . strtolower($thumb_ext);
            $thumb_file->move($thumb_dir, $thumb_filename);
            $thumbnail_path = 'media/thumbnails/' . $thumb_filename;
        }

        Media::create([
            'path' => $path,
            'original_name' => $original_name,
            'thumbnail_path' => $thumbnail_path,
            'tooltip' => $validated['tooltip'] ?? null,
            'mime_type' => $mime,
            'size' => $size,
        ]);

        return redirect()->route('dashboard.media.index')->with('success', 'Media added to library.');
    }

    public function update(Request $request, Media $media): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'tooltip' => ['nullable', 'string', 'max:500'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:' . self::MAX_THUMBNAIL_KB],
        ]);

        $media->tooltip = $validated['tooltip'] ?? $media->tooltip;

        if ($request->hasFile('thumbnail')) {
            $thumb_dir = public_path('media/thumbnails');
            if (! is_dir($thumb_dir)) {
                mkdir($thumb_dir, 0755, true);
            }
            if ($media->thumbnail_path && is_file(public_path($media->thumbnail_path))) {
                @unlink(public_path($media->thumbnail_path));
            }
            $thumb_file = $request->file('thumbnail');
            $thumb_ext = $thumb_file->getClientOriginalExtension() ?: 'jpg';
            $thumb_filename = Str::ulid() . '.' . strtolower($thumb_ext);
            $thumb_file->move($thumb_dir, $thumb_filename);
            $media->thumbnail_path = 'media/thumbnails/' . $thumb_filename;
        }

        $media->save();

        return redirect()->route('dashboard.media.index')->with('success', 'Media updated.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        if ($media->videos()->exists()) {
            return redirect()->route('dashboard.media.index')
                ->with('error', 'Cannot delete: this media is assigned to one or more videos. Unassign it first.');
        }

        if (is_file(public_path($media->path))) {
            @unlink(public_path($media->path));
        }
        if ($media->thumbnail_path && is_file(public_path($media->thumbnail_path))) {
            @unlink(public_path($media->thumbnail_path));
        }
        $media->delete();

        return redirect()->route('dashboard.media.index')->with('success', 'Media removed.');
    }
}
