@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- 1. Banner carousel (dynamic from dashboard) --}}
<section class="banner-carousel relative h-[280px] sm:h-[340px] md:h-[400px] overflow-hidden bg-slate-800">
    <div class="carousel-track flex h-full transition-transform duration-500 ease-out" id="carouselTrack">
        @forelse ($slider_slides as $slide)
        <div class="carousel-slide flex-shrink-0 w-full h-full min-h-full relative flex items-center justify-center bg-slate-800">
            <img src="{{ $slide->image_url }}" alt="Slide" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
            <div class="absolute inset-0 "></div>
        </div>
        @empty
        <div class="carousel-slide flex-shrink-0 w-full h-full min-h-full bg-gradient-to-br from-amber-900/50 via-slate-800 to-slate-700 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-2">CAM Solutions</h2>
                <p class="text-slate-300 text-lg">CNC programming training — add slides from the dashboard</p>
            </div>
        </div>
        @endforelse
    </div>
    <button type="button" id="carouselPrev" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 text-white flex items-center justify-center hover:bg-black/60 transition" aria-label="Previous">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    <button type="button" id="carouselNext" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 text-white flex items-center justify-center hover:bg-black/60 transition" aria-label="Next">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2" id="carouselDots"></div>
</section>

<div class="container mx-auto px-4 py-8 md:py-12">
    {{-- 2. Question: "Do you want to learn CNC programming?" --}}
    <section class="text-center max-w-2xl mx-auto mb-10 md:mb-14">
        <div class="inline-block px-6 py-4 rounded-2xl bg-gradient-to-r from-amber-500/15 to-amber-600/10 border border-amber-400/30 shadow-lg">
            <p class="text-xl md:text-2xl font-semibold text-slate-800">Do you want to learn <span class="text-amber-600">CNC Programming</span>?</p>
            <p class="text-slate-600 mt-2 text-sm md:text-base">Explore Milling, Multi-Axis, and Turning with Mastercam.</p>
        </div>
    </section>

    {{-- 3. Tabs: dynamic categories --}}
    <section>
        @if (!empty($categories_with_videos))
        <div class="flex flex-wrap border-b border-slate-200 gap-1 mb-6" role="tablist">
            @foreach ($categories_with_videos as $index => $cat)
            <button type="button" role="tab" id="tab-{{ $cat['slug'] }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="panel-{{ $cat['slug'] }}"
                class="tab-btn px-5 py-3 font-medium text-sm rounded-t-lg border-b-2 -mb-px {{ $index === 0 ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                {{ $cat['name'] }}
            </button>
            @endforeach
        </div>

        @foreach ($categories_with_videos as $index => $cat)
        <div id="panel-{{ $cat['slug'] }}" role="tabpanel" class="tab-panel {{ $index === 0 ? '' : 'hidden' }}">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($cat['videos'] as $video)
                <article class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition">
                    <button type="button" class="video-modal-trigger block w-full aspect-video bg-slate-200 cursor-pointer text-left border-0 p-0"
                        data-youtube-id="{{ $video['id'] }}" aria-label="Play {{ $video['title'] }}">
                        <img src="https://img.youtube.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="{{ $video['title'] }}"
                            class="w-full h-full object-cover" loading="lazy">
                    </button>
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-800 mb-3 line-clamp-2">{{ $video['title'] }}</h3>
                        @if(!empty($video['download_path']))
                        <a href="{{ route('download.model', ['path' => $video['download_path']]) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition"
                            @if(!empty($video['tooltip'])) title="{{ $video['tooltip'] }}" @endif>
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
                    <p class="text-lg font-medium">{{ $cat['name'] }} content coming soon.</p>
                    <p class="mt-2">Add videos from the dashboard.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
        @else
        <div class="bg-slate-100 rounded-xl border border-slate-200 p-8 text-center text-slate-600">
            <p class="text-lg font-medium">No categories yet.</p>
            <p class="mt-2">Add categories and videos from the dashboard.</p>
        </div>
        @endif
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
        // --- Carousel ---
        var track = document.getElementById('carouselTrack');
        var prev = document.getElementById('carouselPrev');
        var next = document.getElementById('carouselNext');
        var dotsEl = document.getElementById('carouselDots');
        var slides = track ? track.querySelectorAll('.carousel-slide') : [];
        var total = slides.length;
        var idx = 0;

        function go(n) {
            idx = (n + total) % total;
            if (track) track.style.transform = 'translateX(-' + (idx * 100) + '%)';
            dotsEl.querySelectorAll('button').forEach(function(d, i) {
                d.setAttribute('aria-current', i === idx ? 'true' : 'false');
                d.classList.toggle('bg-white', i === idx);
                d.classList.toggle('bg-white/50', i !== idx);
            });
        }

        if (total > 0) {
            for (var i = 0; i < total; i++) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'w-2.5 h-2.5 rounded-full transition ' + (i === 0 ? 'bg-white' : 'bg-white/50');
                b.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                b.setAttribute('aria-current', i === 0 ? 'true' : 'false');
                (function(n) {
                    b.addEventListener('click', function() {
                        go(n);
                    });
                })(i);
                dotsEl.appendChild(b);
            }
            if (prev) prev.addEventListener('click', function() {
                go(idx - 1);
            });
            if (next) next.addEventListener('click', function() {
                go(idx + 1);
            });
            setInterval(function() {
                go(idx + 1);
            }, 5000);
        }

        // --- Tabs (dynamic categories) ---
        var tabIds = Array.from(document.querySelectorAll('.tab-btn')).map(function(btn) {
            var id = btn.getAttribute('id');
            return id ? id.replace('tab-', '') : '';
        }).filter(Boolean);
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