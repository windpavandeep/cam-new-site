@php
    $fc = is_array($footer_contact ?? null) ? $footer_contact : [];
    $footer_email = isset($fc['email']) ? trim((string) $fc['email']) : '';
    $footer_phone = isset($fc['phone']) ? trim((string) $fc['phone']) : '';
    $footer_address = isset($fc['address']) ? trim((string) $fc['address']) : '';
    $footer_hours = isset($fc['hours']) ? trim((string) $fc['hours']) : '';
@endphp

<footer class="site-footer relative mt-auto overflow-hidden border-t border-sky-200/80 text-cam-ink">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-sky-50 via-white to-teal-50"></div>
    <div class="footer-bg pointer-events-none absolute inset-0" aria-hidden="true">
        <span class="footer-blob footer-blob-a"></span>
        <span class="footer-blob footer-blob-b"></span>
        <span class="footer-block footer-block-a"></span>
        <span class="footer-block footer-block-b"></span>
        <div class="footer-gear footer-gear-a">
            <svg viewBox="0 0 100 100" fill="currentColor" fill-rule="evenodd">
                <path d="M84.38 43.44 L95.64 44.23 L95.93 47.52 L85.00 50.31 L84.38 56.56 L83.82 59.01 L93.62 64.61 L92.46 67.70 L81.40 65.47 L78.13 70.83 L76.56 72.79 L82.96 82.09 L80.57 84.37 L71.58 77.56 L66.31 80.97 L64.04 82.06 L65.78 93.21 L62.64 94.23 L57.48 84.19 L51.26 84.98 L48.74 84.98 L45.47 95.78 L42.19 95.33 L41.91 84.05 L35.96 82.06 L33.69 80.97 L26.05 89.28 L23.30 87.46 L27.93 77.17 L23.44 72.79 L21.87 70.83 L11.38 75.00 L9.69 72.16 L18.33 64.90 L16.18 59.01 L15.62 56.56 L4.36 55.77 L4.07 52.48 L15.00 49.69 L15.62 43.44 L16.18 40.99 L6.38 35.39 L7.54 32.30 L18.60 34.53 L21.87 29.17 L23.44 27.21 L17.04 17.91 L19.43 15.63 L28.42 22.44 L33.69 19.03 L35.96 17.94 L34.22 6.79 L37.36 5.77 L42.52 15.81 L48.74 15.02 L51.26 15.02 L54.53 4.22 L57.81 4.67 L58.09 15.95 L64.04 17.94 L66.31 19.03 L73.95 10.72 L76.70 12.54 L72.07 22.83 L76.56 27.21 L78.13 29.17 L88.62 25.00 L90.31 27.84 L81.67 35.10 L83.82 40.99 Z M63 50 A13 13 0 1 0 37 50 A13 13 0 1 0 63 50 Z"/>
            </svg>
        </div>
        <div class="footer-gear footer-gear-b">
            <svg viewBox="0 0 100 100" fill="currentColor" fill-rule="evenodd">
                <path d="M83.18 42.58 L95.51 43.28 L95.91 47.11 L84.00 50.36 L83.18 57.42 L82.44 60.17 L92.77 66.93 L91.20 70.45 L79.27 67.31 L75.03 73.01 L73.01 75.03 L78.57 86.05 L75.46 88.31 L66.69 79.62 L60.17 82.44 L57.42 83.18 L56.72 95.51 L52.89 95.91 L49.64 84.00 L42.58 83.18 L39.83 82.44 L33.07 92.77 L29.55 91.20 L32.69 79.27 L26.99 75.03 L24.97 73.01 L13.95 78.57 L11.69 75.46 L20.38 66.69 L17.56 60.17 L16.82 57.42 L4.49 56.72 L4.09 52.89 L16.00 49.64 L16.82 42.58 L17.56 39.83 L7.23 33.07 L8.80 29.55 L20.73 32.69 L24.97 26.99 L26.99 24.97 L21.43 13.95 L24.54 11.69 L33.31 20.38 L39.83 17.56 L42.58 16.82 L43.28 4.49 L47.11 4.09 L50.36 16.00 L57.42 16.82 L60.17 17.56 L66.93 7.23 L70.45 8.80 L67.31 20.73 L73.01 24.97 L75.03 26.99 L86.05 21.43 L88.31 24.54 L79.62 33.31 L82.44 39.83 Z M62 50 A12 12 0 1 0 38 50 A12 12 0 1 0 62 50 Z"/>
            </svg>
        </div>
    </div>

    <div class="relative z-10 mx-auto max-w-[1500px] px-4 pb-6 pt-12 md:pt-14">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-12 lg:gap-8">
            {{-- Brand --}}
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-cam-steel via-cam-sky to-teal-600 text-sm font-bold text-white shadow-md shadow-sky-500/25">CS</span>
                    <span>
                        <span class="font-display block text-lg font-bold text-cam-ink">CAM Solutions</span>
                        <span class="text-[10px] font-semibold uppercase tracking-[0.16em] text-sky-600/80">CNC training studio</span>
                    </span>
                </a>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-600">
                    Practical Mastercam and CNC programming lessons — toolpaths, models, jobs, and certificates in one place.
                </p>
                <a href="{{ route('contact') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cam-steel to-cam-sky px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-sky-500/20 transition hover:brightness-105">
                    Contact Us
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Explore --}}
            <div class="lg:col-span-2">
                <h3 class="footer-heading">Explore</h3>
                <ul class="mt-4 space-y-2.5">
                    <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                    <li><a href="{{ route('videos') }}" class="footer-link">Videos</a></li>
                    <li><a href="{{ route('about') }}" class="footer-link">About Us</a></li>
                    <li><a href="{{ route('models') }}" class="footer-link">Models</a></li>
                    <li><a href="{{ route('jobs.index') }}" class="footer-link">Find Jobs</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div class="lg:col-span-2">
                <h3 class="footer-heading">Resources</h3>
                <ul class="mt-4 space-y-2.5">
                    <li><a href="{{ route('certificates.lookup') }}" class="footer-link">Certificates</a></li>
                    <li><a href="{{ route('videos') }}" class="footer-link">Course library</a></li>
                    <li><a href="{{ route('models') }}" class="footer-link">Download models</a></li>
                    @guest
                        <li><a href="{{ route('login') }}" class="footer-link">Login</a></li>
                        <li><a href="{{ route('register') }}" class="footer-link">Sign Up</a></li>
                    @else
                        @if(auth()->user()->isAdmin())
                            <li><a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="footer-link">Logout</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-4">
                <h3 class="footer-heading">Contact Us</h3>
                <p class="mt-4 text-sm text-slate-600">Reach the CAM Solutions team for training questions, partnerships, or support.</p>
                <div class="mt-4 space-y-3 rounded-2xl border border-sky-200/80 bg-white/80 p-4 shadow-sm backdrop-blur-sm">
                    @if ($footer_email !== '')
                    <a href="mailto:{{ $footer_email }}" class="footer-contact-row">
                        <span class="footer-contact-icon">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <span class="min-w-0 break-all">{{ $footer_email }}</span>
                    </a>
                    @endif
                    @if ($footer_phone !== '')
                    <a href="tel:{{ preg_replace('/\s+/', '', $footer_phone) }}" class="footer-contact-row">
                        <span class="footer-contact-icon">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        <span>{{ $footer_phone }}</span>
                    </a>
                    @endif
                    @if ($footer_address !== '')
                    <div class="footer-contact-row">
                        <span class="footer-contact-icon">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <span class="whitespace-pre-line">{{ $footer_address }}</span>
                    </div>
                    @endif
                    @if ($footer_hours !== '')
                    <div class="footer-contact-row">
                        <span class="footer-contact-icon">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span>{{ $footer_hours }}</span>
                    </div>
                    @endif
                    @if ($footer_email === '' && $footer_phone === '' && $footer_address === '')
                    <p class="text-sm text-slate-500">Visit our <a href="{{ route('contact') }}" class="font-semibold text-cam-steel underline decoration-sky-300 underline-offset-2 hover:text-cam-sky">Contact page</a> to send a message.</p>
                    @else
                    <a href="{{ route('contact') }}" class="inline-flex text-sm font-semibold text-cam-steel transition hover:text-cam-sky">Open contact form →</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-sky-200/80 pt-5 text-sm text-slate-500 sm:flex-row">
            <p>&copy; {{ date('Y') }} <span class="font-semibold text-cam-ink">CAM Solutions</span>. All rights reserved.</p>
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
                <a href="{{ route('about') }}" class="transition hover:text-cam-steel">About</a>
                <a href="{{ route('contact') }}" class="transition hover:text-cam-steel">Contact</a>
                <a href="{{ route('certificates.lookup') }}" class="transition hover:text-cam-steel">Certificates</a>
                <a href="{{ route('jobs.index') }}" class="transition hover:text-cam-steel">Jobs</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-heading {
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #1a5f8a;
    }
    .footer-link {
        display: inline-flex;
        font-size: 0.9rem;
        font-weight: 500;
        color: #475569;
        transition: color 180ms ease, transform 180ms ease;
    }
    .footer-link:hover {
        color: #1a5f8a;
        transform: translateX(3px);
    }
    .footer-contact-row {
        display: flex;
        align-items: flex-start;
        gap: 0.7rem;
        font-size: 0.875rem;
        color: #334155;
        transition: color 180ms ease;
    }
    a.footer-contact-row:hover { color: #1a5f8a; }
    .footer-contact-icon {
        display: inline-flex;
        height: 1.85rem;
        width: 1.85rem;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 0.65rem;
        border: 1px solid rgba(43, 124, 181, 0.2);
        background: linear-gradient(145deg, #e0f2fe, #f0fdfa);
        color: #1a5f8a;
    }

    .footer-blob {
        position: absolute;
        border-radius: 9999px;
        filter: blur(30px);
        opacity: 0.5;
    }
    .footer-blob-a {
        width: 16rem; height: 16rem; top: -4rem; left: 5%;
        background: rgba(56, 189, 248, 0.22);
        animation: footerPulse 11s ease-in-out infinite;
    }
    .footer-blob-b {
        width: 14rem; height: 14rem; bottom: -3rem; right: 8%;
        background: rgba(20, 184, 166, 0.2);
        animation: footerPulse 13s ease-in-out infinite reverse;
    }
    .footer-block {
        position: absolute;
        border-radius: 0.7rem;
        border: 1px solid rgba(43, 124, 181, 0.16);
        background: linear-gradient(145deg, rgba(43, 124, 181, 0.12), rgba(15, 118, 110, 0.08));
        opacity: 0.55;
    }
    .footer-block-a { width: 2.8rem; height: 2.8rem; top: 22%; right: 22%; animation: footerFloatA 14s ease-in-out infinite; }
    .footer-block-b { width: 2rem; height: 2rem; bottom: 28%; left: 18%; animation: footerFloatB 12s ease-in-out infinite; }

    .footer-gear {
        position: absolute;
        display: grid;
        place-items: center;
        transform-origin: center;
        will-change: transform;
    }
    .footer-gear svg { width: 100%; height: 100%; display: block; }
    .footer-gear-a {
        width: 7.5rem; height: 7.5rem; top: -1.2rem; right: 4%;
        color: rgba(26, 95, 138, 0.12);
        animation: footerSpin 50s linear infinite;
    }
    .footer-gear-b {
        width: 5.5rem; height: 5.5rem; bottom: -0.8rem; left: 6%;
        color: rgba(15, 118, 110, 0.14);
        animation: footerSpin 38s linear infinite reverse;
    }

    @keyframes footerPulse {
        0%, 100% { transform: scale(1); opacity: 0.35; }
        50% { transform: scale(1.1); opacity: 0.55; }
    }
    @keyframes footerFloatA {
        0%, 100% { transform: translate(0, 0) rotate(14deg); }
        50% { transform: translate(8px, -10px) rotate(22deg); }
    }
    @keyframes footerFloatB {
        0%, 100% { transform: translate(0, 0) rotate(-10deg); }
        50% { transform: translate(-8px, 8px) rotate(-4deg); }
    }
    @keyframes footerSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        .footer-blob, .footer-block, .footer-gear {
            animation: none !important;
        }
    }
</style>
