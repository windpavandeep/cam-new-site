@extends('layouts.dashboard')

@section('title', 'Edit job')
@section('page-heading', 'Edit job posting')

@section('content')
    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="mb-4 text-sm text-slate-500">URL slug: <code class="rounded bg-slate-100 px-1.5 py-0.5 text-slate-800">{{ $job->slug }}</code> (updates when you change the title if needed)</p>

        <form             method="POST" action="{{ route('dashboard.jobs.update', $job) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}" required maxlength="200"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                <textarea name="description" id="description" rows="12" required maxlength="65000"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">{{ old('description', $job->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="location" class="mb-1 block text-sm font-medium text-slate-700">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $job->location) }}" maxlength="200"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="employment_type" class="mb-1 block text-sm font-medium text-slate-700">Employment type</label>
                    <input type="text" name="employment_type" id="employment_type" value="{{ old('employment_type', $job->employment_type) }}" maxlength="100"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    @error('employment_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label for="sort_order" class="mb-1 block text-sm font-medium text-slate-700">Sort order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $job->sort_order) }}" min="0" max="999999"
                    class="w-full max-w-xs rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('sort_order')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" id="is_published" value="1" class="h-4 w-4 rounded border-slate-300 text-sky-700 focus:ring-sky-500" {{ (string) old('is_published', $job->is_published ? '1' : '0') === '1' ? 'checked' : '' }}>
                <label for="is_published" class="text-sm text-slate-700">Published (visible on Find jobs)</label>
            </div>
            @error('is_published')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 font-medium text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Save changes
                </button>
                <a href="{{ route('dashboard.jobs.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Back to list</a>
                @if ($job->is_published)
                <a href="{{ route('jobs.show', $job) }}" target="_blank" rel="noopener" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">View public page</a>
                @endif
            </div>
        </form>
    </div>
@endsection
