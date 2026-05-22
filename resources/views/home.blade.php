@extends('layouts.app')

@section('title', 'Home')

@section('content')
<canvas id="homeParticleNetwork" class="pointer-events-none fixed inset-0 z-0 opacity-60"></canvas>

{{-- 1. Hero carousel + content --}}
<section class="banner-carousel relative z-10 h-[520px] md:h-[620px] overflow-hidden border-b border-cyan-400/15 bg-slate-950">
    <div class="carousel-track flex h-full transition-transform duration-500 ease-out" id="carouselTrack">
        @forelse ($slider_slides as $slide)
        <div class="carousel-slide flex-shrink-0 w-full h-full min-h-full relative flex items-center justify-center bg-slate-950">
            <img src="{{ $slide->image_url }}" alt="Slide" class="absolute inset-0 w-full h-full object-cover opacity-100" loading="lazy">
        </div>
        @empty
        <div class="carousel-slide flex-shrink-0 w-full h-full min-h-full relative flex items-center justify-center bg-slate-950">
            <img src="{{ asset('images/hero.png') }}" alt="CNC hero" class="absolute inset-0 w-full h-full object-cover opacity-100" loading="eager">
        </div>
        @endforelse
    </div>
    <div class="relative z-10 mx-auto flex h-full max-w-[1500px] items-center px-4">
        <div class="max-w-xl">
            <span class="inline-flex items-center rounded-full border border-cyan-400/30 bg-cyan-500/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-cyan-200">Precision machining training</span>
            <h1 class="mt-5 text-3xl font-bold leading-tight text-white sm:text-4xl md:text-5xl">Precise Drilling.<br><span class="text-cyan-300">Perfect Results.</span></h1>
            <p class="mt-4 max-w-lg text-sm text-slate-200 sm:text-base">High accuracy, better surface finish, and optimized toolpaths. Build industry-ready CNC skills with practical CAD/CAM workflows.</p>
            <div class="mt-5 grid grid-cols-1 gap-2 text-sm text-slate-200 sm:grid-cols-2">
                <div class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-cyan-300"></span>High precision</div>
                <div class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-cyan-300"></span>Better surface finish</div>
                <div class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-cyan-300"></span>Optimized toolpath</div>
                <div class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-cyan-300"></span>Improved productivity</div>
            </div>
            <div class="mt-7 flex flex-wrap items-center gap-3">
                <a href="#course-library" class="inline-flex items-center gap-2 rounded-lg bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.868v4.264a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    </svg>
                    Start Learning
                </a>
                <a href="{{ route('videos') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-400/40 bg-slate-900/70 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:border-cyan-300/60 hover:text-cyan-100">
                    Explore Courses
                </a>
            </div>
            <div class="mt-5 flex items-center gap-4 text-xs text-slate-300 sm:text-sm">
                <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>&plusmn;0.01mm accuracy focus</span>
                <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-cyan-400"> </span>Real machining simulations</span>
            </div>
        </div>
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

<div class="container relative z-10 mx-auto max-w-[1500px] px-4 py-8 md:py-12">
    {{-- 2. Industry tools strip --}}
    <section class="mb-10 rounded-2xl border border-cyan-400/20 bg-slate-900/60 p-5 backdrop-blur-sm md:p-7">
        <p class="text-center text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300/80">Industry-leading tools</p>
        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach (['Mastercam', 'NX', 'SolidWorks', 'Edgecam', 'Artcam', 'SolidCAM'] as $tool)
                <div class="rounded-xl border border-slate-700/70 bg-slate-950/70 px-3 py-4 text-center text-sm font-semibold text-slate-100 shadow-inner shadow-cyan-500/10">
                    <span class="mx-auto mb-2 inline-flex h-9 w-9 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
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
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300/80">Our courses</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-100 md:text-3xl">Popular Courses</h2>
            </div>
            <a href="{{ route('videos') }}" class="text-sm font-semibold text-cyan-300 transition hover:text-cyan-200">View all courses &rarr;</a>
        </div>
        @if (!empty($categories_with_videos))
        <div class="mb-6 flex flex-wrap items-center justify-center gap-2 rounded-2xl border border-slate-700/70 bg-slate-900/60 p-2 md:mb-7" role="tablist">
            @foreach ($categories_with_videos as $index => $cat)
            <button type="button" role="tab" id="tab-{{ $cat['slug'] }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="panel-{{ $cat['slug'] }}"
                class="tab-btn rounded-xl border px-4 py-2.5 text-sm font-medium transition {{ $index === 0 ? 'border-cyan-400/70 bg-cyan-500/20 text-cyan-100 shadow-[0_0_0_1px_rgba(34,211,238,0.18)]' : 'border-slate-700/80 bg-slate-900/70 text-slate-400 hover:border-slate-500/70 hover:bg-slate-800/80 hover:text-slate-200' }}">
                {{ $cat['name'] }}
            </button>
            @endforeach
        </div>

        @foreach ($categories_with_videos as $index => $cat)
        <div id="panel-{{ $cat['slug'] }}" role="tabpanel" class="tab-panel {{ $index === 0 ? '' : 'hidden' }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                @forelse ($cat['videos'] as $video)
                <article class="group w-full rounded-xl border border-slate-600/70 bg-gradient-to-b from-slate-800/95 to-slate-900/95 shadow-[0_10px_24px_rgba(2,6,23,0.55)] overflow-hidden hover:-translate-y-1 hover:border-cyan-400/50 transition duration-200">
                    <button type="button" class="video-modal-trigger relative block w-full aspect-video bg-slate-200 cursor-pointer text-left border-0 p-0"
                        data-youtube-id="{{ $video['id'] }}" aria-label="Play {{ $video['title'] }}">
                        <img src="https://img.youtube.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="{{ $video['title'] }}"
                            class="w-full h-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                        @php
                            $badge_palette = ['bg-emerald-500/90 text-emerald-50', 'bg-amber-500/90 text-amber-50', 'bg-violet-500/90 text-violet-50', 'bg-rose-500/90 text-rose-50'];
                            $badge_labels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
                            $badge_index = ($loop->iteration - 1) % 4;
                        @endphp
                        <span class="pointer-events-none absolute left-2 top-2 inline-flex items-center rounded-md px-2 py-1 text-[10px] font-semibold uppercase tracking-wide {{ $badge_palette[$badge_index] }}">{{ $badge_labels[$badge_index] }}</span>
                    </button>
                    <div class="p-3">
                        <h3 class="mb-2 min-h-[3rem] line-clamp-2 text-base font-semibold leading-snug text-slate-100">{{ $video['title'] }}</h3>
                        <div class="mb-3 flex items-center gap-4 text-xs text-slate-300">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8zm0 0V5m0 10v4m7-7h-4M5 12H1" />
                                </svg>
                                {{ 10 + ($loop->iteration % 12) }} Lessons
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ number_format(3 + (($loop->iteration % 8) * 0.5), 1) }} Hours
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center justify-center gap-2">
                            <button type="button"
                                class="js-copy-video-link inline-flex items-center gap-1 rounded-lg border border-slate-600 bg-slate-800 px-2.5 py-1.5 text-xs font-medium text-slate-200 transition hover:border-cyan-400/60 hover:text-cyan-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-1 focus:ring-offset-slate-900"
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
                                    class="js-model-download inline-flex items-center gap-1 rounded-lg border border-cyan-300/20 bg-cyan-500 px-2.5 py-1.5 text-xs font-semibold text-slate-950 transition hover:bg-cyan-400"
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
                                    class="inline-flex items-center gap-1 rounded-lg border border-cyan-500/40 bg-cyan-500/10 px-2.5 py-1.5 text-xs font-medium text-cyan-200 transition hover:bg-cyan-500/20">
                                    Log in to download
                                </a>
                                @endauth
                            @endif
                        </div>
                        <button type="button"
                            class="video-modal-trigger mt-3 inline-flex w-full items-center justify-center rounded-lg bg-[#2563eb] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1d4ed8]"
                            data-youtube-id="{{ $video['id'] }}" aria-label="Start course {{ $video['title'] }}">
                            Play Video
                        </button>
                    </div>
                </article>
                @empty
                <div class="col-span-full rounded-xl border border-slate-700/70 bg-slate-900/70 p-8 text-center text-slate-400">
                    <p class="text-lg font-medium">{{ $cat['name'] }} content coming soon.</p>
                    <p class="mt-2">Add videos from the dashboard.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
        @else
        <div class="rounded-xl border border-slate-700/70 bg-slate-900/70 p-8 text-center text-slate-400">
            <p class="text-lg font-medium">No categories yet.</p>
            <p class="mt-2">Add categories and videos from the dashboard.</p>
        </div>
        @endif
    </section>

    {{-- 4. Precision drilling showcase (from hero image) --}}
    <section class="mt-12 overflow-hidden rounded-2xl border border-cyan-400/20 bg-slate-950/70">
        <div class="grid items-stretch gap-0 lg:grid-cols-2">
            <div class="relative min-h-[320px] lg:min-h-[420px]">
                <img src="{{ asset('images/hero.png') }}" alt="Precision drilling showcase" class="absolute inset-0 h-full w-full object-cover">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/35 to-transparent"></div>
            </div>
            <div class="p-6 md:p-8 lg:p-10">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-300/80">Advanced machining workflow</p>
                <h3 class="mt-3 text-2xl font-bold leading-tight text-slate-100 md:text-3xl">Precise Drilling.<br><span class="text-cyan-300">Perfect Results.</span></h3>
                <p class="mt-4 text-sm text-slate-300 md:text-base">Master high-accuracy drilling strategies, surface finish optimization, and production-ready G-code confidence with CAM-first lessons.</p>
                <div class="mt-6 space-y-3 text-sm text-slate-200">
                    <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-cyan-300"></span><span>High precision machining with <strong class="font-semibold text-cyan-200">&plusmn;0.01mm</strong> focus.</span></div>
                    <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-cyan-300"></span><span>Toolpath optimization to reduce cycle time and improve finish quality.</span></div>
                    <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-cyan-300"></span><span>Simulation-backed lessons for safer and faster real-world setup.</span></div>
                    <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-cyan-300"></span><span>Practical methods aligned with modern CNC shop requirements.</span></div>
                </div>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('videos') }}" class="inline-flex items-center rounded-lg bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">Play Training Videos</a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg border border-cyan-400/40 bg-slate-900/70 px-5 py-3 text-sm font-semibold text-cyan-100 transition hover:bg-slate-800">Get Guidance</a>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. CNC programming workstation showcase --}}
    <section class="mt-10 overflow-hidden rounded-2xl border border-cyan-400/20 bg-slate-950/70">
        <div class="grid items-stretch gap-0 lg:grid-cols-2">
            <div class="order-2 p-6 md:p-8 lg:order-1 lg:p-10">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-300/80">CNC programming in action</p>
                <h3 class="mt-3 text-2xl font-bold leading-tight text-slate-100 md:text-3xl">From CAD Model to<br><span class="text-cyan-300">Machine-Ready NC Code</span></h3>
                <p class="mt-4 text-sm text-slate-300 md:text-base">Learn complete CNC programming workflow on a real workstation setup: define geometry, generate toolpaths, validate simulations, and post-process reliable G-code for production.</p>
                <div class="mt-6 grid gap-3 text-sm text-slate-200 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-700/70 bg-slate-900/70 px-3 py-2">Toolpath planning and editing</div>
                    <div class="rounded-lg border border-slate-700/70 bg-slate-900/70 px-3 py-2">NC program structure and syntax</div>
                    <div class="rounded-lg border border-slate-700/70 bg-slate-900/70 px-3 py-2">Simulation and collision checks</div>
                    <div class="rounded-lg border border-slate-700/70 bg-slate-900/70 px-3 py-2">Post-processing for CNC control</div>
                </div>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('videos') }}" class="inline-flex items-center rounded-lg bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">Watch CNC Programming Lessons</a>
                    <a href="{{ route('models') }}" class="inline-flex items-center rounded-lg border border-cyan-400/40 bg-slate-900/70 px-5 py-3 text-sm font-semibold text-cyan-100 transition hover:bg-slate-800">Download Practice Models</a>
                </div>
            </div>
            <div class="relative order-1 min-h-[320px] lg:order-2 lg:min-h-[420px]">
                <img src="{{ asset('images/program.png') }}" alt="CNC programming workstation" class="absolute inset-0 h-full w-full object-cover">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-l from-slate-950/70 via-transparent to-transparent"></div>
            </div>
        </div>
    </section>

    {{-- 6. Why choose + stats --}}
    <section class="mt-12 space-y-6 rounded-2xl border border-cyan-400/15 bg-slate-950/55 p-5 md:p-7">
        <div class="text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300/80">Why choose CAM Solutions?</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-100 md:text-3xl">Learn Smarter. <span class="text-cyan-300">Achieve More.</span></h2>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/75 p-4 text-center shadow-inner shadow-cyan-500/10">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.31 0-6 2.24-6 5v1h12v-1c0-2.76-2.69-5-6-5z" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-cyan-200">Industry-Focused Curriculum</p>
                <p class="mt-1 text-xs text-slate-400">Courses designed as per industry requirements.</p>
            </div>
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/75 p-4 text-center shadow-inner shadow-cyan-500/10">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5h18v12H3V5zm0 12l5-5 4 4 3-3 6 4M8 21h8" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-cyan-200">Real-World Projects</p>
                <p class="mt-1 text-xs text-slate-400">Work on real projects and case studies.</p>
            </div>
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/75 p-4 text-center shadow-inner shadow-cyan-500/10">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 18h16M6 15l3-3 3 2 5-6M15 8h2v2" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-cyan-200">Job-Ready Skills</p>
                <p class="mt-1 text-xs text-slate-400">Gain practical skills and get job-ready.</p>
            </div>
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/75 p-4 text-center shadow-inner shadow-cyan-500/10">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3h6v3h3v6h-3v3H9v-3H6V6h3V3zm2 2v2h2V5h-2zm0 10v2h2v-2h-2zM8 8v2h2V8H8zm6 0v2h2V8h-2z" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-cyan-200">AI-Powered Learning</p>
                <p class="mt-1 text-xs text-slate-400">Smart recommendations and progress tracking.</p>
            </div>
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/75 p-4 text-center shadow-inner shadow-cyan-500/10">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16v10H8l-4 4V6zm5 4h6m-6 3h4" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-cyan-200">Support & Guidance</p>
                <p class="mt-1 text-xs text-slate-400">WhatsApp support and career guidance.</p>
            </div>
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/75 p-4 text-center shadow-inner shadow-cyan-500/10">
                <span class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l2.2 2.3 3.2-.6.6 3.2L21 10l-2.2 2 .6 3.2-3.2.6L12 18l-2.2-2.2-3.2.6.6-3.2L3 10l2.2-2 .6-3.2 3.2.6L12 3zm-2 8l1.5 1.5L14 10" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-cyan-200">Certification</p>
                <p class="mt-1 text-xs text-slate-400">Get recognized with completion certificate.</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/80 p-4 text-center">
                <p class="text-2xl font-bold text-cyan-300">10,000+</p>
                <p class="text-xs text-slate-400">Students Trained</p>
            </div>
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/80 p-4 text-center">
                <p class="text-2xl font-bold text-cyan-300">25+</p>
                <p class="text-xs text-slate-400">Countries</p>
            </div>
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/80 p-4 text-center">
                <p class="text-2xl font-bold text-cyan-300">150+</p>
                <p class="text-xs text-slate-400">Courses</p>
            </div>
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/80 p-4 text-center">
                <p class="text-2xl font-bold text-cyan-300">95%</p>
                <p class="text-xs text-slate-400">Placement Support</p>
            </div>
        </div>
    </section>

    {{-- 7. Final CTA --}}
    <section class="mt-10 rounded-2xl border border-cyan-400/20 bg-[radial-gradient(circle_at_15%_20%,rgba(34,211,238,0.2),transparent_35%),linear-gradient(120deg,rgba(15,23,42,0.95),rgba(2,6,23,0.95))] px-6 py-7 md:px-10 md:py-9">
        <div class="flex flex-col items-start justify-between gap-5 md:flex-row md:items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300/80">Your future starts here</p>
                <h3 class="mt-2 text-2xl font-bold text-white">Start your CNC career today!</h3>
                <p class="mt-2 text-sm text-slate-300">Join thousands of learners mastering CAD/CAM with CAM Solutions.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">Join Now</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg border border-cyan-400/40 bg-slate-900/70 px-5 py-3 text-sm font-semibold text-cyan-100 transition hover:bg-slate-800">Chat / Contact</a>
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
    .reveal-item {
        opacity: 0;
        transform: translate3d(0, 26px, 0) scale(0.985);
        transition: opacity 700ms cubic-bezier(0.16, 1, 0.3, 1), transform 700ms cubic-bezier(0.16, 1, 0.3, 1);
        will-change: transform, opacity;
    }
    .reveal-item.from-left { transform: translate3d(-42px, 0, 0) scale(0.985); }
    .reveal-item.from-right { transform: translate3d(42px, 0, 0) scale(0.985); }
    .reveal-item.from-up { transform: translate3d(0, 32px, 0) scale(0.985); }
    .reveal-item.is-visible {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
    }
    @media (prefers-reduced-motion: reduce) {
        .reveal-item {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    (function() {
        // --- Section reveal animation ---
        var revealTargets = [
            '.banner-carousel > .relative.z-10',
            '.banner-carousel #carouselPrev',
            '.banner-carousel #carouselNext',
            '.banner-carousel #carouselDots',
            '.container > section'
        ];
        var revealNodes = [];
        revealTargets.forEach(function(sel) {
            document.querySelectorAll(sel).forEach(function(el) {
                if (!revealNodes.includes(el)) revealNodes.push(el);
            });
        });
        revealNodes.forEach(function(el, i) {
            el.classList.add('reveal-item');
            if (i % 3 === 0) el.classList.add('from-left');
            else if (i % 3 === 1) el.classList.add('from-right');
            else el.classList.add('from-up');
            el.style.transitionDelay = Math.min(i * 70, 450) + 'ms';
        });
        var revealObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });
        revealNodes.forEach(function(el) { revealObserver.observe(el); });

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
            }, 10000);
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
                        ob.classList.toggle('border-cyan-400/70', oid === id);
                        ob.classList.toggle('bg-cyan-500/20', oid === id);
                        ob.classList.toggle('text-cyan-100', oid === id);
                        ob.classList.toggle('shadow-[0_0_0_1px_rgba(34,211,238,0.18)]', oid === id);
                        ob.classList.toggle('border-slate-700/80', oid !== id);
                        ob.classList.toggle('bg-slate-900/70', oid !== id);
                        ob.classList.toggle('text-slate-400', oid !== id);
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

        // --- Home particle network background ---
        var particleCanvas = document.getElementById('homeParticleNetwork');
        if (particleCanvas) {
            var ctx = particleCanvas.getContext('2d');
            if (ctx) {
                var particles = [];
                var particleCount = window.innerWidth >= 1536 ? 120 : (window.innerWidth >= 1024 ? 92 : 56);
                var connectDistance = 155;
                var speed = 0.16;
                var dpr = window.devicePixelRatio || 1;
                var mouse = {
                    x: window.innerWidth / 2,
                    y: window.innerHeight / 2,
                    active: false,
                    radius: 180
                };

                function drawTechnicalGrid() {
                    var w = window.innerWidth;
                    var h = window.innerHeight;
                    var gridGap = w > 1400 ? 56 : (w > 900 ? 48 : 42);
                    ctx.strokeStyle = 'rgba(56, 189, 248, 0.06)';
                    ctx.lineWidth = 1;
                    for (var gx = 0; gx <= w; gx += gridGap) {
                        ctx.beginPath();
                        ctx.moveTo(gx, 0);
                        ctx.lineTo(gx, h);
                        ctx.stroke();
                    }
                    for (var gy = 0; gy <= h; gy += gridGap) {
                        ctx.beginPath();
                        ctx.moveTo(0, gy);
                        ctx.lineTo(w, gy);
                        ctx.stroke();
                    }
                }

                function setCanvasSize() {
                    var width = window.innerWidth;
                    var height = window.innerHeight;
                    dpr = window.devicePixelRatio || 1;
                    particleCanvas.width = Math.floor(width * dpr);
                    particleCanvas.height = Math.floor(height * dpr);
                    particleCanvas.style.width = width + 'px';
                    particleCanvas.style.height = height + 'px';
                    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                }

                function createParticles() {
                    particles = [];
                    for (var i = 0; i < particleCount; i++) {
                        particles.push({
                            x: Math.random() * window.innerWidth,
                            y: Math.random() * window.innerHeight,
                            vx: (Math.random() - 0.5) * speed,
                            vy: (Math.random() - 0.5) * speed
                        });
                    }
                }

                function animateParticles() {
                    ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
                    drawTechnicalGrid();

                    for (var i = 0; i < particles.length; i++) {
                        var p = particles[i];
                        p.x += p.vx;
                        p.y += p.vy;

                        if (p.x <= 0 || p.x >= window.innerWidth) p.vx *= -1;
                        if (p.y <= 0 || p.y >= window.innerHeight) p.vy *= -1;

                        if (mouse.active) {
                            var mdx = mouse.x - p.x;
                            var mdy = mouse.y - p.y;
                            var mdist = Math.sqrt(mdx * mdx + mdy * mdy) || 1;
                            if (mdist < mouse.radius) {
                                var force = (1 - mdist / mouse.radius) * 0.065;
                                p.vx += (mdx / mdist) * force;
                                p.vy += (mdy / mdist) * force;
                            }
                        }

                        p.vx *= 0.985;
                        p.vy *= 0.985;
                        if (p.vx > speed) p.vx = speed;
                        if (p.vx < -speed) p.vx = -speed;
                        if (p.vy > speed) p.vy = speed;
                        if (p.vy < -speed) p.vy = -speed;

                        ctx.beginPath();
                        ctx.arc(p.x, p.y, 2.8, 0, Math.PI * 2);
                        ctx.fillStyle = 'rgba(125, 211, 252, 0.45)';
                        ctx.fill();
                    }

                    for (var a = 0; a < particles.length; a++) {
                        for (var b = a + 1; b < particles.length; b++) {
                            var dx = particles[a].x - particles[b].x;
                            var dy = particles[a].y - particles[b].y;
                            var dist = Math.sqrt(dx * dx + dy * dy);
                            if (dist < connectDistance) {
                                var alpha = (1 - dist / connectDistance) * 0.18;
                                ctx.beginPath();
                                ctx.moveTo(particles[a].x, particles[a].y);
                                ctx.lineTo(particles[b].x, particles[b].y);
                                ctx.strokeStyle = 'rgba(56, 189, 248, ' + alpha + ')';
                                ctx.lineWidth = 2.35;
                                ctx.stroke();
                            }
                        }
                    }

                    if (mouse.active) {
                        for (var c = 0; c < particles.length; c++) {
                            var cx = particles[c].x - mouse.x;
                            var cy = particles[c].y - mouse.y;
                            var cdist = Math.sqrt(cx * cx + cy * cy);
                            if (cdist < mouse.radius * 0.9) {
                                var calpha = (1 - cdist / (mouse.radius * 0.9)) * 0.42;
                                ctx.beginPath();
                                ctx.moveTo(particles[c].x, particles[c].y);
                                ctx.lineTo(mouse.x, mouse.y);
                                ctx.strokeStyle = 'rgba(34, 211, 238, ' + calpha + ')';
                                ctx.lineWidth = 1.6;
                                ctx.stroke();
                            }
                        }

                        var bubbleRadius = mouse.radius * 1.2;
                        var glow = ctx.createRadialGradient(mouse.x, mouse.y, 12, mouse.x, mouse.y, bubbleRadius);
                        glow.addColorStop(0, 'rgba(34, 211, 238, 0.22)');
                        glow.addColorStop(1, 'rgba(34, 211, 238, 0)');
                        ctx.fillStyle = glow;
                        ctx.beginPath();
                        ctx.arc(mouse.x, mouse.y, bubbleRadius, 0, Math.PI * 2);
                        ctx.fill();
                    }

                    window.requestAnimationFrame(animateParticles);
                }

                setCanvasSize();
                createParticles();
                animateParticles();

                window.addEventListener('resize', function() {
                    particleCount = window.innerWidth >= 1536 ? 120 : (window.innerWidth >= 1024 ? 92 : 56);
                    setCanvasSize();
                    createParticles();
                });
                window.addEventListener('mousemove', function(e) {
                    mouse.x = e.clientX;
                    mouse.y = e.clientY;
                    mouse.active = true;
                });
                window.addEventListener('mouseout', function() {
                    mouse.active = false;
                });
                window.addEventListener('blur', function() {
                    mouse.active = false;
                });
            }
        }
    })();
</script>
@endpush
@endsection