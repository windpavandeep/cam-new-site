@extends('layouts.dashboard')

@section('title', 'Videos')
@section('page-heading', 'Manage Videos')

@section('content')
    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    <div class="space-y-8">
        {{-- Add video card --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-800">Add Video</h2>
            <form method="POST" action="{{ route('dashboard.videos.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="youtube_id" class="mb-1 block text-sm font-medium text-slate-700">YouTube Video ID</label>
                        <input type="text" name="youtube_id" id="youtube_id" value="{{ old('youtube_id') }}" required
                            placeholder="e.g. dQw4w9WgXcQ"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        @error('youtube_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="category" class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                        <select name="category" id="category" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                            @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        placeholder="Video title"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="pdf" class="mb-1 block text-sm font-medium text-slate-700">Model PDF (optional)</label>
                    <input type="file" name="pdf" id="pdf" accept=".pdf,application/pdf"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 file:mr-3 file:rounded file:border-0 file:bg-amber-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-amber-700 hover:file:bg-amber-100 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                    @error('pdf')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="rounded-lg bg-amber-500 px-4 py-2 font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    Add Video
                </button>
            </form>
        </div>

        {{-- Videos by category --}}
        <div class="space-y-6">
            @foreach (['milling' => 'Milling', 'multi_axis' => 'Multi-Axis', 'turning' => 'Turning'] as $cat_key => $cat_label)
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-800">{{ $cat_label }}</h2>
                @php $videos = $videos_by_category[$cat_key] ?? collect(); @endphp
                @if ($videos->isEmpty())
                <p class="text-slate-500 text-sm">No videos yet. Add one above.</p>
                @else
                <ul class="divide-y divide-slate-200">
                    @foreach ($videos as $video)
                    <li class="flex flex-wrap items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                        <div class="flex min-w-0 items-center gap-4">
                            <a href="https://www.youtube.com/watch?v={{ $video->youtube_id }}" target="_blank" rel="noopener" class="flex-shrink-0 overflow-hidden rounded-lg">
                                <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="" class="h-16 w-[240px] object-cover">
                            </a>
                            <div class="min-w-0">
                                <p class="font-medium text-slate-800 truncate">{{ $video->title }}</p>
                                <p class="text-xs text-slate-500">{{ $video->youtube_id }}{{ $video->pdf ? ' · ' . $video->pdf : '' }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('dashboard.videos.destroy', $video) }}" class="flex-shrink-0" onsubmit="return confirm('Remove this video?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 transition hover:bg-red-100">
                                Remove
                            </button>
                        </form>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
        </div>
    </div>
@endsection
