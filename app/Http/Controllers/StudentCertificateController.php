<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\StudentCertificate;
use App\Support\PublicAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Admin: upload and manage student certificates keyed by reference number.
 */
class StudentCertificateController extends Controller
{
    private const int MAX_FILE_KB = 20480;

    private const int MAX_PHOTO_KB = 5120;

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

        if (PublicAsset::likelyPhpUploadLimitRejected($request, 'certificate_file')
            || PublicAsset::likelyPhpUploadLimitRejected($request, 'student_photo')) {
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
            'father_name' => ['nullable', 'string', 'max:150'],
            'course_name' => ['nullable', 'string', 'max:200'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'issued_at' => ['nullable', 'date'],
            'certificate_file' => ['required', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:'.self::MAX_FILE_KB],
            'student_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:'.self::MAX_PHOTO_KB],
        ], [
            'ref_no.regex' => 'Reference number may only contain letters, numbers, hyphens, and underscores.',
            'ref_no.unique' => 'This reference number is already in use.',
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
            'certificate_file.required' => 'Please select a Certificate file.',
        ]);

        $ref_no = $validated['ref_no'];
        $file = $request->file('certificate_file');
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
            return redirect()->route('dashboard.certificates.index')->with('error', 'Could not save Certificate file: '.$e->getMessage());
        }

        $photo_path = null;
        $photo_original = null;
        $photo_mime = null;
        if ($request->hasFile('student_photo')) {
            $photo = $this->storeStudentPhoto($request->file('student_photo'));
            if (is_string($photo)) {
                @unlink(PublicAsset::diskPath($storage_dir.'/'.$filename));

                return redirect()->route('dashboard.certificates.index')->with('error', $photo);
            }
            $photo_path = $photo['path'];
            $photo_original = $photo['original_name'];
            $photo_mime = $photo['mime_type'];
        }

        StudentCertificate::create([
            'ref_no' => $ref_no,
            'student_name' => $validated['student_name'] ?? null,
            'father_name' => $validated['father_name'] ?? null,
            'course_name' => $validated['course_name'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'path' => $storage_dir.'/'.$filename,
            'student_photo_path' => $photo_path,
            'student_photo_original_name' => $photo_original,
            'student_photo_mime_type' => $photo_mime,
            'original_name' => $original_name,
            'mime_type' => $mime_type,
            'size' => $size,
            'issued_at' => $validated['issued_at'] ?? null,
        ]);

        return redirect()->route('dashboard.certificates.index')->with('success', 'Certificate added for ref '.$ref_no.'.');
    }

    public function update(Request $request, StudentCertificate $certificate): RedirectResponse
    {
        $this->ensureAdmin();

        $request->merge([
            'ref_no' => StudentCertificate::normalizeRefNo((string) $request->input('ref_no', '')),
        ]);

        $validated = $request->validate([
            'ref_no' => ['required', 'string', 'max:64', 'regex:/^[A-Z0-9\-_]+$/', 'unique:student_certificates,ref_no,'.$certificate->id],
            'student_name' => ['nullable', 'string', 'max:150'],
            'father_name' => ['nullable', 'string', 'max:150'],
            'course_name' => ['nullable', 'string', 'max:200'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'issued_at' => ['nullable', 'date'],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:'.self::MAX_FILE_KB],
            'student_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:'.self::MAX_PHOTO_KB],
            'remove_student_photo' => ['nullable', 'boolean'],
        ], [
            'ref_no.regex' => 'Reference number may only contain letters, numbers, hyphens, and underscores.',
            'ref_no.unique' => 'This reference number is already in use.',
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
        ]);

        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
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
                return redirect()->route('dashboard.certificates.index')->with('error', 'Could not save Certificate file: '.$e->getMessage());
            }

            $old = PublicAsset::diskPath($certificate->path);
            if (is_file($old)) {
                @unlink($old);
            }

            $certificate->path = $storage_dir.'/'.$filename;
            $certificate->original_name = $file->getClientOriginalName();
            $certificate->mime_type = $file->getMimeType();
            $certificate->size = $file->getSize();
        }

        if ($request->boolean('remove_student_photo') && ! $request->hasFile('student_photo')) {
            $this->deleteStudentPhoto($certificate);
            $certificate->student_photo_path = null;
            $certificate->student_photo_original_name = null;
            $certificate->student_photo_mime_type = null;
        }

        if ($request->hasFile('student_photo')) {
            $photo = $this->storeStudentPhoto($request->file('student_photo'));
            if (is_string($photo)) {
                return redirect()->route('dashboard.certificates.index')->with('error', $photo);
            }
            $this->deleteStudentPhoto($certificate);
            $certificate->student_photo_path = $photo['path'];
            $certificate->student_photo_original_name = $photo['original_name'];
            $certificate->student_photo_mime_type = $photo['mime_type'];
        }

        $certificate->fill([
            'ref_no' => $validated['ref_no'],
            'student_name' => $validated['student_name'] ?? null,
            'father_name' => $validated['father_name'] ?? null,
            'course_name' => $validated['course_name'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'issued_at' => $validated['issued_at'] ?? null,
        ]);
        $certificate->save();

        return redirect()->route('dashboard.certificates.index')->with('success', 'Certificate updated for ref '.$validated['ref_no'].'.');
    }

    public function destroy(StudentCertificate $certificate): RedirectResponse
    {
        $this->ensureAdmin();

        $disk = PublicAsset::diskPath($certificate->path);
        if (is_file($disk)) {
            @unlink($disk);
        }
        $this->deleteStudentPhoto($certificate);
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

    /**
     * @return array{path: string, original_name: string, mime_type: string|null}|string
     */
    private function storeStudentPhoto(UploadedFile $photo): array|string
    {
        $photo_dir = StudentCertificate::PHOTO_DIR;
        $dir_error = PublicAsset::ensureWritablePublicDirectory($photo_dir);
        if ($dir_error !== null) {
            return $dir_error;
        }

        $ext = $photo->getClientOriginalExtension() ?: 'jpg';
        $original_name = $photo->getClientOriginalName();
        $mime_type = $photo->getMimeType();
        $filename = Str::ulid().'.'.strtolower($ext);
        try {
            $photo->move(PublicAsset::diskPath($photo_dir), $filename);
        } catch (\Throwable $e) {
            return 'Could not save student photo: '.$e->getMessage();
        }

        return [
            'path' => $photo_dir.'/'.$filename,
            'original_name' => $original_name,
            'mime_type' => $mime_type,
        ];
    }

    private function deleteStudentPhoto(StudentCertificate $certificate): void
    {
        if (! $certificate->student_photo_path) {
            return;
        }

        $disk = PublicAsset::diskPath($certificate->student_photo_path);
        if (is_file($disk)) {
            @unlink($disk);
        }
    }

    private function ensureAdmin(): void
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }
    }
}
