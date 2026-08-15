@extends('layouts.app')

@section('title', 'Videos')

@section('content')
<div class="container mx-auto px-4 py-8 md:py-12">
    <section class="reveal mb-8">
        <h1 class="font-display mb-2 text-3xl font-bold text-cam-ink">Videos</h1>
        <p class="text-slate-500">Browse Milling, Multi-axis, and Turning tutorials. Click play video to start.</p>
    </section>

    {{-- Tabs: dynamic categories --}}
    <section>
        @if (!empty($categories_with_videos))
        <div class="mb-6 flex flex-wrap items-center gap-2 border-b border-slate-200" role="tablist">
            @foreach ($categories_with_videos as $index => $cat)
            <button type="button" role="tab" id="tab-{{ $cat['slug'] }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="panel-{{ $cat['slug'] }}"
                class="tab-btn -mb-px rounded-t-xl border-b-2 px-4 py-2.5 text-sm font-medium transition {{ $index === 0 ? 'border-cyan-400 bg-sky-50 text-sky-700 shadow-sm' : 'border-transparent text-slate-500 hover:bg-slate-100 hover:text-slate-700' }}">
                {{ $cat['name'] }}
            </button>
            @endforeach
        </div>

        @foreach ($categories_with_videos as $index => $cat)
        <div id="panel-{{ $cat['slug'] }}" role="tabpanel" class="tab-panel {{ $index === 0 ? '' : 'hidden' }}">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                @forelse ($cat['videos'] as $video)
                <article class="group w-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md shadow-slate-200/80 transition duration-200 hover:-translate-y-1 hover:border-sky-300">
                    <button type="button" class="video-modal-trigger block w-full aspect-video bg-slate-200 cursor-pointer text-left border-0 p-0"
                        data-youtube-id="{{ $video['id'] }}" aria-label="Play {{ $video['title'] }}">
                        <img src="https://img.youtube.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="{{ $video['title'] }}"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                        @php
                            $badge_palette = ['bg-emerald-500/90 text-emerald-50', 'bg-sky-600/90 text-amber-50', 'bg-violet-500/90 text-violet-50', 'bg-rose-500/90 text-rose-50'];
                            $badge_labels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
                            $badge_index = ($loop->iteration - 1) % 4;
                        @endphp
                        <span class="pointer-events-none absolute left-2 top-2 inline-flex items-center rounded-md px-2 py-1 text-[10px] font-semibold uppercase tracking-wide {{ $badge_palette[$badge_index] }}">{{ $badge_labels[$badge_index] }}</span>
                    </button>
                    <div class="p-3">
                        <h3 class="mb-2 min-h-[3rem] line-clamp-2 text-base font-semibold leading-snug text-slate-800">{{ $video['title'] }}</h3>
                        <div class="mb-3 flex items-center gap-4 text-xs text-slate-600">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8zm0 0V5m0 10v4m7-7h-4M5 12H1" />
                                </svg>
                                {{ 10 + ($loop->iteration % 12) }} Lessons
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ number_format(3 + (($loop->iteration % 8) * 0.5), 1) }} Hours
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button"
                                class="js-copy-video-link inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition hover:border-sky-300 hover:text-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1 focus:ring-offset-white"
                                data-video-url="https://www.youtube.com/watch?v={{ $video['id'] }}"
                                title="Copy YouTube link">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span class="copy-link-label">Copy link</span>
                            </button>
                            @if(!empty($video['download_path']))
                                @auth
                                <a href="{{ route('download.model', ['path' => $video['download_path']]) }}"
                                    class="js-model-download inline-flex items-center gap-1 rounded-lg border border-sky-200 bg-sky-600 px-2.5 py-1.5 text-xs font-semibold text-white transition hover:bg-sky-500"
                                    data-download-path="{{ $video['download_path'] }}"
                                    @if(!empty($video['tooltip'])) title="{{ $video['tooltip'] }}" @endif>
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>Download</span>
                                    <span class="js-download-count rounded-md bg-white/20 px-2 py-0.5 text-xs font-semibold tabular-nums" title="Total downloads">{{ number_format($video['downloads_count'] ?? 0) }}</span>
                                </a>
                                @else
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-sky-300 bg-sky-50 px-2.5 py-1.5 text-xs font-medium text-sky-700 transition hover:bg-sky-100">
                                    Log in to download
                                </a>
                                @endauth
                            @endif
                        </div>
                        <button type="button"
                            class="video-modal-trigger mt-3 inline-flex w-full items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700"
                            data-youtube-id="{{ $video['id'] }}" aria-label="Play video {{ $video['title'] }}">
                            Play Video
                        </button>
                    </div>
                </article>
                @empty
                <div class="col-span-full rounded-xl border border-slate-200 bg-white p-8 text-center text-slate-500">
                    <p class="text-lg font-medium">{{ $cat['name'] }} content coming soon.</p>
                    <p class="mt-2">Add videos from the dashboard.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
        @else
        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center text-slate-500">
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
                        ob.classList.toggle('border-cyan-400', oid === id);
                        ob.classList.toggle('bg-sky-50', oid === id);
                        ob.classList.toggle('text-sky-700', oid === id);
                        ob.classList.toggle('border-transparent', oid !== id);
                        ob.classList.toggle('text-slate-500', oid !== id);
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
@endsection
