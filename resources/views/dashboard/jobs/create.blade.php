@extends('layouts.dashboard')

@section('title', 'Add job')
@section('page-heading', 'Add job posting')

@section('content')
    <div class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('dashboard.jobs.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="200"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                <textarea name="description" id="description" rows="12" required maxlength="65000"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="location" class="mb-1 block text-sm font-medium text-slate-700">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" maxlength="200"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        placeholder="e.g. Remote, City">
                    @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="employment_type" class="mb-1 block text-sm font-medium text-slate-700">Employment type</label>
                    <input type="text" name="employment_type" id="employment_type" value="{{ old('employment_type') }}" maxlength="100"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        placeholder="e.g. Full-time, Contract">
                    @error('employment_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label for="sort_order" class="mb-1 block text-sm font-medium text-slate-700">Sort order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" max="999999"
                    class="w-full max-w-xs rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                <p class="mt-1 text-xs text-slate-500">Lower numbers appear first on the public list.</p>
                @error('sort_order')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" id="is_published" value="1" class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500" {{ (string) old('is_published', '1') === '1' ? 'checked' : '' }}>
                <label for="is_published" class="text-sm text-slate-700">Published (visible on Find jobs)</label>
            </div>
            @error('is_published')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-amber-500 px-4 py-2 font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    Create job
                </button>
                <a href="{{ route('dashboard.jobs.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
