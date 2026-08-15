@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- Professional image slider (full visual, no left wash) --}}
<section class="site-slider relative z-10 overflow-hidden bg-slate-900" id="siteSlider" aria-label="Featured gallery">
    <div class="site-slider-track relative h-[280px] sm:h-[360px] md:h-[460px] lg:h-[520px]" id="carouselTrack">
        @forelse ($slider_slides as $index => $slide)
        <div class="carousel-slide absolute inset-0 {{ $index === 0 ? 'is-active' : '' }}" data-slide="{{ $index }}">
            <img src="{{ $slide->image_url }}" alt="Slide {{ $index + 1 }}" class="site-slide-img absolute inset-0 h-full w-full object-cover" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
        </div>
        @empty
        <div class="carousel-slide absolute inset-0 is-active" data-slide="0">
            <img src="{{ asset('images/hero.png') }}" alt="CNC showcase" class="site-slide-img absolute inset-0 h-full w-full object-cover" loading="eager">
        </div>
        @endforelse
        <div class="pointer-events-none absolute inset-x-0 bottom-0 z-[5] h-24 bg-gradient-to-t from-slate-900/55 to-transparent"></div>
    </div>

    <button type="button" id="carouselPrev" class="absolute left-3 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-white/15 text-white backdrop-blur-md transition hover:bg-white/30 md:left-6" aria-label="Previous slide">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
    </button>
    <button type="button" id="carouselNext" class="absolute right-3 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-white/15 text-white backdrop-blur-md transition hover:bg-white/30 md:right-6" aria-label="Next slide">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
    </button>

    <div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 items-center gap-3">
        <div class="flex gap-2 rounded-full bg-black/25 px-3 py-2 backdrop-blur-md" id="carouselDots"></div>
    </div>
    <div class="absolute bottom-0 left-0 z-20 h-1 w-full bg-white/15">
        <div id="carouselProgress" class="h-full w-0 bg-gradient-to-r from-sky-400 to-teal-400"></div>
    </div>
</section>

{{-- Separate hero with Three.js gear --}}
<section class="hero-panel relative z-10 overflow-hidden border-b border-cam-line">
    <div class="absolute inset-0 bg-gradient-to-br from-sky-50 via-white to-teal-50"></div>
    <div class="pointer-events-none absolute -left-16 top-10 h-56 w-56 rounded-full bg-sky-200/40 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-10 bottom-0 h-64 w-64 rounded-full bg-teal-200/35 blur-3xl"></div>

    {{-- Soft animated bg gears + blocks --}}
    <div class="hero-bg-motion pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <span class="hero-bg-block hero-bg-block-a"></span>
        <span class="hero-bg-block hero-bg-block-b"></span>
        <span class="hero-bg-block hero-bg-block-c"></span>

        <div class="hero-bg-gear hero-bg-gear-a">
            <svg viewBox="0 0 100 100" class="h-full w-full" fill="currentColor" fill-rule="evenodd">
                <path d="M84.38 43.44 L95.64 44.23 L95.93 47.52 L85.00 50.31 L84.38 56.56 L83.82 59.01 L93.62 64.61 L92.46 67.70 L81.40 65.47 L78.13 70.83 L76.56 72.79 L82.96 82.09 L80.57 84.37 L71.58 77.56 L66.31 80.97 L64.04 82.06 L65.78 93.21 L62.64 94.23 L57.48 84.19 L51.26 84.98 L48.74 84.98 L45.47 95.78 L42.19 95.33 L41.91 84.05 L35.96 82.06 L33.69 80.97 L26.05 89.28 L23.30 87.46 L27.93 77.17 L23.44 72.79 L21.87 70.83 L11.38 75.00 L9.69 72.16 L18.33 64.90 L16.18 59.01 L15.62 56.56 L4.36 55.77 L4.07 52.48 L15.00 49.69 L15.62 43.44 L16.18 40.99 L6.38 35.39 L7.54 32.30 L18.60 34.53 L21.87 29.17 L23.44 27.21 L17.04 17.91 L19.43 15.63 L28.42 22.44 L33.69 19.03 L35.96 17.94 L34.22 6.79 L37.36 5.77 L42.52 15.81 L48.74 15.02 L51.26 15.02 L54.53 4.22 L57.81 4.67 L58.09 15.95 L64.04 17.94 L66.31 19.03 L73.95 10.72 L76.70 12.54 L72.07 22.83 L76.56 27.21 L78.13 29.17 L88.62 25.00 L90.31 27.84 L81.67 35.10 L83.82 40.99 Z M63 50 A13 13 0 1 0 37 50 A13 13 0 1 0 63 50 Z"/>
                <circle cx="50" cy="50" r="22" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>
            </svg>
        </div>
        <div class="hero-bg-gear hero-bg-gear-b">
            <svg viewBox="0 0 100 100" class="h-full w-full" fill="currentColor" fill-rule="evenodd">
                <path d="M83.18 42.58 L95.51 43.28 L95.91 47.11 L84.00 50.36 L83.18 57.42 L82.44 60.17 L92.77 66.93 L91.20 70.45 L79.27 67.31 L75.03 73.01 L73.01 75.03 L78.57 86.05 L75.46 88.31 L66.69 79.62 L60.17 82.44 L57.42 83.18 L56.72 95.51 L52.89 95.91 L49.64 84.00 L42.58 83.18 L39.83 82.44 L33.07 92.77 L29.55 91.20 L32.69 79.27 L26.99 75.03 L24.97 73.01 L13.95 78.57 L11.69 75.46 L20.38 66.69 L17.56 60.17 L16.82 57.42 L4.49 56.72 L4.09 52.89 L16.00 49.64 L16.82 42.58 L17.56 39.83 L7.23 33.07 L8.80 29.55 L20.73 32.69 L24.97 26.99 L26.99 24.97 L21.43 13.95 L24.54 11.69 L33.31 20.38 L39.83 17.56 L42.58 16.82 L43.28 4.49 L47.11 4.09 L50.36 16.00 L57.42 16.82 L60.17 17.56 L66.93 7.23 L70.45 8.80 L67.31 20.73 L73.01 24.97 L75.03 26.99 L86.05 21.43 L88.31 24.54 L79.62 33.31 L82.44 39.83 Z M62 50 A12 12 0 1 0 38 50 A12 12 0 1 0 62 50 Z"/>
                <circle cx="50" cy="50" r="21" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>
            </svg>
        </div>
        <div class="hero-bg-gear hero-bg-gear-c">
            <svg viewBox="0 0 100 100" class="h-full w-full" fill="currentColor" fill-rule="evenodd">
                <path d="M85.51 44.09 L95.72 44.95 L95.95 47.83 L86.00 50.28 L85.51 55.91 L85.07 58.13 L94.17 62.83 L93.28 65.58 L83.15 64.04 L80.55 69.05 L79.29 70.93 L85.90 78.76 L84.02 80.96 L75.26 75.65 L70.93 79.29 L69.05 80.55 L72.16 90.31 L69.59 91.62 L63.51 83.37 L58.13 85.07 L55.91 85.51 L55.05 95.72 L52.17 95.95 L49.72 86.00 L44.09 85.51 L41.87 85.07 L37.17 94.17 L34.42 93.28 L35.96 83.15 L30.95 80.55 L29.07 79.29 L21.24 85.90 L19.04 84.02 L24.35 75.26 L20.71 70.93 L19.45 69.05 L9.69 72.16 L8.38 69.59 L16.63 63.51 L14.93 58.13 L14.49 55.91 L4.28 55.05 L4.05 52.17 L14.00 49.72 L14.49 44.09 L14.93 41.87 L5.83 37.17 L6.72 34.42 L16.85 35.96 L19.45 30.95 L20.71 29.07 L14.10 21.24 L15.98 19.04 L24.74 24.35 L29.07 20.71 L30.95 19.45 L27.84 9.69 L30.41 8.38 L36.49 16.63 L41.87 14.93 L44.09 14.49 L44.95 4.28 L47.83 4.05 L50.28 14.00 L55.91 14.49 L58.13 14.93 L62.83 5.83 L65.58 6.72 L64.04 16.85 L69.05 19.45 L70.93 20.71 L78.76 14.10 L80.96 15.98 L75.65 24.74 L79.29 29.07 L80.55 30.95 L90.31 27.84 L91.62 30.41 L83.37 36.49 L85.07 41.87 Z M64 50 A14 14 0 1 0 36 50 A14 14 0 1 0 64 50 Z"/>
                <circle cx="50" cy="50" r="23" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.4"/>
            </svg>
        </div>

        <span class="hero-bg-blob hero-bg-blob-a"></span>
        <span class="hero-bg-blob hero-bg-blob-b"></span>
    </div>

    <div class="relative mx-auto grid max-w-[1500px] items-center gap-8 px-4 py-12 md:grid-cols-2 md:gap-10 md:py-16 lg:py-20">
        <div class="hero-copy order-2 md:order-1">
            <p class="inline-flex items-center rounded-full border border-sky-200 bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-cam-steel shadow-sm">CNC training studio</p>
            <h1 class="font-display mt-4 text-3xl font-extrabold leading-tight text-cam-ink sm:text-4xl lg:text-5xl">
                CAM Solutions
                <span class="mt-2 block text-cam-sky">Precision machining, taught clearly.</span>
            </h1>
            <p class="mt-4 max-w-xl text-base text-slate-600 sm:text-lg">
                Master toolpaths, surface finish, and production-ready workflows with practical Mastercam lessons built for real shops.
            </p>
            <div class="mt-6 grid grid-cols-1 gap-2.5 text-sm text-slate-700 sm:grid-cols-2">
                <div class="flex items-center gap-2 rounded-xl border border-cam-line bg-white/80 px-3 py-2.5 shadow-sm"><span class="h-2 w-2 rounded-full bg-sky-500"></span>High precision focus</div>
                <div class="flex items-center gap-2 rounded-xl border border-cam-line bg-white/80 px-3 py-2.5 shadow-sm"><span class="h-2 w-2 rounded-full bg-sky-500"></span>Better surface finish</div>
                <div class="flex items-center gap-2 rounded-xl border border-cam-line bg-white/80 px-3 py-2.5 shadow-sm"><span class="h-2 w-2 rounded-full bg-teal-500"></span>Optimized toolpaths</div>
                <div class="flex items-center gap-2 rounded-xl border border-cam-line bg-white/80 px-3 py-2.5 shadow-sm"><span class="h-2 w-2 rounded-full bg-teal-500"></span>Shop-ready skills</div>
            </div>
            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="#course-library" class="cam-btn text-sm">Start Learning</a>
                <a href="{{ route('videos') }}" class="cam-btn-ghost text-sm">Explore Courses</a>
                <a href="{{ route('certificates.lookup') }}" class="text-sm font-semibold text-cam-steel hover:text-cam-sky">Find certificate →</a>
            </div>
        </div>

        <div class="order-1 md:order-2">
            <div class="hero-gear-stage relative mx-auto aspect-square w-full max-w-[400px] overflow-hidden rounded-[2rem] lg:max-w-[460px]">
                <div class="absolute inset-0 bg-gradient-to-br from-[#e8f4fb] via-white to-[#e6f7f4] shadow-[0_28px_70px_rgba(26,95,138,0.16)] ring-1 ring-sky-200/60"></div>
                <div class="pointer-events-none absolute inset-6 rounded-full bg-[radial-gradient(circle_at_30%_25%,rgba(56,189,248,0.22),transparent_45%),radial-gradient(circle_at_70%_70%,rgba(15,118,110,0.18),transparent_50%)]"></div>
                <div class="pointer-events-none absolute left-1/2 top-1/2 h-[68%] w-[68%] -translate-x-1/2 -translate-y-1/2 rounded-full border border-dashed border-sky-300/40"></div>
                <div class="absolute inset-[8%] overflow-hidden rounded-full">
                    <canvas id="heroGearCanvas" class="h-full w-full cursor-grab active:cursor-grabbing" aria-label="Interactive 3D gear assembly"></canvas>
                </div>
                <div class="pointer-events-none absolute bottom-3 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2 rounded-full border border-sky-200/80 bg-white/90 px-3 py-1.5 text-[11px] font-medium text-cam-steel shadow-sm backdrop-blur">
                    <span class="inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-teal-500"></span>
                    Move mouse or drag to rotate
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container relative z-10 mx-auto max-w-[1500px] px-4 py-8 md:py-12">
    {{-- 2. Industry tools strip --}}
    <section class="reveal mb-10 rounded-2xl border border-sky-200 bg-white p-5 shadow-sm md:p-7">
        <p class="text-center text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Industry-leading tools</p>
        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach (['Mastercam', 'NX', 'SolidWorks', 'Edgecam', 'Artcam', 'SolidCAM'] as $tool)
                <div class="rounded-xl border border-slate-200 bg-white px-3 py-4 text-center text-sm font-semibold text-slate-800 shadow-sm">
                    <span class="mx-auto mb-2 inline-flex h-9 w-9 items-center justify-center rounded-lg border border-sky-200 bg-sky-50 text-sky-600">
                        @switch($tool)
                            @case('Mastercam')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 12h16M12 4v16M6.5 6.5l11 11M17.5 6.5l-11 11" />
                                </svg>
                                @break
                            @case('NX')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 18V6l6 6-6 6zm8-12h8l-8 12h8" />
                                </svg>
                                @break
                            @case('SolidWorks')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 15c2.5-4.5 6-7 10-8 2.5 1 4 2.5 6 5-2 3-4.5 5.5-8 7-4-.2-6.5-1.8-8-4z" />
                                </svg>
                                @break
                            @case('Edgecam')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h14v14H5zM5 12h8M13 5v14" />
                                </svg>
                                @break
                            @case('Artcam')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4l7 16-7-4-7 4 7-16z" />
                                </svg>
                                @break
                            @default
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l2.4 2.6 3.5-.7.7 3.5L21 11l-2.4 2.2.7 3.5-3.5.7L12 20l-3.5-2.6-3.5.7.7-3.5L3 11l2.6-2.4.7-3.5 3.5.7L12 3z" />
                                </svg>
                        @endswitch
                    </span>
                    <span class="block">{{ $tool }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 3. Course library --}}
    <section id="course-library">
        <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Our courses</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-800 md:text-3xl">Popular Courses</h2>
            </div>
            <a href="{{ route('videos') }}" class="text-sm font-semibold text-sky-600 transition hover:text-sky-700">View all courses &rarr;</a>
        </div>
        @if (!empty($categories_with_videos))
        <div class="mb-6 flex flex-wrap items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white/90 p-2 md:mb-7" role="tablist">
            @foreach ($categories_with_videos as $index => $cat)
            <button type="button" role="tab" id="tab-{{ $cat['slug'] }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="panel-{{ $cat['slug'] }}"
                class="tab-btn rounded-xl border px-4 py-2.5 text-sm font-medium transition {{ $index === 0 ? 'border-sky-500 bg-sky-100 text-sky-800 shadow-[0_0_0_1px_rgba(34,211,238,0.18)]' : 'border-slate-200 bg-white text-slate-500 hover:border-slate-500/70 hover:bg-slate-100 hover:text-slate-700' }}">
                {{ $cat['name'] }}
            </button>
            @endforeach
        </div>

        @foreach ($categories_with_videos as $index => $cat)
        <div id="panel-{{ $cat['slug'] }}" role="tabpanel" class="tab-panel {{ $index === 0 ? '' : 'hidden' }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                @forelse ($cat['videos'] as $video)
                <article class="group w-full rounded-xl border border-slate-200 bg-white shadow-md shadow-slate-200/80 overflow-hidden hover:-translate-y-1 hover:border-sky-300 transition duration-200">
                    <button type="button" class="video-modal-trigger relative block w-full aspect-video bg-slate-200 cursor-pointer text-left border-0 p-0"
                        data-youtube-id="{{ $video['id'] }}" aria-label="Play {{ $video['title'] }}">
                        <img src="https://img.youtube.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="{{ $video['title'] }}"
                            class="w-full h-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
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
                        <div class="flex flex-wrap items-center justify-center gap-2">
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
                            data-youtube-id="{{ $video['id'] }}" aria-label="Start course {{ $video['title'] }}">
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

    {{-- 4. Precision drilling showcase (from hero image) --}}
    <section class="mt-12 overflow-hidden rounded-2xl border border-sky-200 bg-white">
        <div class="grid items-stretch gap-0 lg:grid-cols-2">
            <div class="relative min-h-[320px] lg:min-h-[420px]">
                <img src="{{ asset('images/hero.png') }}" alt="Precision drilling showcase" class="absolute inset-0 h-full w-full object-cover">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-white/90 via-white/40 to-transparent"></div>
            </div>
            <div class="p-6 md:p-8 lg:p-10">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Advanced machining workflow</p>
                <h3 class="mt-3 text-2xl font-bold leading-tight text-slate-800 md:text-3xl">Precise Drilling.<br><span class="text-sky-600">Perfect Results.</span></h3>
                <p class="mt-4 text-sm text-slate-600 md:text-base">Master high-accuracy drilling strategies, surface finish optimization, and production-ready G-code confidence with CAM-first lessons.</p>
                <div class="mt-6 space-y-3 text-sm text-slate-700">
                    <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-sky-500"></span><span>High precision machining with <strong class="font-semibold text-sky-700">&plusmn;0.01mm</strong> focus.</span></div>
                    <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-sky-500"></span><span>Toolpath optimization to reduce cycle time and improve finish quality.</span></div>
                    <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-teal-500"></span><span>Simulation-backed lessons for safer and faster real-world setup.</span></div>
                    <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-teal-500"></span><span>Practical methods aligned with modern CNC shop requirements.</span></div>
                </div>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('videos') }}" class="inline-flex items-center rounded-lg bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-500">Play Training Videos</a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg border border-sky-300 bg-white px-5 py-3 text-sm font-semibold text-sky-800 transition hover:bg-slate-100">Get Guidance</a>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. CNC programming workstation showcase --}}
    <section class="mt-10 overflow-hidden rounded-2xl border border-sky-200 bg-white">
        <div class="grid items-stretch gap-0 lg:grid-cols-2">
            <div class="order-2 p-6 md:p-8 lg:order-1 lg:p-10">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">CNC programming in action</p>
                <h3 class="mt-3 text-2xl font-bold leading-tight text-slate-800 md:text-3xl">From CAD Model to<br><span class="text-sky-600">Machine-Ready NC Code</span></h3>
                <p class="mt-4 text-sm text-slate-600 md:text-base">Learn complete CNC programming workflow on a real workstation setup: define geometry, generate toolpaths, validate simulations, and post-process reliable G-code for production.</p>
                <div class="mt-6 grid gap-3 text-sm text-slate-700 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-200 bg-white px-3 py-2">Toolpath planning and editing</div>
                    <div class="rounded-lg border border-slate-200 bg-white px-3 py-2">NC program structure and syntax</div>
                    <div class="rounded-lg border border-slate-200 bg-white px-3 py-2">Simulation and collision checks</div>
                    <div class="rounded-lg border border-slate-200 bg-white px-3 py-2">Post-processing for CNC control</div>
                </div>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('videos') }}" class="inline-flex items-center rounded-lg bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-500">Watch CNC Programming Lessons</a>
                    <a href="{{ route('models') }}" class="inline-flex items-center rounded-lg border border-sky-300 bg-white px-5 py-3 text-sm font-semibold text-sky-800 transition hover:bg-slate-100">Download Practice Models</a>
                </div>
            </div>
            <div class="relative order-1 min-h-[320px] lg:order-2 lg:min-h-[420px]">
                <img src="{{ asset('images/program.png') }}" alt="CNC programming workstation" class="absolute inset-0 h-full w-full object-cover">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-l from-white/80 via-transparent to-transparent"></div>
            </div>
        </div>
    </section>

    {{-- 6. Why choose + stats --}}
    <section class="mt-12 space-y-6 rounded-2xl border border-sky-100 bg-slate-50 p-5 md:p-7">
        <div class="text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Why choose CAM Solutions?</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-800 md:text-3xl">Learn Smarter. <span class="text-sky-600">Achieve More.</span></h2>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-sky-200 bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.31 0-6 2.24-6 5v1h12v-1c0-2.76-2.69-5-6-5z" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-sky-700">Industry-Focused Curriculum</p>
                <p class="mt-1 text-xs text-slate-500">Courses designed as per industry requirements.</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-sky-200 bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5h18v12H3V5zm0 12l5-5 4 4 3-3 6 4M8 21h8" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-sky-700">Real-World Projects</p>
                <p class="mt-1 text-xs text-slate-500">Work on real projects and case studies.</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-sky-200 bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 18h16M6 15l3-3 3 2 5-6M15 8h2v2" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-sky-700">Job-Ready Skills</p>
                <p class="mt-1 text-xs text-slate-500">Gain practical skills and get job-ready.</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-sky-200 bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3h6v3h3v6h-3v3H9v-3H6V6h3V3zm2 2v2h2V5h-2zm0 10v2h2v-2h-2zM8 8v2h2V8H8zm6 0v2h2V8h-2z" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-sky-700">AI-Powered Learning</p>
                <p class="mt-1 text-xs text-slate-500">Smart recommendations and progress tracking.</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-sky-200 bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16v10H8l-4 4V6zm5 4h6m-6 3h4" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-sky-700">Support & Guidance</p>
                <p class="mt-1 text-xs text-slate-500">WhatsApp support and career guidance.</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-sky-200 bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l2.2 2.3 3.2-.6.6 3.2L21 10l-2.2 2 .6 3.2-3.2.6L12 18l-2.2-2.2-3.2.6.6-3.2L3 10l2.2-2 .6-3.2 3.2.6L12 3zm-2 8l1.5 1.5L14 10" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-sky-700">Certification</p>
                <p class="mt-1 text-xs text-slate-500">Get recognized with completion certificate.</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                <p class="text-2xl font-bold text-sky-600">10,000+</p>
                <p class="text-xs text-slate-500">Students Trained</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                <p class="text-2xl font-bold text-sky-600">25+</p>
                <p class="text-xs text-slate-500">Countries</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                <p class="text-2xl font-bold text-sky-600">150+</p>
                <p class="text-xs text-slate-500">Courses</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                <p class="text-2xl font-bold text-sky-600">95%</p>
                <p class="text-xs text-slate-500">Placement Support</p>
            </div>
        </div>
    </section>

    {{-- 7. Final CTA --}}
    <section class="mt-10 rounded-2xl border border-sky-200 bg-gradient-to-br from-sky-100 via-white to-teal-50 px-6 py-7 md:px-10 md:py-9">
        <div class="flex flex-col items-start justify-between gap-5 md:flex-row md:items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Your future starts here</p>
                <h3 class="mt-2 text-2xl font-bold text-cam-ink">Start your CNC career today!</h3>
                <p class="mt-2 text-sm text-slate-600">Join thousands of learners mastering CAD/CAM with CAM Solutions.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-500">Join Now</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg border border-sky-300 bg-white px-5 py-3 text-sm font-semibold text-sky-800 transition hover:bg-slate-100">Chat / Contact</a>
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

@push('styles')
<style>
    .site-slider .carousel-slide {
        opacity: 0;
        visibility: hidden;
        transform: scale(1.03);
        transition: opacity 850ms cubic-bezier(0.22, 1, 0.36, 1), transform 850ms cubic-bezier(0.22, 1, 0.36, 1), visibility 850ms;
        z-index: 1;
    }
    .site-slider .carousel-slide.is-active {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
        z-index: 2;
    }
    .site-slider .carousel-slide.is-active .site-slide-img {
        animation: slideZoom 7.2s ease-out forwards;
    }
    @keyframes slideZoom {
        from { transform: scale(1); }
        to { transform: scale(1.06); }
    }
    .hero-dot {
        height: 0.5rem;
        width: 0.5rem;
        border-radius: 9999px;
        background: rgba(255,255,255,0.45);
        transition: width 280ms ease, background 280ms ease;
    }
    .hero-dot.is-active {
        width: 1.55rem;
        background: linear-gradient(90deg, #38bdf8, #2dd4bf);
    }
    .hero-copy {
        animation: heroCopyIn 750ms cubic-bezier(0.22, 1, 0.36, 1) both;
    }
    @keyframes heroCopyIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: none; }
    }

    /* Hero background motion */
    .hero-bg-block {
        position: absolute;
        border-radius: 1rem;
        border: 1px solid rgba(43, 124, 181, 0.16);
        background: linear-gradient(145deg, rgba(43, 124, 181, 0.1), rgba(15, 118, 110, 0.07));
        opacity: 0.5;
    }
    .hero-bg-block-a { width: 5rem; height: 5rem; top: 14%; left: 5%; animation: heroFloatA 14s ease-in-out infinite; }
    .hero-bg-block-b { width: 3.6rem; height: 3.6rem; bottom: 18%; left: 14%; background: linear-gradient(145deg, rgba(20, 184, 166, 0.12), rgba(43, 124, 181, 0.07)); animation: heroFloatB 16s ease-in-out infinite; }
    .hero-bg-block-c { width: 5.8rem; height: 2.8rem; top: 18%; right: 8%; animation: heroFloatC 18s ease-in-out infinite; }

    .hero-bg-gear {
        position: absolute;
        display: grid;
        place-items: center;
        transform-origin: center center;
        will-change: transform;
    }
    .hero-bg-gear svg {
        display: block;
        width: 100%;
        height: 100%;
    }
    .hero-bg-gear-a {
        width: 13rem;
        height: 13rem;
        top: -2.5rem;
        right: 18%;
        color: rgba(26, 95, 138, 0.14);
        animation: heroSpinSlow 55s linear infinite;
    }
    .hero-bg-gear-b {
        width: 9rem;
        height: 9rem;
        bottom: -1.5rem;
        left: 4%;
        color: rgba(15, 118, 110, 0.16);
        animation: heroSpinSlow 40s linear infinite reverse;
    }
    .hero-bg-gear-c {
        width: 6.5rem;
        height: 6.5rem;
        top: 58%;
        right: 42%;
        color: rgba(43, 124, 181, 0.12);
        animation: heroSpinSlow 32s linear infinite;
    }

    .hero-bg-blob {
        position: absolute;
        border-radius: 9999px;
        filter: blur(18px);
        opacity: 0.5;
    }
    .hero-bg-blob-a { width: 12rem; height: 12rem; top: 6%; left: 32%; background: rgba(56, 189, 248, 0.16); animation: heroPulse 11s ease-in-out infinite; }
    .hero-bg-blob-b { width: 10rem; height: 10rem; bottom: 8%; right: 6%; background: rgba(45, 212, 191, 0.14); animation: heroPulse 13s ease-in-out infinite reverse; }

    @keyframes heroFloatA {
        0%, 100% { transform: translate(0, 0) rotate(16deg); }
        50% { transform: translate(10px, -12px) rotate(22deg); }
    }
    @keyframes heroFloatB {
        0%, 100% { transform: translate(0, 0) rotate(-12deg); }
        50% { transform: translate(-10px, 8px) rotate(-6deg); }
    }
    @keyframes heroFloatC {
        0%, 100% { transform: translate(0, 0) rotate(8deg); }
        50% { transform: translate(8px, 10px) rotate(12deg); }
    }
    @keyframes heroSpinSlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes heroPulse {
        0%, 100% { transform: scale(1); opacity: 0.35; }
        50% { transform: scale(1.1); opacity: 0.55; }
    }

    .hero-gear-stage {
        isolation: isolate;
    }

    .reveal-item {
        opacity: 0;
        transform: translate3d(0, 22px, 0);
        transition: opacity 650ms cubic-bezier(0.16, 1, 0.3, 1), transform 650ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal-item.is-visible { opacity: 1; transform: none; }
    @media (prefers-reduced-motion: reduce) {
        .site-slider .carousel-slide,
        .site-slider .site-slide-img,
        .hero-copy,
        .reveal-item,
        .hero-bg-block,
        .hero-bg-gear,
        .hero-bg-blob {
            animation: none !important;
            transition: none !important;
            opacity: 1 !important;
            transform: none !important;
            visibility: visible !important;
        }
    }
</style>
@endpush

@push('scripts')
<script type="importmap">
{
  "imports": {
    "three": "https://cdn.jsdelivr.net/npm/three@0.170.0/build/three.module.js"
  }
}
</script>
<script type="module">
import * as THREE from 'three';

(function initHeroGear() {
    const canvas = document.getElementById('heroGearCanvas');
    if (!canvas) return;

    const COLORS = {
        steel: 0x1a5f8a,
        sky: 0x2b7cb5,
        teal: 0x0f766e,
        mint: 0x14b8a6,
        ink: 0x0f2744,
        bright: 0x7dd3fc,
        warm: 0xe0f2fe,
    };

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.setClearColor(0x000000, 0);
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.15;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(36, 1, 0.1, 100);
    camera.position.set(0, 0.1, 6.4);

    // Soft studio lighting matched to theme
    scene.add(new THREE.AmbientLight(0xffffff, 0.42));
    const key = new THREE.DirectionalLight(0xffffff, 1.35);
    key.position.set(4.5, 6.5, 5);
    scene.add(key);
    const cool = new THREE.DirectionalLight(COLORS.bright, 0.65);
    cool.position.set(-5, 2, 3);
    scene.add(cool);
    const tealFill = new THREE.PointLight(COLORS.mint, 1.1, 18);
    tealFill.position.set(2.2, -1.2, 3.5);
    scene.add(tealFill);
    const skyRim = new THREE.PointLight(COLORS.sky, 0.9, 16);
    skyRim.position.set(-2.5, 2.8, -1.5);
    scene.add(skyRim);

    // Fake environment reflections (no HDR file needed)
    const pmrem = new THREE.PMREMGenerator(renderer);
    const envScene = new THREE.Scene();
    envScene.add(new THREE.Mesh(
        new THREE.SphereGeometry(8, 32, 32),
        new THREE.MeshBasicMaterial({
            color: 0xffffff,
            side: THREE.BackSide,
            map: (() => {
                const c = document.createElement('canvas');
                c.width = 256; c.height = 128;
                const g = c.getContext('2d');
                const grd = g.createLinearGradient(0, 0, 0, 128);
                grd.addColorStop(0, '#dbeafe');
                grd.addColorStop(0.45, '#f8fafc');
                grd.addColorStop(0.7, '#ccfbf1');
                grd.addColorStop(1, '#0f766e');
                g.fillStyle = grd;
                g.fillRect(0, 0, 256, 128);
                g.fillStyle = 'rgba(43,124,181,0.35)';
                g.beginPath(); g.arc(70, 40, 28, 0, Math.PI * 2); g.fill();
                g.fillStyle = 'rgba(255,255,255,0.55)';
                g.beginPath(); g.arc(180, 30, 18, 0, Math.PI * 2); g.fill();
                const tex = new THREE.CanvasTexture(c);
                tex.mapping = THREE.EquirectangularReflectionMapping;
                tex.colorSpace = THREE.SRGBColorSpace;
                return tex;
            })(),
        })
    ));
    const envMap = pmrem.fromScene(envScene, 0.04).texture;
    scene.environment = envMap;
    pmrem.dispose();

    function createGearShape(teeth, pitchR, holeR, spokeCount) {
        // Spur-gear profile with many fine teeth (module-based addendum/dedendum)
        const module = (2 * pitchR) / teeth;
        const addendum = module * 1.0;
        const dedendum = module * 1.25;
        const outerR = pitchR + addendum;
        const rootR = Math.max(pitchR - dedendum, holeR * 1.15);
        const shape = new THREE.Shape();
        const step = (Math.PI * 2) / teeth;

        for (let i = 0; i < teeth; i++) {
            const base = i * step;
            const rootHalf = step * 0.28;
            const tipHalf = step * 0.15;
            const samples = [
                [base - rootHalf, rootR],
                [base - rootHalf * 0.62, rootR + (pitchR - rootR) * 0.28],
                [base - tipHalf * 1.2, pitchR],
                [base - tipHalf, outerR],
                [base - tipHalf * 0.3, outerR],
                [base + tipHalf * 0.3, outerR],
                [base + tipHalf, outerR],
                [base + tipHalf * 1.2, pitchR],
                [base + rootHalf * 0.62, rootR + (pitchR - rootR) * 0.28],
                [base + rootHalf, rootR],
            ];
            samples.forEach(([ang, r], idx) => {
                const x = Math.cos(ang) * r;
                const y = Math.sin(ang) * r;
                if (i === 0 && idx === 0) shape.moveTo(x, y);
                else shape.lineTo(x, y);
            });
        }
        shape.closePath();

        const hole = new THREE.Path();
        hole.absarc(0, 0, holeR, 0, Math.PI * 2, true);
        shape.holes.push(hole);

        if (spokeCount > 0) {
            for (let s = 0; s < spokeCount; s++) {
                const ang = (s / spokeCount) * Math.PI * 2 + Math.PI / spokeCount;
                const dist = (pitchR + holeR) * 0.48;
                const cx = Math.cos(ang) * dist;
                const cy = Math.sin(ang) * dist;
                const spoke = new THREE.Path();
                const rw = pitchR * 0.12;
                const rh = pitchR * 0.085;
                for (let k = 0; k <= 20; k++) {
                    const t = (k / 20) * Math.PI * 2;
                    const lx = Math.cos(t) * rw;
                    const ly = Math.sin(t) * rh;
                    const px = cx + lx * Math.cos(ang) - ly * Math.sin(ang);
                    const py = cy + lx * Math.sin(ang) + ly * Math.cos(ang);
                    if (k === 0) spoke.moveTo(px, py);
                    else spoke.lineTo(px, py);
                }
                spoke.closePath();
                shape.holes.push(spoke);
            }
        }
        return { shape, outerR, pitchR };
    }

    function makeGearMesh({ teeth, pitchR, holeR, depth, color, spokeCount, metalness, roughness }) {
        const { shape, outerR } = createGearShape(teeth, pitchR, holeR, spokeCount);
        const geo = new THREE.ExtrudeGeometry(shape, {
            depth,
            bevelEnabled: true,
            bevelThickness: Math.min(depth * 0.07, 0.024),
            bevelSize: Math.min(depth * 0.05, 0.016),
            bevelSegments: 3,
            curveSegments: 28,
        });
        geo.center();
        geo.computeVertexNormals();

        const mat = new THREE.MeshPhysicalMaterial({
            color,
            metalness: metalness ?? 0.85,
            roughness: roughness ?? 0.22,
            clearcoat: 0.55,
            clearcoatRoughness: 0.25,
            reflectivity: 0.9,
            envMapIntensity: 1.15,
        });
        const mesh = new THREE.Mesh(geo, mat);
        mesh.userData.teeth = teeth;
        mesh.userData.pitchR = pitchR;
        mesh.userData.outerR = outerR;

        // Hub flange
        const flange = new THREE.Mesh(
            new THREE.CylinderGeometry(holeR * 1.35, holeR * 1.45, depth * 1.15, 48),
            new THREE.MeshPhysicalMaterial({
                color: COLORS.teal,
                metalness: 0.9,
                roughness: 0.18,
                clearcoat: 0.4,
                envMapIntensity: 1.1,
            })
        );
        flange.rotation.x = Math.PI / 2;
        mesh.add(flange);

        // Center pin
        const pin = new THREE.Mesh(
            new THREE.CylinderGeometry(holeR * 0.55, holeR * 0.55, depth * 1.55, 32),
            new THREE.MeshPhysicalMaterial({
                color: COLORS.ink,
                metalness: 0.95,
                roughness: 0.15,
            })
        );
        pin.rotation.x = Math.PI / 2;
        mesh.add(pin);

        // Bolts around hub
        const boltMat = new THREE.MeshPhysicalMaterial({
            color: 0xcbd5e1,
            metalness: 0.95,
            roughness: 0.18,
        });
        for (let i = 0; i < 6; i++) {
            const a = (i / 6) * Math.PI * 2;
            const bolt = new THREE.Mesh(new THREE.CylinderGeometry(holeR * 0.12, holeR * 0.12, depth * 1.25, 10), boltMat);
            bolt.rotation.x = Math.PI / 2;
            bolt.position.set(Math.cos(a) * holeR * 0.95, Math.sin(a) * holeR * 0.95, 0);
            mesh.add(bolt);
            const head = new THREE.Mesh(new THREE.CylinderGeometry(holeR * 0.18, holeR * 0.18, depth * 0.22, 6), boltMat);
            head.rotation.x = Math.PI / 2;
            head.position.set(Math.cos(a) * holeR * 0.95, Math.sin(a) * holeR * 0.95, depth * 0.55);
            mesh.add(head);
        }

        // Accent face ring
        const faceRing = new THREE.Mesh(
            new THREE.TorusGeometry(pitchR * 0.72, depth * 0.06, 12, 100),
            new THREE.MeshPhysicalMaterial({
                color: COLORS.sky,
                metalness: 0.7,
                roughness: 0.25,
                transparent: true,
                opacity: 0.85,
                emissive: COLORS.sky,
                emissiveIntensity: 0.08,
            })
        );
        faceRing.position.z = depth * 0.52;
        mesh.add(faceRing);

        return mesh;
    }

    const assembly = new THREE.Group();
    scene.add(assembly);

    const MAIN_TEETH = 48;
    const SMALL_A_TEETH = 24;
    const SMALL_B_TEETH = 30;
    const mainPitch = 0.98;
    const smallAPitch = mainPitch * (SMALL_A_TEETH / MAIN_TEETH);
    const smallBPitch = mainPitch * (SMALL_B_TEETH / MAIN_TEETH);

    const main = makeGearMesh({
        teeth: MAIN_TEETH, pitchR: mainPitch, holeR: 0.28, depth: 0.3,
        color: COLORS.steel, spokeCount: 6, metalness: 0.88, roughness: 0.2,
    });
    assembly.add(main);

    const smallA = makeGearMesh({
        teeth: SMALL_A_TEETH, pitchR: smallAPitch, holeR: 0.13, depth: 0.24,
        color: COLORS.teal, spokeCount: 0, metalness: 0.82, roughness: 0.24,
    });
    const distA = mainPitch + smallAPitch;
    smallA.position.set(distA * 0.78, distA * 0.34, 0.08);
    assembly.add(smallA);

    const smallB = makeGearMesh({
        teeth: SMALL_B_TEETH, pitchR: smallBPitch, holeR: 0.15, depth: 0.26,
        color: COLORS.sky, spokeCount: 5, metalness: 0.84, roughness: 0.22,
    });
    const distB = mainPitch + smallBPitch;
    smallB.position.set(-distB * 0.7, -distB * 0.4, -0.05);
    assembly.add(smallB);

    // Keep the whole assembly inside the circular stage
    assembly.scale.setScalar(0.78);

    // Orbit rings
    const ringGroup = new THREE.Group();
    scene.add(ringGroup);
    const orbitMat = new THREE.MeshPhysicalMaterial({
        color: COLORS.sky,
        metalness: 0.55,
        roughness: 0.3,
        transparent: true,
        opacity: 0.45,
        emissive: COLORS.sky,
        emissiveIntensity: 0.12,
    });
    ringGroup.scale.setScalar(0.78);
    const orbit1 = new THREE.Mesh(new THREE.TorusGeometry(1.7, 0.014, 12, 120), orbitMat);
    orbit1.rotation.x = Math.PI / 2.4;
    ringGroup.add(orbit1);
    const orbit2 = new THREE.Mesh(
        new THREE.TorusGeometry(1.88, 0.012, 12, 120),
        new THREE.MeshPhysicalMaterial({
            color: COLORS.mint,
            metalness: 0.5,
            roughness: 0.35,
            transparent: true,
            opacity: 0.35,
            emissive: COLORS.mint,
            emissiveIntensity: 0.1,
        })
    );
    orbit2.rotation.x = Math.PI / 1.7;
    orbit2.rotation.y = 0.4;
    ringGroup.add(orbit2);

    // Floating spark particles
    const sparkCount = 48;
    const sparkGeo = new THREE.BufferGeometry();
    const sparkPos = new Float32Array(sparkCount * 3);
    for (let i = 0; i < sparkCount; i++) {
        const r = 1.05 + Math.random() * 0.85;
        const a = Math.random() * Math.PI * 2;
        const y = (Math.random() - 0.5) * 1.5;
        sparkPos[i * 3] = Math.cos(a) * r;
        sparkPos[i * 3 + 1] = y;
        sparkPos[i * 3 + 2] = Math.sin(a) * r * 0.45;
    }
    sparkGeo.setAttribute('position', new THREE.BufferAttribute(sparkPos, 3));
    const sparks = new THREE.Points(
        sparkGeo,
        new THREE.PointsMaterial({
            color: COLORS.bright,
            size: 0.045,
            transparent: true,
            opacity: 0.85,
            depthWrite: false,
            sizeAttenuation: true,
        })
    );
    scene.add(sparks);

    // Soft ground shadow disc
    const shadow = new THREE.Mesh(
        new THREE.CircleGeometry(1.45, 64),
        new THREE.MeshBasicMaterial({ color: 0x0f2744, transparent: true, opacity: 0.07 })
    );
    shadow.rotation.x = -Math.PI / 2;
    shadow.position.y = -1.35;
    scene.add(shadow);

    const target = { x: 0.42, y: 0.35 };
    const current = { x: 0.42, y: 0.35 };
    let dragging = false;
    let lastX = 0;
    let lastY = 0;
    let spinY = 0;

    function onPointer(clientX, clientY) {
        const rect = canvas.getBoundingClientRect();
        const nx = ((clientX - rect.left) / rect.width) * 2 - 1;
        const ny = ((clientY - rect.top) / rect.height) * 2 - 1;
        target.y = nx * 1.35;
        target.x = ny * 0.95;
    }

    canvas.addEventListener('pointermove', (e) => {
        if (dragging) {
            const dx = e.clientX - lastX;
            const dy = e.clientY - lastY;
            lastX = e.clientX;
            lastY = e.clientY;
            target.y += dx * 0.012;
            target.x += dy * 0.01;
            spinY += dx * 0.012;
        } else {
            onPointer(e.clientX, e.clientY);
        }
    });
    canvas.addEventListener('pointerdown', (e) => {
        dragging = true;
        lastX = e.clientX;
        lastY = e.clientY;
        canvas.setPointerCapture(e.pointerId);
    });
    const endDrag = () => { dragging = false; };
    canvas.addEventListener('pointerup', endDrag);
    canvas.addEventListener('pointercancel', endDrag);
    canvas.addEventListener('pointerleave', endDrag);

    function resize() {
        const w = canvas.clientWidth || canvas.parentElement.clientWidth;
        const h = canvas.clientHeight || canvas.parentElement.clientHeight;
        if (!w || !h) return;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }
    resize();
    window.addEventListener('resize', resize);

    const ratioA = MAIN_TEETH / SMALL_A_TEETH;
    const ratioB = MAIN_TEETH / SMALL_B_TEETH;
    const clock = new THREE.Clock();

    function animate() {
        const t = clock.getElapsedTime();
        current.x += (target.x - current.x) * 0.075;
        current.y += (target.y - current.y) * 0.075;

        const baseSpin = spinY + t * 0.35;
        assembly.rotation.x = current.x * 0.35;
        assembly.rotation.y = current.y * 0.28;

        main.rotation.z = baseSpin;
        smallA.rotation.z = -baseSpin * ratioA;
        smallB.rotation.z = -baseSpin * ratioB;

        ringGroup.rotation.y = t * 0.18;
        ringGroup.rotation.z = Math.sin(t * 0.35) * 0.12;
        orbit1.rotation.z = t * 0.25;
        orbit2.rotation.z = -t * 0.18;

        sparks.rotation.y = t * 0.12;
        const pos = sparks.geometry.attributes.position.array;
        for (let i = 0; i < sparkCount; i++) {
            pos[i * 3 + 1] += Math.sin(t * 1.4 + i) * 0.0018;
        }
        sparks.geometry.attributes.position.needsUpdate = true;

        tealFill.intensity = 0.95 + Math.sin(t * 1.6) * 0.2;
        skyRim.intensity = 0.75 + Math.cos(t * 1.2) * 0.18;

        renderer.render(scene, camera);
        requestAnimationFrame(animate);
    }
    animate();
})();
</script>
<script>
    (function() {
        document.querySelectorAll('.container > section').forEach(function(el, i) {
            el.classList.add('reveal-item');
            el.style.transitionDelay = Math.min(i * 60, 360) + 'ms';
        });
        var revealObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
        document.querySelectorAll('.reveal-item').forEach(function(el) { revealObserver.observe(el); });

        var slides = Array.from(document.querySelectorAll('#carouselTrack .carousel-slide'));
        var prev = document.getElementById('carouselPrev');
        var next = document.getElementById('carouselNext');
        var dotsEl = document.getElementById('carouselDots');
        var progress = document.getElementById('carouselProgress');
        var total = slides.length;
        var idx = 0;
        var duration = 7000;
        var startedAt = Date.now();

        function setDots() {
            if (!dotsEl) return;
            dotsEl.querySelectorAll('button').forEach(function(d, i) {
                d.classList.toggle('is-active', i === idx);
                d.setAttribute('aria-current', i === idx ? 'true' : 'false');
            });
        }

        function go(n) {
            if (total < 1) return;
            idx = (n + total) % total;
            slides.forEach(function(slide, i) {
                slide.classList.toggle('is-active', i === idx);
            });
            setDots();
            startedAt = Date.now();
            if (progress) progress.style.width = '0%';
        }

        function tick() {
            var elapsed = Date.now() - startedAt;
            var pct = Math.min(100, (elapsed / duration) * 100);
            if (progress) progress.style.width = pct + '%';
            if (elapsed >= duration) go(idx + 1);
            window.requestAnimationFrame(tick);
        }

        if (total > 0 && dotsEl) {
            for (var i = 0; i < total; i++) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'hero-dot' + (i === 0 ? ' is-active' : '');
                b.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                b.setAttribute('aria-current', i === 0 ? 'true' : 'false');
                (function(n) { b.addEventListener('click', function() { go(n); }); })(i);
                dotsEl.appendChild(b);
            }
            if (prev) prev.addEventListener('click', function() { go(idx - 1); });
            if (next) next.addEventListener('click', function() { go(idx + 1); });
            if (total > 1) window.requestAnimationFrame(tick);
            else if (progress) progress.style.width = '100%';
        }

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
                        ob.classList.toggle('border-sky-500', oid === id);
                        ob.classList.toggle('bg-sky-100', oid === id);
                        ob.classList.toggle('text-sky-800', oid === id);
                        ob.classList.toggle('border-slate-200', oid !== id);
                        ob.classList.toggle('bg-white', oid !== id);
                        ob.classList.toggle('text-slate-500', oid !== id);
                    }
                    if (op) op.classList.toggle('hidden', oid !== id);
                });
            });
        });

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
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) closeVideoModal();
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
                    try { document.execCommand('copy'); feedback(true); } catch (err) { feedback(false); }
                    document.body.removeChild(ta);
                }
            });
        });
    })();
</script>
@endpush
@endsection
