@extends('layouts.app')

@section('title', $job->title)

@section('content')
<div class="container mx-auto px-4 py-10 md:py-14 max-w-3xl">
    <p class="mb-4">
        <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">← All jobs</a>
    </p>

    <header class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-3">{{ $job->title }}</h1>
        <div class="flex flex-wrap gap-3 text-sm text-slate-600">
            @if ($job->location)
            <span>{{ $job->location }}</span>
            @endif
            @if ($job->employment_type)
            @if ($job->location)<span class="text-slate-300">·</span>@endif
            <span>{{ $job->employment_type }}</span>
            @endif
        </div>
    </header>

    @if (session('success'))
    <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm mb-10">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">About this role</h2>
        <div class="text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $job->description }}</div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900 mb-6">Apply</h2>
        <p class="text-sm text-slate-600 mb-6">Share your details and upload your CV (PDF or Word, max 10&nbsp;MB).</p>

        <form method="POST" action="{{ route('jobs.apply', $job) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Full name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="100"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                    autocomplete="name">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required maxlength="255"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                    autocomplete="email">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">Phone <span class="text-slate-400 font-normal">(optional)</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" maxlength="50"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                    autocomplete="tel">
                @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="cover_letter" class="mb-1 block text-sm font-medium text-slate-700">Cover message <span class="text-slate-400 font-normal">(optional)</span></label>
                <textarea name="cover_letter" id="cover_letter" rows="5" maxlength="5000"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                    placeholder="Briefly tell us why you are a good fit">{{ old('cover_letter') }}</textarea>
                @error('cover_letter')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="cv" class="mb-1 block text-sm font-medium text-slate-700">CV / résumé</label>
                <input type="file" name="cv" id="cv" required accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 file:mr-3 file:rounded file:border-0 file:bg-amber-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-amber-700 hover:file:bg-amber-100 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                @error('cv')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full sm:w-auto rounded-xl bg-amber-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                Submit application
            </button>
        </form>
    </div>
</div>
@endsection
