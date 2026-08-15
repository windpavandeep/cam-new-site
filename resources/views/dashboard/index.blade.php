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
            <form method="POST" action="{{ route('dashboard.videos.store') }}" class="space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="youtube_id" class="mb-1 block text-sm font-medium text-slate-700">YouTube Video ID</label>
                        <input type="text" name="youtube_id" id="youtube_id" value="{{ old('youtube_id') }}" required
                            placeholder="e.g. dQw4w9WgXcQ"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                        @error('youtube_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="category_id" class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                        @if ($categories->isEmpty())
                        <p class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">Add at least one category from <a href="{{ route('dashboard.categories.index') }}" class="font-medium underline">Categories</a> first.</p>
                        @else
                        <select name="category_id" id="category_id" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                            @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string) old('category_id') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @endif
                        @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        placeholder="Video title"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="media_id" class="mb-1 block text-sm font-medium text-slate-700">Model from Media Library (optional)</label>
                    <select name="media_id" id="media_id"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                        <option value="">— None —</option>
                        @foreach ($media as $m)
                        <option value="{{ $m->id }}" {{ (string) old('media_id') === (string) $m->id ? 'selected' : '' }}>{{ $m->original_name }}{{ $m->tooltip ? ' · ' . \Illuminate\Support\Str::limit($m->tooltip, 40) : '' }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Model can only be assigned from the library. Upload files to Media Library first if needed.</p>
                    @error('media_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 font-medium text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Add Video
                </button>
            </form>
        </div>

        {{-- Videos by category --}}
        <div class="space-y-6">
            @foreach ($categories as $cat)
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-800">{{ $cat->name }}</h2>
                @php $videos = $videos_by_category[$cat->id] ?? collect(); @endphp
                @if ($videos->isEmpty())
                <p class="mt-4 text-slate-500 text-sm">No videos yet. Add one above.</p>
                @else
                <p class="mt-1 mb-3 text-xs text-slate-500">Drag by the grip to reorder. This order is used on the home and videos pages.</p>
                <ul class="divide-y divide-slate-200 video-sortable-list rounded-lg border border-slate-100" data-category-id="{{ $cat->id }}" id="video-list-{{ $cat->id }}">
                    @foreach ($videos as $video)
                    <li draggable="true" data-video-id="{{ $video->id }}"
                        class="video-sortable-item flex flex-wrap items-center justify-between gap-4 bg-white py-4 px-2 first:pt-3 last:pb-3 transition hover:bg-slate-50/80">
                        <div class="flex min-w-0 flex-1 items-center gap-3 sm:gap-4">
                            <span class="drag-handle flex-shrink-0 cursor-grab select-none rounded p-1 text-slate-400 hover:bg-slate-200 hover:text-slate-600 active:cursor-grabbing" title="Drag to reorder" aria-hidden="true">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm-2 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm8-14a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm-2 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm2 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm6-16a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm-2 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm2 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/></svg>
                            </span>
                            <a href="https://www.youtube.com/watch?v={{ $video->youtube_id }}" target="_blank" rel="noopener" class="flex-shrink-0 overflow-hidden rounded-lg">
                                <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="" class="h-16 w-[240px] object-cover">
                            </a>
                            <div class="min-w-0">
                                <p class="font-medium text-slate-800 truncate">{{ $video->title }}</p>
                                <p class="text-xs text-slate-500">{{ $video->youtube_id }}{{ $video->media_id && $video->media ? ' · Media: ' . $video->media->original_name : ($video->pdf ? ' · ' . $video->pdf : '') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-shrink-0 flex-wrap items-center gap-2">
                            <button type="button"
                                class="js-copy-video-link rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1"
                                data-video-url="https://www.youtube.com/watch?v={{ $video->youtube_id }}"
                                title="Copy YouTube link">
                                <span class="copy-link-label">Copy link</span>
                            </button>
                            <form method="POST" action="{{ route('dashboard.videos.destroy', $video) }}" class="inline" onsubmit="return confirm('Remove this video?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 transition hover:bg-red-100">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <div id="dashboard-reorder-toast" class="pointer-events-none fixed bottom-6 right-6 z-50 hidden rounded-lg bg-slate-900 px-4 py-2 text-sm text-white shadow-lg" role="status" aria-live="polite"></div>
@endsection

@push('scripts')
<script>
(function() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) return;
    var reorderUrl = @json(route('dashboard.videos.reorder'));
    var toast = document.getElementById('dashboard-reorder-toast');
    var dragSrc = null;

    function showToast(msg, isError) {
        if (!toast) return;
        toast.textContent = msg;
        toast.classList.remove('bg-red-900', 'bg-slate-900');
        toast.classList.add(isError ? 'bg-red-900' : 'bg-slate-900');
        toast.classList.remove('hidden');
        clearTimeout(showToast._t);
        showToast._t = setTimeout(function() { toast.classList.add('hidden'); }, 2500);
    }

    function saveOrder(ul) {
        var categoryId = ul.getAttribute('data-category-id');
        if (!categoryId) return;
        var ids = Array.from(ul.querySelectorAll('li[data-video-id]')).map(function(li) {
            return parseInt(li.getAttribute('data-video-id'), 10);
        });
        fetch(reorderUrl, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': meta.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ category_id: parseInt(categoryId, 10), order: ids }),
            credentials: 'same-origin',
        })
            .then(function(r) {
                return r.json().then(function(data) {
                    if (!r.ok) throw new Error(data.message || 'Save failed');
                    return data;
                });
            })
            .then(function() {
                showToast('Order saved', false);
            })
            .catch(function(err) {
                showToast(err.message || 'Could not save order', true);
            });
    }

    document.querySelectorAll('ul.video-sortable-list').forEach(function(ul) {
        ul.addEventListener('dragstart', function(e) {
            var li = e.target.closest('li.video-sortable-item');
            if (!li || !ul.contains(li)) return;
            if (e.target.closest('button, a')) {
                e.preventDefault();
                return;
            }
            dragSrc = li;
            li.classList.add('opacity-60', 'ring-2', 'ring-amber-400');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', li.getAttribute('data-video-id'));
        });

        ul.addEventListener('dragend', function(e) {
            var li = e.target.closest('li.video-sortable-item');
            if (li) {
                li.classList.remove('opacity-60', 'ring-2', 'ring-amber-400');
            }
            if (dragSrc) {
                saveOrder(ul);
            }
            dragSrc = null;
        });

        ul.addEventListener('dragover', function(e) {
            e.preventDefault();
            if (!dragSrc || !ul.contains(dragSrc)) return;
            var target = e.target.closest('li.video-sortable-item');
            if (!target || target === dragSrc || !ul.contains(target)) return;
            var rect = target.getBoundingClientRect();
            var after = (e.clientY - rect.top) > rect.height / 2;
            if (after) {
                ul.insertBefore(dragSrc, target.nextSibling);
            } else {
                ul.insertBefore(dragSrc, target);
            }
        });

        ul.addEventListener('drop', function(e) {
            e.preventDefault();
        });
    });

    document.querySelectorAll('.js-copy-video-link').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = btn.getAttribute('data-video-url');
            var label = btn.querySelector('.copy-link-label');
            function feedback(ok) {
                if (!label) return;
                var t = label.textContent;
                label.textContent = ok ? 'Copied!' : 'Failed';
                setTimeout(function() { label.textContent = t; }, 2000);
            }
            if (!url) return;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() { feedback(true); }).catch(function() { feedback(false); });
            } else {
                var ta = document.createElement('textarea');
                ta.value = url;
                ta.style.position = 'fixed';
                ta.style.left = '-9999px';
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                    feedback(true);
                } catch (err) {
                    feedback(false);
                }
                document.body.removeChild(ta);
            }
        });
    });
})();
</script>
@endpush
