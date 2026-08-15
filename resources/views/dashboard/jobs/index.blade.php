@extends('layouts.dashboard')

@section('title', 'Jobs')
@section('page-heading', 'Job postings')

@section('content')
    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-600">Published jobs appear on <strong>Find jobs</strong> on the public site.</p>
        <a href="{{ route('dashboard.jobs.create') }}" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            Add job
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        @if ($jobs->isEmpty())
        <div class="px-6 py-16 text-center text-sm text-slate-500">
            No jobs yet. Click <strong>Add job</strong> to create a listing.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Applications</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($jobs as $row)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $row->title }}</td>
                        <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $row->slug }}</td>
                        <td class="px-4 py-3">
                            @if ($row->is_published)
                            <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">Published</span>
                            @else
                            <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('dashboard.job-applications.index') }}" class="text-amber-700 hover:underline">{{ $row->applications_count }}</a>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-3">
                            @if ($row->is_published)
                            <a href="{{ route('jobs.show', $row) }}" target="_blank" rel="noopener" class="text-slate-600 hover:text-amber-700">View</a>
                            @endif
                            <a href="{{ route('dashboard.jobs.edit', $row) }}" class="font-medium text-amber-700 hover:text-amber-800">Edit</a>
                            <form method="POST" action="{{ route('dashboard.jobs.destroy', $row) }}" class="inline" onsubmit="return confirm('Delete this job and all applications?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection
