<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public job listings and applications (no login required).
 */
class JobOpeningController extends Controller
{
    private const int MAX_CV_KB = 10240;

    public function index(): View
    {
        $jobs = Job::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    public function show(Job $job): View
    {
        if (! $job->is_published) {
            abort(404);
        }

        return view('jobs.show', [
            'job' => $job,
        ]);
    }

    public function apply(Request $request, Job $job): RedirectResponse
    {
        if (! $job->is_published) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'cv' => ['required', 'file', 'max:' . self::MAX_CV_KB, 'mimes:pdf,doc,docx'],
        ], [
            'cv.required' => 'Please upload your CV (PDF or Word).',
            'cv.mimes' => 'CV must be a PDF or Word document (.pdf, .doc, .docx).',
        ]);

        $file = $request->file('cv');
        $cv_path = $file->store('job-cvs', 'local');
        $cv_original_name = $file->getClientOriginalName();

        JobApplication::query()->create([
            'job_id' => $job->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'cv_path' => $cv_path,
            'cv_original_name' => $cv_original_name,
        ]);

        return redirect()
            ->route('jobs.show', $job)
            ->with('success', 'Thank you for applying. We have received your application and CV.');
    }
}
