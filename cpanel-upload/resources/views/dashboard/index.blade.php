@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Dashboard – Manage Videos</h1>

    @if (session('success'))
    <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-800">
        {{ session('success') }}
    </div>
    @endif

    {{-- Add video form --}}
    <div class="mb-10 rounded-lg border border-slate-200 bg-white p-6 shadow-sm max-w-2xl">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Add Video</h2>
        <form method="POST" action="{{ route('dashboard.videos.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="youtube_id" class="block text-sm font-medium text-slate-700 mb-1">YouTube Video ID</label>
                    <input type="text" name="youtube_id" id="youtube_id" value="{{ old('youtube_id') }}" required
                        placeholder="e.g. dQw4w9WgXcQ"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                    @error('youtube_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                    <select name="category" id="category" required
                        class="w-full rounded border border-slate-300 px-3 py-2 text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
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
                <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    placeholder="Video title"
                    class="w-full rounded border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="pdf" class="block text-sm font-medium text-slate-700 mb-1">Model PDF (optional)</label>
                <input type="file" name="pdf" id="pdf" accept=".pdf,application/pdf"
                    class="w-full rounded border border-slate-300 px-3 py-2 text-slate-800 file:mr-3 file:rounded file:border-0 file:bg-amber-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-amber-700 hover:file:bg-amber-100 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                @error('pdf')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded bg-amber-500 px-4 py-2 font-medium text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                Add Video
            </button>
        </form>
    </div>

    {{-- Videos by category --}}
    <div class="space-y-8">
        @foreach (['milling' => 'Milling', 'multi_axis' => 'Multi-Axis', 'turning' => 'Turning'] as $cat_key => $cat_label)
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">{{ $cat_label }}</h2>
            @php $videos = $videos_by_category[$cat_key] ?? collect(); @endphp
            @if ($videos->isEmpty())
            <p class="text-slate-500 text-sm">No videos yet. Add one above.</p>
            @else
            <ul class="divide-y divide-slate-200">
                @foreach ($videos as $video)
                <li class="flex flex-wrap items-center justify-between gap-2 py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <a href="https://www.youtube.com/watch?v={{ $video->youtube_id }}" target="_blank" rel="noopener" class="flex-shrink-0">
                            <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="" class="h-14 w-[200px] rounded object-cover">
                        </a>
                        <div class="min-w-0">
                            <p class="font-medium text-slate-800 truncate">{{ $video->title }}</p>
                            <p class="text-xs text-slate-500">{{ $video->youtube_id }}{{ $video->pdf ? ' · ' . $video->pdf : '' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('dashboard.videos.destroy', $video) }}" class="flex-shrink-0" onsubmit="return confirm('Remove this video?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded border border-red-200 bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-100">
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