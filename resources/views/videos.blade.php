@extends('layouts.app')

@section('title', 'Videos')

@section('content')
<div class="container mx-auto px-4 py-8 md:py-12">
    <section class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">Videos</h1>
        <p class="text-slate-600">Browse Milling, Multi-axis, and Turning tutorials. Click a video to play.</p>
    </section>

    {{-- Tabs: Milling (default), Multi Axis, Turning --}}
    <section>
        <div class="flex border-b border-slate-200 gap-1 mb-6" role="tablist">
            <button type="button" role="tab" id="tab-milling" aria-selected="true" aria-controls="panel-milling"
                class="tab-btn px-5 py-3 font-medium text-sm rounded-t-lg border-b-2 border-amber-500 bg-amber-50 text-amber-700 -mb-px">
                Milling
            </button>
            <button type="button" role="tab" id="tab-multiaxis" aria-selected="false" aria-controls="panel-multiaxis"
                class="tab-btn px-5 py-3 font-medium text-sm rounded-t-lg border-b-2 border-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-800 -mb-px">
                Multi-axis
            </button>
            <button type="button" role="tab" id="tab-turning" aria-selected="false" aria-controls="panel-turning"
                class="tab-btn px-5 py-3 font-medium text-sm rounded-t-lg border-b-2 border-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-800 -mb-px">
                Turning
            </button>
        </div>

        {{-- Milling panel (default visible) --}}
        <div id="panel-milling" role="tabpanel" class="tab-panel">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($milling_videos as $video)
                <article class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition">
                    <button type="button" class="video-modal-trigger block w-full aspect-video bg-slate-200 cursor-pointer text-left border-0 p-0"
                        data-youtube-id="{{ $video['id'] }}" aria-label="Play {{ $video['title'] }}">
                        <img src="https://img.youtube.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="{{ $video['title'] }}"
                            class="w-full h-full object-cover" loading="lazy">
                    </button>
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-800 mb-3 line-clamp-2">{{ $video['title'] }}</h3>
                        @if(!empty($video['pdf']))
                        <a href="{{ route('download.model', ['filename' => $video['pdf']]) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Model PDF
                        </a>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
        </div>

        {{-- Multi-axis panel --}}
        <div id="panel-multiaxis" role="tabpanel" class="tab-panel hidden">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($multiaxis_videos as $video)
                <article class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition">
                    <button type="button" class="video-modal-trigger block w-full aspect-video bg-slate-200 cursor-pointer text-left border-0 p-0"
                        data-youtube-id="{{ $video['id'] }}" aria-label="Play {{ $video['title'] }}">
                        <img src="https://img.youtube.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="{{ $video['title'] }}"
                            class="w-full h-full object-cover" loading="lazy">
                    </button>
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-800 mb-3 line-clamp-2">{{ $video['title'] }}</h3>
                        @if(!empty($video['pdf']))
                        <a href="{{ route('download.model', ['filename' => $video['pdf']]) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Model PDF
                        </a>
                        @endif
                    </div>
                </article>
                @empty
                <div class="col-span-full bg-slate-100 rounded-xl border border-slate-200 p-8 text-center text-slate-600">
                    <p class="text-lg font-medium">Multi-axis content coming soon.</p>
                    <p class="mt-2">Check back for 4-axis and 5-axis Mastercam tutorials.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Turning panel --}}
        <div id="panel-turning" role="tabpanel" class="tab-panel hidden">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($turning_videos as $video)
                <article class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition">
                    <button type="button" class="video-modal-trigger block w-full aspect-video bg-slate-200 cursor-pointer text-left border-0 p-0"
                        data-youtube-id="{{ $video['id'] }}" aria-label="Play {{ $video['title'] }}">
                        <img src="https://img.youtube.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="{{ $video['title'] }}"
                            class="w-full h-full object-cover" loading="lazy">
                    </button>
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-800 mb-3 line-clamp-2">{{ $video['title'] }}</h3>
                        @if(!empty($video['pdf']))
                        <a href="{{ route('download.model', ['filename' => $video['pdf']]) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Model PDF
                        </a>
                        @endif
                    </div>
                </article>
                @empty
                <div class="col-span-full bg-slate-100 rounded-xl border border-slate-200 p-8 text-center text-slate-600">
                    <p class="text-lg font-medium">Turning content coming soon.</p>
                    <p class="mt-2">Lathe and turning tutorials will be available here.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</div>

{{-- YouTube video modal --}}
<div id="youtubeModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="youtubeModalTitle">
    <div id="youtubeModalBackdrop" class="absolute inset-0 bg-black/70 transition-opacity" aria-hidden="true"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-black rounded-lg overflow-hidden shadow-2xl">
            <button type="button" id="youtubeModalClose" class="absolute top-2 right-2 z-10 w-10 h-10 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition focus:outline-none focus:ring-2 focus:ring-white" aria-label="Close video">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="aspect-video w-full">
                <iframe id="youtubeModalIframe" class="w-full h-full" src="" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        // --- Tabs ---
        var tabIds = ['milling', 'multiaxis', 'turning'];
        tabIds.forEach(function(id) {
            var btn = document.getElementById('tab-' + id);
            var panel = document.getElementById('panel-' + id);
            if (!btn || !panel) return;
            btn.addEventListener('click', function() {
                tabIds.forEach(function(oid) {
                    var ob = document.getElementById('tab-' + oid);
                    var op = document.getElementById('panel-' + oid);
                    if (ob) {
                        ob.setAttribute('aria-selected', oid === id ? 'true' : 'false');
                        ob.classList.toggle('border-amber-500', oid === id);
                        ob.classList.toggle('bg-amber-50', oid === id);
                        ob.classList.toggle('text-amber-700', oid === id);
                        ob.classList.toggle('border-transparent', oid !== id);
                        ob.classList.toggle('text-slate-600', oid !== id);
                    }
                    if (op) op.classList.toggle('hidden', oid !== id);
                });
            });
        });

        // --- YouTube modal ---
        var modal = document.getElementById('youtubeModal');
        var modalIframe = document.getElementById('youtubeModalIframe');
        var modalClose = document.getElementById('youtubeModalClose');
        var modalBackdrop = document.getElementById('youtubeModalBackdrop');

        function openVideoModal(youtubeId) {
            if (!modal || !modalIframe) return;
            modalIframe.src = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal() {
            if (!modal || !modalIframe) return;
            modal.classList.add('hidden');
            modalIframe.src = '';
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.video-modal-trigger').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-youtube-id');
                if (id) openVideoModal(id);
            });
        });
        if (modalClose) modalClose.addEventListener('click', closeVideoModal);
        if (modalBackdrop) modalBackdrop.addEventListener('click', closeVideoModal);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeVideoModal();
            }
        });
    })();
</script>
@endpush
@endsection
