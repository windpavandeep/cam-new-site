@extends('layouts.dashboard')

@section('title', 'Media Library')
@section('page-heading', 'Media Library')

@section('content')
    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
    @endif

    <div class="rounded-xl border border-amber-200 bg-amber-50/80 px-4 py-3 mb-6 text-sm text-amber-800">
        <p class="font-medium">Upload files to the global media library. Set a <strong>thumbnail</strong> and <strong>tooltip</strong> for each item. Media can be assigned to YouTube videos (Videos) as the model file for that video.</p>
        <p class="mt-1 text-amber-700">All file types allowed. Max file size: 100 MB. Thumbnail max: 2 MB.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm mb-8">
        <h2 class="mb-4 text-lg font-semibold text-slate-800">Upload media</h2>
        <form method="POST" action="{{ route('dashboard.media.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="file" class="mb-1 block text-sm font-medium text-slate-700">File</label>
                <input type="file" name="file" id="file" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 file:mr-3 file:rounded file:border-0 file:bg-amber-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-amber-700 hover:file:bg-amber-100 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                @error('file')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="tooltip" class="mb-1 block text-sm font-medium text-slate-700">Tooltip (optional)</label>
                <input type="text" name="tooltip" id="tooltip" value="{{ old('tooltip') }}" maxlength="500"
                    placeholder="Short description shown on hover"d
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                @error('tooltip')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="thumbnail" class="mb-1 block text-sm font-medium text-slate-700">Thumbnail (optional)</label>
                <input type="file" name="thumbnail" id="thumbnail" accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 file:mr-3 file:rounded file:border-0 file:bg-amber-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-amber-700 hover:file:bg-amber-100 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                @error('thumbnail')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded-lg bg-amber-500 px-4 py-2 font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                Add to library
            </button>
        </form>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <h2 class="px-6 py-4 text-lg font-semibold text-slate-800 border-b border-slate-200">Library items</h2>
        @if ($media->isEmpty())
        <div class="p-12 text-center text-slate-500">
            <p class="text-lg font-medium">No media yet.</p>
            <p class="mt-1 text-sm">Upload a file above. Then assign media to videos from the Videos page.</p>
        </div>
        @else
        <ul class="divide-y divide-slate-200">
            @foreach ($media as $item)
            <li class="flex flex-wrap items-center gap-4 p-4 sm:p-6">
                <div class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-slate-200 flex items-center justify-center">
                    @if ($item->thumbnail_url)
                        <img src="{{ \App\Support\PublicAsset::url($item->thumbnail_path) }}" alt="" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl text-slate-400" title="No thumbnail">📄</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-slate-800 truncate">{{ $item->original_name }}</p>
                    @if ($item->tooltip)
                        <p class="text-sm text-slate-500 line-clamp-2 mt-0.5" title="{{ $item->tooltip }}">{{ $item->tooltip }}</p>
                    @else
                        <p class="text-sm text-slate-400 italic mt-0.5">No tooltip</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-1">Used by {{ $item->videos_count }} video(s)</p>
                </div>
                <div class="flex flex-shrink-0 items-center gap-2">
                    <form method="POST" action="{{ route('dashboard.media.update', $item) }}" enctype="multipart/form-data" class="inline-flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="tooltip" value="{{ $item->tooltip }}" placeholder="Tooltip"
                            class="rounded border border-slate-300 px-2 py-1 text-sm w-40">
                        <label class="cursor-pointer rounded border border-slate-300 px-2 py-1 text-sm bg-slate-50 hover:bg-slate-100">
                            <span class="text-slate-700">Thumbnail</span>
                            <input type="file" name="thumbnail" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                        <button type="submit" class="rounded-lg border border-amber-300 bg-amber-50 px-3 py-1.5 text-sm font-medium text-amber-800 hover:bg-amber-100">
                            Update
                        </button>
                    </form>
                    <form action="{{ route('dashboard.media.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Remove this media? Unassign from videos first if in use.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100">
                            Remove
                        </button>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
@endsection
