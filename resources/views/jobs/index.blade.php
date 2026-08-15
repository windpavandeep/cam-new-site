@extends('layouts.app')

@section('title', 'Find jobs')

@section('content')
<div class="container mx-auto max-w-6xl px-4 py-10 md:py-14">
    <header class="mb-10 rounded-2xl border border-sky-200 bg-gradient-to-br from-sky-50 via-white to-teal-50 px-6 py-7 md:px-8 md:py-9">
        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Careers</p>
        <h1 class="mb-3 text-3xl font-bold text-slate-800 md:text-4xl">Find jobs</h1>
        <p class="max-w-2xl text-lg text-slate-600">
            Open positions at CAM Solutions. Select a role to read more and apply with your CV.
        </p>
    </header>

    @if ($jobs->isEmpty())
    <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm shadow-slate-200/70">
        <p class="text-slate-600">There are no open listings right now. Please check again later.</p>
    </div>
    @else
    <ul class="grid gap-4 sm:grid-cols-2">
        @foreach ($jobs as $job)
        <li>
            <a href="{{ route('jobs.show', $job) }}" class="group block rounded-2xl border border-slate-200 bg-white p-6 shadow-md shadow-slate-200/80 transition duration-200 hover:-translate-y-1 hover:border-sky-300">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">{{ $job->title }}</h2>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                            @if ($job->location)
                            <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1">
                                <svg class="h-3.5 w-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $job->location }}
                            </span>
                            @endif
                            @if ($job->employment_type)
                            <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1">
                                <svg class="h-3.5 w-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $job->employment_type }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <span class="inline-flex items-center text-sm font-semibold text-sky-600 transition group-hover:text-sky-700">View & apply &rarr;</span>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection
