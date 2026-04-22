<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Admin: list and review job applications / CV downloads.
 */
class JobApplicationAdminController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $applications = JobApplication::query()
            ->with('job')
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('dashboard.job-applications.index', [
            'applications' => $applications,
        ]);
    }

    public function show(JobApplication $job_application): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $job_application->load('job');
        $job_application->markRead();

        return view('dashboard.job-applications.show', [
            'application' => $job_application,
        ]);
    }

    public function downloadCv(JobApplication $job_application): StreamedResponse|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        if ($job_application->cv_path === '' || ! Storage::disk('local')->exists($job_application->cv_path)) {
            return redirect()
                ->route('dashboard.job-applications.show', $job_application)
                ->with('error', 'CV file is missing on the server.');
        }

        $download_name = $job_application->cv_original_name ?: basename($job_application->cv_path);

        return Storage::disk('local')->download($job_application->cv_path, $download_name);
    }

    public function destroy(JobApplication $job_application): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $job_application->delete();

        return redirect()
            ->route('dashboard.job-applications.index')
            ->with('success', 'Application removed.');
    }
}
