<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Media;
use App\Support\PublicAsset;
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
    /** Max file size in KB (100 MB). */
    private const int MAX_FILE_KB = 102400;

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

        if (PublicAsset::likelyPhpUploadLimitRejected($request, 'file')) {
            return redirect()->route('dashboard.media.index')->with(
                'error',
                'The file never reached the application (often PHP limits on cPanel). '.PublicAsset::phpUploadLimitsHint()
            );
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
        $size = $file->getSize();
        $original_name = $file->getClientOriginalName();

        $dir_error = PublicAsset::ensureWritablePublicDirectory('media');
        if ($dir_error !== null) {
            return redirect()->route('dashboard.media.index')->with('error', $dir_error);
        }
        $dir = PublicAsset::diskPath('media');
        $ext = $file->getClientOriginalExtension() ?: Str::after($mime, '/');
        $filename = Str::ulid() . '.' . strtolower($ext);
        try {
            $file->move($dir, $filename);
        } catch (\Throwable $e) {
            return redirect()->route('dashboard.media.index')->with('error', 'Could not save the file: '.$e->getMessage());
        }
        $path = 'media/' . $filename;

        $thumbnail_path = null;
        if ($request->hasFile('thumbnail')) {
            $thumb_err = PublicAsset::ensureWritablePublicDirectory('media/thumbnails');
            if ($thumb_err !== null) {
                @unlink(PublicAsset::diskPath($path));

                return redirect()->route('dashboard.media.index')->with('error', $thumb_err);
            }
            $thumb_dir = PublicAsset::diskPath('media/thumbnails');
            $thumb_file = $request->file('thumbnail');
            $thumb_ext = $thumb_file->getClientOriginalExtension() ?: 'jpg';
            $thumb_filename = Str::ulid() . '.' . strtolower($thumb_ext);
            try {
                $thumb_file->move($thumb_dir, $thumb_filename);
            } catch (\Throwable $e) {
                @unlink(PublicAsset::diskPath($path));

                return redirect()->route('dashboard.media.index')->with('error', 'Could not save thumbnail: '.$e->getMessage());
            }
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
            $thumb_err = PublicAsset::ensureWritablePublicDirectory('media/thumbnails');
            if ($thumb_err !== null) {
                return redirect()->route('dashboard.media.index')->with('error', $thumb_err);
            }
            $thumb_dir = PublicAsset::diskPath('media/thumbnails');
            if ($media->thumbnail_path && is_file(PublicAsset::diskPath($media->thumbnail_path))) {
                @unlink(PublicAsset::diskPath($media->thumbnail_path));
            }
            $thumb_file = $request->file('thumbnail');
            $thumb_ext = $thumb_file->getClientOriginalExtension() ?: 'jpg';
            $thumb_filename = Str::ulid() . '.' . strtolower($thumb_ext);
            try {
                $thumb_file->move($thumb_dir, $thumb_filename);
            } catch (\Throwable $e) {
                return redirect()->route('dashboard.media.index')->with('error', 'Could not save thumbnail: '.$e->getMessage());
            }
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

        if (is_file(PublicAsset::diskPath($media->path))) {
            @unlink(PublicAsset::diskPath($media->path));
        }
        if ($media->thumbnail_path && is_file(PublicAsset::diskPath($media->thumbnail_path))) {
            @unlink(PublicAsset::diskPath($media->thumbnail_path));
        }
        $media->delete();

        return redirect()->route('dashboard.media.index')->with('success', 'Media removed.');
    }
}
