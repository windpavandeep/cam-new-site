@extends('layouts.app')

@section('title', $job->title)

@section('content')
<div class="container mx-auto max-w-5xl px-4 py-10 md:py-14">
    <p class="mb-4">
        <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-sky-600 transition hover:text-sky-700">&larr; All jobs</a>
    </p>

    <header class="mb-8 rounded-2xl border border-sky-200 bg-gradient-to-br from-sky-50 via-white to-teal-50 px-6 py-7 md:px-8 md:py-9">
        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Career opportunity</p>
        <h1 class="mb-3 text-3xl font-bold text-slate-800 md:text-4xl">{{ $job->title }}</h1>
        <div class="flex flex-wrap gap-2 text-xs text-slate-600">
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
    </header>

    @if (session('success'))
    <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-5">
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70 md:p-8">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-sky-700">About this role</h2>
            <div class="whitespace-pre-wrap leading-relaxed text-slate-600">{{ $job->description }}</div>
        </div>

        <div class="lg:col-span-3 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/70 md:p-8">
        <h2 class="mb-6 text-lg font-semibold text-slate-800">Apply</h2>
        <p class="mb-6 text-sm text-slate-500">Share your details and upload your CV (PDF or Word, max 10&nbsp;MB).</p>

        <form method="POST" action="{{ route('jobs.apply', $job) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Full name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="100"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                    autocomplete="name">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required maxlength="255"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                    autocomplete="email">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">Phone <span class="font-normal text-slate-500">(optional)</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" maxlength="50"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                    autocomplete="tel">
                @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="cover_letter" class="mb-1 block text-sm font-medium text-slate-700">Cover message <span class="font-normal text-slate-500">(optional)</span></label>
                <textarea name="cover_letter" id="cover_letter" rows="5" maxlength="5000"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                    placeholder="Briefly tell us why you are a good fit">{{ old('cover_letter') }}</textarea>
                @error('cover_letter')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="cv" class="mb-1 block text-sm font-medium text-slate-700">CV / résumé</label>
                <input type="file" name="cv" id="cv" required accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-800 file:mr-3 file:rounded file:border-0 file:bg-sky-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-sky-700 hover:file:bg-sky-600/25 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('cv')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full rounded-xl bg-sky-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-white sm:w-auto">
                Submit application
            </button>
        </form>
        </div>
    </div>
</div>
@endsection
