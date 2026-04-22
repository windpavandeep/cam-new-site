<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin CRUD for job postings.
 */
class JobAdminController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $jobs = Job::query()
            ->withCount('applications')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    public function create(): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        return view('dashboard.jobs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:65000'],
            'location' => ['nullable', 'string', 'max:200'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
        ]);

        $job = Job::query()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'] ?? null,
            'employment_type' => $validated['employment_type'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('dashboard.jobs.edit', $job)
            ->with('success', 'Job created. Slug: ' . $job->slug);
    }

    public function edit(Job $job): View|RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        return view('dashboard.jobs.edit', [
            'job' => $job,
        ]);
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:65000'],
            'location' => ['nullable', 'string', 'max:200'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
        ]);

        $job->fill([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'] ?? null,
            'employment_type' => $validated['employment_type'] ?? null,
            'is_published' => $request->boolean('is_published', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);
        $job->save();

        return redirect()
            ->route('dashboard.jobs.edit', $job)
            ->with('success', 'Job updated.');
    }

    public function destroy(Job $job): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin only.');
        }

        $job->delete();

        return redirect()
            ->route('dashboard.jobs.index')
            ->with('success', 'Job deleted.');
    }
}
