@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top,rgba(34,211,238,0.14),transparent_40%),linear-gradient(145deg,rgba(2,6,23,0.82),rgba(15,23,42,0.88))]"></div>
    <div class="container mx-auto max-w-5xl px-4 py-10 md:py-14">
        <header class="mb-10 md:mb-14">
            <p class="mb-2 text-sm font-semibold uppercase tracking-[0.22em] text-cyan-300/90">About CAM Solutions</p>
            <h1 class="mb-4 text-3xl font-bold text-slate-100 md:text-4xl">CNC programming training built for real shops</h1>
            <p class="max-w-3xl text-lg leading-relaxed text-slate-300">
                We help machinists, programmers, and engineers build confidence with <strong class="text-cyan-200">Mastercam</strong> from 2D milling and multi-axis work to turning using clear video lessons and downloadable models you can follow along with.
            </p>
        </header>

        <div class="grid gap-6 md:gap-8 md:grid-cols-2 mb-12">
            <section class="rounded-2xl border border-slate-700/70 bg-slate-900/75 p-6 shadow-[0_8px_24px_rgba(2,6,23,0.4)] md:p-8">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h2 class="mb-3 text-xl font-semibold text-slate-100">Our mission</h2>
                <p class="leading-relaxed text-slate-300">
                    To make professional-grade CNC education accessible: structured categories, practical examples, and resources you can reuse on the floor—not just theory on a slide.
                </p>
            </section>
            <section class="rounded-2xl border border-slate-700/70 bg-slate-900/75 p-6 shadow-[0_8px_24px_rgba(2,6,23,0.4)] md:p-8">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg border border-cyan-400/30 bg-cyan-500/10 text-cyan-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="mb-3 text-xl font-semibold text-slate-100">Who we serve</h2>
                <p class="leading-relaxed text-slate-300">
                    Apprentices, career switchers, and experienced operators who want to sharpen Mastercam skills across <strong class="text-cyan-200">milling</strong>, <strong class="text-cyan-200">multi-axis</strong>, and <strong class="text-cyan-200">turning</strong> at your own pace.
                </p>
            </section>
        </div>

        <section class="mb-12 rounded-2xl border border-slate-700/70 bg-slate-900/75 p-6 shadow-[0_8px_24px_rgba(2,6,23,0.4)] md:p-10">
            <h2 class="mb-6 text-2xl font-semibold text-slate-100">What you’ll find here</h2>
            <ul class="space-y-4 text-slate-300">
                <li class="flex gap-3">
                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-cyan-500 text-xs font-bold text-slate-950">1</span>
                    <span><strong class="text-cyan-200">Curated video library</strong> — YouTube-based lessons organized by category, with thumbnails and playback in a clean modal on the site.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-cyan-500 text-xs font-bold text-slate-950">2</span>
                    <span><strong class="text-cyan-200">Model files</strong> — Supporting documents and CAD-related files (PDF, Office, STEP, and more) through our media library, linked to each lesson where applicable.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-0.5 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-cyan-500 text-xs font-bold text-slate-950">3</span>
                    <span><strong class="text-cyan-200">Accounts for learners</strong> — Sign in to download models and track activity; admins manage categories, media, and the home page slider from the dashboard.</span>
                </li>
            </ul>
        </section>

        <div class="flex flex-col gap-4 rounded-2xl border border-cyan-400/20 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,0.18),transparent_38%),linear-gradient(120deg,rgba(15,23,42,0.95),rgba(2,6,23,0.95))] p-6 text-white sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-semibold text-lg mb-1">Questions or partnership ideas?</p>
                <p class="text-slate-300 text-sm">We’d like to hear from you.</p>
            </div>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                Contact us
            </a>
        </div>
    </div>
</div>
@endsection
