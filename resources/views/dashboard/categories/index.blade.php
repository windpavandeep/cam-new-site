@extends('layouts.dashboard')

@section('title', 'Video Categories')
@section('page-heading', 'Video Categories')

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

    <p class="mb-6 text-slate-600 text-sm">Categories appear on the home and videos pages. Reorder to change tab order. Create categories and assign them when adding videos.</p>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm mb-8">
        <h2 class="mb-4 text-lg font-semibold text-slate-800">Add category</h2>
        <form method="POST" action="{{ route('dashboard.categories.store') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            <div class="min-w-[200px]">
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="100"
                    placeholder="e.g. Milling"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:border-sky-600 focus:outline-none focus:ring-1 focus:ring-sky-500">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 font-medium text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                Add category
            </button>
        </form>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <h2 class="px-6 py-4 text-lg font-semibold text-slate-800 border-b border-slate-200">Categories (drag to reorder or use arrows)</h2>
        @if ($categories->isEmpty())
        <div class="p-12 text-center text-slate-500">
            <p class="text-lg font-medium">No categories yet.</p>
            <p class="mt-1 text-sm">Add a category above. Then assign categories to videos when adding videos.</p>
        </div>
        @else
        <form method="POST" action="{{ route('dashboard.categories.reorder') }}" id="reorder-form">
            @csrf
            @method('PUT')
            <ul class="divide-y divide-slate-200" id="category-list">
                @foreach ($categories as $category)
                <li class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition" data-id="{{ $category->id }}">
                    <div class="flex flex-col gap-0.5 text-slate-400">
                        <button type="button" class="move-up rounded p-1 hover:bg-slate-200 hover:text-slate-700" aria-label="Move up">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                        </button>
                        <button type="button" class="move-down rounded p-1 hover:bg-slate-200 hover:text-slate-700" aria-label="Move down">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                    </div>
                    <span class="text-slate-400 text-sm w-8">{{ $loop->iteration }}</span>
                    <div class="flex-1 min-w-0">
                        <span class="font-medium text-slate-800">{{ $category->name }}</span>
                        <span class="text-slate-500 text-sm ml-2">({{ $category->videos_count }} video(s))</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('dashboard.categories.update', $category) }}" class="inline-flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $category->name }}" required maxlength="100"
                                class="rounded border border-slate-300 px-2 py-1 text-sm w-40">
                            <button type="submit" class="rounded border border-amber-300 bg-amber-50 px-2 py-1 text-sm font-medium text-amber-800 hover:bg-amber-100">Save</button>
                        </form>
                        <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category? Move or remove its videos first.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100">Remove</button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                <button type="submit" form="reorder-form" class="rounded-lg bg-sky-600 px-4 py-2 font-medium text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Save order
                </button>
            </div>
        </form>
        @endif
    </div>

    @if (!$categories->isEmpty())
    <script>
    (function() {
        var list = document.getElementById('category-list');
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
        }

        list.querySelectorAll('li').forEach(function(li) {
            var up = li.querySelector('.move-up');
            var down = li.querySelector('.move-down');
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
