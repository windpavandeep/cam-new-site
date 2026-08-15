<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\StudentCertificate;
use App\Support\PublicAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Admin: upload and manage student certificates keyed by reference number.
 */
class StudentCertificateController extends Controller
{
    private const int MAX_FILE_KB = 20480;

    public function index(): View
    {
        $this->ensureAdmin();

        $certificates = StudentCertificate::query()
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.certificates.index', [
            'certificates' => $certificates,
            'suggested_ref_no' => StudentCertificate::generateRefNo(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        if (PublicAsset::likelyPhpUploadLimitRejected($request, 'file')) {
            return redirect()->route('dashboard.certificates.index')->with(
                'error',
                'The file never reached the application. '.PublicAsset::phpUploadLimitsHint()
            );
        }

        $request->merge([
            'ref_no' => StudentCertificate::normalizeRefNo((string) $request->input('ref_no', '')),
        ]);

        $validated = $request->validate([
            'ref_no' => ['required', 'string', 'max:64', 'regex:/^[A-Z0-9\-_]+$/', 'unique:student_certificates,ref_no'],
            'student_name' => ['nullable', 'string', 'max:150'],
            'course_name' => ['nullable', 'string', 'max:200'],
            'issued_at' => ['nullable', 'date'],
            'file' => ['required', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:'.self::MAX_FILE_KB],
        ], [
            'ref_no.regex' => 'Reference number may only contain letters, numbers, hyphens, and underscores.',
            'ref_no.unique' => 'This reference number is already in use.',
        ]);

        $ref_no = $validated['ref_no'];
        $file = $request->file('file');
        $original_name = $file->getClientOriginalName();
        $mime_type = $file->getMimeType();
        $size = $file->getSize();

        $storage_dir = StudentCertificate::STORAGE_DIR;
        $dir_error = PublicAsset::ensureWritablePublicDirectory($storage_dir);
        if ($dir_error !== null) {
            return redirect()->route('dashboard.certificates.index')->with('error', $dir_error);
        }

        $ext = $file->getClientOriginalExtension() ?: 'pdf';
        $filename = Str::ulid().'.'.strtolower($ext);
        try {
            $file->move(PublicAsset::diskPath($storage_dir), $filename);
        } catch (\Throwable $e) {
            return redirect()->route('dashboard.certificates.index')->with('error', 'Could not save file: '.$e->getMessage());
        }

        StudentCertificate::create([
            'ref_no' => $ref_no,
            'student_name' => $validated['student_name'] ?? null,
            'course_name' => $validated['course_name'] ?? null,
            'path' => $storage_dir.'/'.$filename,
            'original_name' => $original_name,
            'mime_type' => $mime_type,
            'size' => $size,
            'issued_at' => $validated['issued_at'] ?? null,
        ]);

        return redirect()->route('dashboard.certificates.index')->with('success', 'Certificate added for ref '.$ref_no.'.');
    }

    public function destroy(StudentCertificate $certificate): RedirectResponse
    {
        $this->ensureAdmin();

        $disk = PublicAsset::diskPath($certificate->path);
        if (is_file($disk)) {
            @unlink($disk);
        }
        $certificate->delete();

        return redirect()->route('dashboard.certificates.index')->with('success', 'Certificate removed.');
    }

    public function download(StudentCertificate $certificate): BinaryFileResponse
    {
        $this->ensureAdmin();

        $disk = PublicAsset::diskPath($certificate->path);
        if (! is_file($disk)) {
            abort(404, 'Certificate file not found.');
        }

        return response()->download($disk, $certificate->original_name, [
            'Content-Type' => $certificate->mime_type ?? 'application/octet-stream',
        ]);
    }

    private function ensureAdmin(): void
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }
    }
}
