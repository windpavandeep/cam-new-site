@extends('layouts.app')

@section('title', 'Models')

@section('content')
    <div class="container mx-auto max-w-[1500px] px-4 py-10">
        <h1 class="mb-2 text-3xl font-bold text-slate-100">Models Draw</h1>
        <p class="mb-8 text-slate-400">Download model PDFs linked to training videos. New models appear here automatically when added in the dashboard.</p>

        @if (empty($models))
            <div class="rounded-xl border border-slate-700/70 bg-slate-900/70 p-8 text-center text-slate-400">
                <p class="text-lg font-medium">No models available yet.</p>
                <p class="mt-2 text-sm">Ask an administrator to attach model PDFs to training videos from the dashboard.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                @foreach ($models as $model)
                    <article class="group flex flex-col overflow-hidden rounded-xl border border-slate-600/70 bg-gradient-to-b from-slate-800/95 to-slate-900/95 shadow-[0_10px_24px_rgba(2,6,23,0.55)] transition duration-200 hover:-translate-y-1 hover:border-cyan-400/50">
                        @if(!empty($model['thumbnail_url']))
                        <div class="relative aspect-video w-full bg-slate-900">
                            <img src="{{ $model['thumbnail_url'] }}" alt="" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                            @if(!empty($model['category_name']))
                            <span class="pointer-events-none absolute left-2 top-2 inline-flex items-center rounded-md bg-cyan-500/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-cyan-50">
                                {{ $model['category_name'] }}
                            </span>
                            @endif
                        </div>
                        @else
                        <div class="relative flex aspect-video w-full items-center justify-center bg-slate-900/80 text-slate-400">
                            <span class="text-xs font-semibold uppercase tracking-wide">Model Preview</span>
                        </div>
                        @endif
                        <div class="flex-1 p-3">
                            <h2 class="mb-2 min-h-[3rem] line-clamp-2 text-base font-semibold leading-snug text-slate-100">
                                {{ $model['title'] }}
                            </h2>
                            <div class="mb-3 flex items-center gap-4 text-xs text-slate-300">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10M7 16h6" />
                                    </svg>
                                    Model PDF
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ number_format($model['downloads_count'] ?? 0) }} Downloads
                                </span>
                            </div>
                            <p class="truncate text-xs text-slate-500">{{ $model['filename'] }}</p>
                        </div>
                        <div class="border-t border-slate-700/70 px-3 pb-3 pt-2 bg-slate-900/50">
                            @auth
                            <a href="{{ route('download.model', ['path' => $model['download_path']]) }}"
                                @if(!empty($model['tooltip'])) title="{{ $model['tooltip'] }}" @endif
                                class="js-model-download inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#2563eb] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1d4ed8] focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900"
                                data-download-path="{{ $model['download_path'] }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Download</span>
                                <span class="js-download-count rounded-md bg-white/20 px-2 py-0.5 text-xs font-semibold tabular-nums" title="Total downloads">{{ number_format($model['downloads_count'] ?? 0) }}</span>
                            </a>
                            @else
                            <a href="{{ route('login') }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-cyan-500/40 bg-cyan-500/10 px-4 py-2.5 text-sm font-medium text-cyan-200 transition hover:bg-cyan-500/20 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                                Log in to download
                            </a>
                            @endauth
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
