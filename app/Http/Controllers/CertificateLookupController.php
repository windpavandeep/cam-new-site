<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\StudentCertificate;
use App\Support\PublicAsset;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Public certificate lookup by reference number for past students.
 */
class CertificateLookupController extends Controller
{
    public function index(Request $request): View
    {
        $ref_input = trim((string) $request->query('ref', ''));
        $certificate = null;
        $not_found = false;

        if ($ref_input !== '') {
            $ref_no = StudentCertificate::normalizeRefNo($ref_input);
            $certificate = StudentCertificate::query()
                ->where('ref_no', $ref_no)
                ->first();
            $not_found = $certificate === null;
        }

        return view('certificates.lookup', [
            'ref_input' => $ref_input,
            'certificate' => $certificate,
            'not_found' => $not_found,
        ]);
    }

    public function viewFile(Request $request, StudentCertificate $certificate): BinaryFileResponse
    {
        $ref_no = StudentCertificate::normalizeRefNo((string) $request->query('ref', ''));
        if ($ref_no === '' || $ref_no !== $certificate->ref_no) {
            abort(403, 'Invalid reference for this certificate.');
        }

        $disk = PublicAsset::diskPath($certificate->path);
        if (! is_file($disk)) {
            abort(404, 'Certificate file not found.');
        }

        return response()->file($disk, [
            'Content-Type' => $certificate->mime_type ?? 'application/octet-stream',
        ]);
    }

    public function download(Request $request, StudentCertificate $certificate): BinaryFileResponse
    {
        $ref_no = StudentCertificate::normalizeRefNo((string) $request->query('ref', ''));
        if ($ref_no === '' || $ref_no !== $certificate->ref_no) {
            abort(403, 'Invalid reference for this certificate.');
        }

        $disk = PublicAsset::diskPath($certificate->path);
        if (! is_file($disk)) {
            abort(404, 'Certificate file not found.');
        }

        return response()->download($disk, $certificate->original_name, [
            'Content-Type' => $certificate->mime_type ?? 'application/octet-stream',
        ]);
    }
}
