@extends('layouts.app')

@section('title', 'Models')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">Models</h1>
        <p class="text-slate-600 mb-8">Download model PDFs linked to training videos. New models appear here automatically when added in the dashboard.</p>

        @if (empty($models))
            <div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-600">
                <p class="text-lg font-medium">No models available yet.</p>
                <p class="mt-2 text-sm">Ask an administrator to attach model PDFs to training videos from the dashboard.</p>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($models as $model)
                    <article class="flex flex-col bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition">
                        @if(!empty($model['thumbnail_url']))
                        <div class="aspect-video w-full bg-slate-100">
                            <img src="{{ $model['thumbnail_url'] }}" alt="" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        @endif
                        <div class="flex-1 p-5">
                            @if(!empty($model['category_name']))
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 mb-3">
                                    {{ $model['category_name'] }}
                                </span>
                            @endif
                            <h2 class="text-base md:text-lg font-semibold text-slate-800 mb-2 line-clamp-2">
                                {{ $model['title'] }}
                            </h2>
                            <p class="text-xs text-slate-500 break-all">
                                {{ $model['filename'] }}
                            </p>
                        </div>
                        <div class="px-5 pb-4 pt-2 border-t border-slate-200 bg-slate-50">
                            <a href="{{ route('download.model', ['path' => $model['download_path']]) }}"
                                @if(!empty($model['tooltip'])) title="{{ $model['tooltip'] }}" @endif
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
