@extends('layouts.dashboard')

@section('title', $category->name . ' - Videos')
@section('page-heading')
    <div class="flex items-center gap-2">
        <a href="{{ route('dashboard.categories.index') }}" class="text-slate-500 hover:text-slate-700">Categories</a>
        <span class="text-slate-400">/</span>
        <span>{{ $category->name }}</span>
    </div>
@endsection

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

    <div class="mb-6 flex justify-between items-center">
        <p class="text-slate-600 text-sm">Drag to reorder videos within this category. This order determines how they appear on the site.</p>
        <a href="{{ route('dashboard.categories.index') }}" class="text-sm font-medium text-sky-700 hover:text-amber-700">&larr; Back to Categories</a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h2 class="text-lg font-semibold text-slate-800">{{ $category->name }} Videos</h2>
            <span class="text-sm text-slate-500">{{ $videos->count() }} video(s)</span>
        </div>
        
        @if ($videos->isEmpty())
        <div class="p-12 text-center text-slate-500">
            <p class="text-lg font-medium">No videos in this category.</p>
            <p class="mt-1 text-sm">Add videos from the dashboard home page and assign them to this category.</p>
        </div>
        @else
        <form method="POST" action="{{ route('dashboard.categories.reorder-videos', $category) }}" id="reorder-form">
            @csrf
            @method('PUT')
            <ul class="divide-y divide-slate-200" id="video-list">
                @foreach ($videos as $video)
                <li class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition group" data-id="{{ $video->id }}">
                    <div class="flex flex-col gap-0.5 text-slate-300 group-hover:text-slate-500 transition-colors">
                        <button type="button" class="move-up rounded p-1 hover:bg-slate-200 hover:text-slate-700" aria-label="Move up">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                        </button>
                        <button type="button" class="move-down rounded p-1 hover:bg-slate-200 hover:text-slate-700" aria-label="Move down">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                    </div>
                    <span class="text-slate-400 text-sm w-8">{{ $loop->iteration }}</span>
                    
                    <div class="h-12 w-20 flex-shrink-0 bg-slate-100 rounded overflow-hidden relative border border-slate-200">
                        @if($video->toHomeArray()['thumbnail_url'])
                            <img src="{{ $video->toHomeArray()['thumbnail_url'] }}" alt="" class="h-full w-full object-cover">
                        @else
                             <div class="flex h-full w-full items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-800 truncate" title="{{ $video->title }}">{{ $video->title }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $video->youtube_id }}</p>
                    </div>
                    
                    <div class="text-xs text-slate-400 font-mono">
                        ID: {{ $video->id }}
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-between items-center">
                <span class="text-sm text-slate-500 italic">Changes are not saved until you click Save Order.</span>
                <button type="submit" form="reorder-form" class="rounded-lg bg-sky-600 px-6 py-2 font-medium text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 shadow-sm">
                    Save Order
                </button>
            </div>
        </form>
        @endif
    </div>

    @if (!$videos->isEmpty())
    <script>
    (function() {
        var list = document.getElementById('video-list');
        var form = document.getElementById('reorder-form');
        if (!list || !form) return;

        function getOrder() {
            return Array.from(list.querySelectorAll('li[data-id]')).map(function(li) { return li.getAttribute('data-id'); });
        }

        function updateHiddenInputs() {
            var existing = form.querySelectorAll('input[name="order[]"]');
            existing.forEach(function(el) { el.remove(); });
            getOrder().forEach(function(id) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order[]';
                input.value = id;
                form.appendChild(input);
            });
            // Update visual iteration numbers
            list.querySelectorAll('li').forEach(function(li, index) {
                var num = li.querySelector('span.text-slate-400.text-sm.w-8');
                if (num) num.textContent = index + 1;
            });
        }

        list.querySelectorAll('li').forEach(function(li) {
            var up = li.querySelector('.move-up');
            var down = li.querySelector('.move-down');
            
            // Allow drag and drop
            li.draggable = true;
            li.addEventListener('dragstart', function(e) {
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', null); // Firefox fix
                li.classList.add('opacity-50', 'bg-slate-50');
                window.dragSrcEl = li;
            });
            li.addEventListener('dragover', function(e) {
                if (e.preventDefault) e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                return false;
            });
            li.addEventListener('dragenter', function(e) {
                this.classList.add('border-amber-400', 'border-dashed', 'border-2');
            });
            li.addEventListener('dragleave', function(e) {
                 this.classList.remove('border-amber-400', 'border-dashed', 'border-2');
            });
            li.addEventListener('drop', function(e) {
                 if (e.stopPropagation) e.stopPropagation();
                 this.classList.remove('border-amber-400', 'border-dashed', 'border-2');
                 if (dragSrcEl !== this) {
                     var all = Array.from(list.children);
                     var fromIndex = all.indexOf(dragSrcEl);
                     var toIndex = all.indexOf(this);
                     if (fromIndex < toIndex) {
                         this.after(dragSrcEl);
                     } else {
                         this.before(dragSrcEl);
                     }
                     updateHiddenInputs();
                 }
                 return false;
            });
            li.addEventListener('dragend', function(e) {
                 this.classList.remove('opacity-50', 'bg-slate-50');
                 list.querySelectorAll('li').forEach(function(el) {
                    el.classList.remove('border-amber-400', 'border-dashed', 'border-2');
                 });
            });


            if (up) up.addEventListener('click', function() {
                var prev = li.previousElementSibling;
                if (prev) { list.insertBefore(li, prev); updateHiddenInputs(); }
            });
            if (down) down.addEventListener('click', function() {
                var next = li.nextElementSibling;
                if (next) { list.insertBefore(next, li); updateHiddenInputs(); }
            });
        });
        updateHiddenInputs();
    })();
    </script>
    @endif
@endsection
