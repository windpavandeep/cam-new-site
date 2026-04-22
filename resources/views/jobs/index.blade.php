@extends('layouts.app')

@section('title', 'Find jobs')

@section('content')
<div class="container mx-auto px-4 py-10 md:py-14 max-w-4xl">
    <header class="mb-10">
        <p class="text-sm font-semibold uppercase tracking-wider text-amber-700 mb-2">Careers</p>
        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-3">Find jobs</h1>
        <p class="text-slate-600 text-lg max-w-2xl">
            Open positions at CAM Solutions. Select a role to read more and apply with your CV.
        </p>
    </header>

    @if ($jobs->isEmpty())
    <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
        <p class="text-slate-600">There are no open listings right now. Please check again later.</p>
    </div>
    @else
    <ul class="space-y-4">
        @foreach ($jobs as $job)
        <li>
            <a href="{{ route('jobs.show', $job) }}" class="block rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-amber-200 hover:shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">{{ $job->title }}</h2>
                        <div class="mt-2 flex flex-wrap gap-3 text-sm text-slate-500">
                            @if ($job->location)
                            <span>{{ $job->location }}</span>
                            @endif
                            @if ($job->employment_type)
                            <span class="text-slate-400">·</span>
                            <span>{{ $job->employment_type }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="inline-flex items-center text-sm font-medium text-amber-700">View & apply →</span>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection
