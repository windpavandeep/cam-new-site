@php
    $nav_items = [
        ['route' => 'home', 'match' => 'home', 'label' => 'Home'],
        ['route' => 'videos', 'match' => 'videos', 'label' => 'Videos'],
        ['route' => 'about', 'match' => 'about', 'label' => 'About'],
        ['route' => 'jobs.index', 'match' => 'jobs.*', 'label' => 'Jobs'],
        ['route' => 'models', 'match' => 'models', 'label' => 'Models'],
        ['route' => 'certificates.lookup', 'match' => 'certificates.*', 'label' => 'Certificates'],
        ['route' => 'contact', 'match' => 'contact', 'label' => 'Contact'],
    ];
@endphp

<header id="siteHeader" class="site-header sticky top-0 z-50 overflow-hidden border-b border-cam-line/80 text-cam-ink">
    {{-- Animated navbar atmosphere --}}
    <div class="nav-bg pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="nav-bg-wash absolute inset-0"></div>
        <div class="nav-bg-shine absolute inset-y-0 left-0 w-1/3"></div>

        <span class="nav-block nav-block-a"></span>
        <span class="nav-block nav-block-b"></span>
        <span class="nav-block nav-block-c"></span>
        <span class="nav-block nav-block-d"></span>
        <span class="nav-dot nav-dot-a"></span>
        <span class="nav-dot nav-dot-b"></span>
        <span class="nav-dot nav-dot-c"></span>

        <div class="nav-gear nav-gear-a">
            <svg viewBox="0 0 100 100" fill="currentColor" fill-rule="evenodd">
                <path d="M84.38 43.44 L95.64 44.23 L95.93 47.52 L85.00 50.31 L84.38 56.56 L83.82 59.01 L93.62 64.61 L92.46 67.70 L81.40 65.47 L78.13 70.83 L76.56 72.79 L82.96 82.09 L80.57 84.37 L71.58 77.56 L66.31 80.97 L64.04 82.06 L65.78 93.21 L62.64 94.23 L57.48 84.19 L51.26 84.98 L48.74 84.98 L45.47 95.78 L42.19 95.33 L41.91 84.05 L35.96 82.06 L33.69 80.97 L26.05 89.28 L23.30 87.46 L27.93 77.17 L23.44 72.79 L21.87 70.83 L11.38 75.00 L9.69 72.16 L18.33 64.90 L16.18 59.01 L15.62 56.56 L4.36 55.77 L4.07 52.48 L15.00 49.69 L15.62 43.44 L16.18 40.99 L6.38 35.39 L7.54 32.30 L18.60 34.53 L21.87 29.17 L23.44 27.21 L17.04 17.91 L19.43 15.63 L28.42 22.44 L33.69 19.03 L35.96 17.94 L34.22 6.79 L37.36 5.77 L42.52 15.81 L48.74 15.02 L51.26 15.02 L54.53 4.22 L57.81 4.67 L58.09 15.95 L64.04 17.94 L66.31 19.03 L73.95 10.72 L76.70 12.54 L72.07 22.83 L76.56 27.21 L78.13 29.17 L88.62 25.00 L90.31 27.84 L81.67 35.10 L83.82 40.99 Z M63 50 A13 13 0 1 0 37 50 A13 13 0 1 0 63 50 Z"/>
            </svg>
        </div>
        <div class="nav-gear nav-gear-b">
            <svg viewBox="0 0 100 100" fill="currentColor" fill-rule="evenodd">
                <path d="M83.18 42.58 L95.51 43.28 L95.91 47.11 L84.00 50.36 L83.18 57.42 L82.44 60.17 L92.77 66.93 L91.20 70.45 L79.27 67.31 L75.03 73.01 L73.01 75.03 L78.57 86.05 L75.46 88.31 L66.69 79.62 L60.17 82.44 L57.42 83.18 L56.72 95.51 L52.89 95.91 L49.64 84.00 L42.58 83.18 L39.83 82.44 L33.07 92.77 L29.55 91.20 L32.69 79.27 L26.99 75.03 L24.97 73.01 L13.95 78.57 L11.69 75.46 L20.38 66.69 L17.56 60.17 L16.82 57.42 L4.49 56.72 L4.09 52.89 L16.00 49.64 L16.82 42.58 L17.56 39.83 L7.23 33.07 L8.80 29.55 L20.73 32.69 L24.97 26.99 L26.99 24.97 L21.43 13.95 L24.54 11.69 L33.31 20.38 L39.83 17.56 L42.58 16.82 L43.28 4.49 L47.11 4.09 L50.36 16.00 L57.42 16.82 L60.17 17.56 L66.93 7.23 L70.45 8.80 L67.31 20.73 L73.01 24.97 L75.03 26.99 L86.05 21.43 L88.31 24.54 L79.62 33.31 L82.44 39.83 Z M62 50 A12 12 0 1 0 38 50 A12 12 0 1 0 62 50 Z"/>
            </svg>
        </div>
        <div class="nav-gear nav-gear-c">
            <svg viewBox="0 0 100 100" fill="currentColor" fill-rule="evenodd">
                <path d="M85.51 44.09 L95.72 44.95 L95.95 47.83 L86.00 50.28 L85.51 55.91 L85.07 58.13 L94.17 62.83 L93.28 65.58 L83.15 64.04 L80.55 69.05 L79.29 70.93 L85.90 78.76 L84.02 80.96 L75.26 75.65 L70.93 79.29 L69.05 80.55 L72.16 90.31 L69.59 91.62 L63.51 83.37 L58.13 85.07 L55.91 85.51 L55.05 95.72 L52.17 95.95 L49.72 86.00 L44.09 85.51 L41.87 85.07 L37.17 94.17 L34.42 93.28 L35.96 83.15 L30.95 80.55 L29.07 79.29 L21.24 85.90 L19.04 84.02 L24.35 75.26 L20.71 70.93 L19.45 69.05 L9.69 72.16 L8.38 69.59 L16.63 63.51 L14.93 58.13 L14.49 55.91 L4.28 55.05 L4.05 52.17 L14.00 49.72 L14.49 44.09 L14.93 41.87 L5.83 37.17 L6.72 34.42 L16.85 35.96 L19.45 30.95 L20.71 29.07 L14.10 21.24 L15.98 19.04 L24.74 24.35 L29.07 20.71 L30.95 19.45 L27.84 9.69 L30.41 8.38 L36.49 16.63 L41.87 14.93 L44.09 14.49 L44.95 4.28 L47.83 4.05 L50.28 14.00 L55.91 14.49 L58.13 14.93 L62.83 5.83 L65.58 6.72 L64.04 16.85 L69.05 19.45 L70.93 20.71 L78.76 14.10 L80.96 15.98 L75.65 24.74 L79.29 29.07 L80.55 30.95 L90.31 27.84 L91.62 30.41 L83.37 36.49 L85.07 41.87 Z M64 50 A14 14 0 1 0 36 50 A14 14 0 1 0 64 50 Z"/>
            </svg>
        </div>
    </div>

    <div class="pointer-events-none absolute inset-x-0 bottom-0 z-[1] h-px bg-gradient-to-r from-transparent via-sky-400/80 to-transparent"></div>

    <div class="relative z-10 container mx-auto flex h-[4.25rem] items-center justify-between gap-3 px-4">
        <a href="{{ route('home') }}" class="brand-link group flex items-center gap-2.5">
            <span class="brand-mark relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-cam-steel via-cam-sky to-teal-600 text-sm font-bold text-white shadow-md shadow-sky-500/25">
                <span class="brand-mark-glow absolute inset-0 opacity-0 transition duration-300 group-hover:opacity-100"></span>
                <span class="relative">CS</span>
            </span>
            <span class="leading-tight">
                <span class="font-display block text-lg font-bold tracking-tight text-cam-ink transition group-hover:text-cam-steel">CAM Solutions</span>
                <span class="hidden text-[10px] font-semibold uppercase tracking-[0.16em] text-sky-600/80 sm:block">CNC training</span>
            </span>
        </a>

        <nav class="site-nav hidden items-center gap-1 rounded-2xl border border-sky-200/70 bg-white/75 p-1.5 shadow-[inset_0_1px_0_rgba(255,255,255,0.9),0_8px_20px_rgba(43,124,181,0.08)] backdrop-blur-md lg:flex" aria-label="Main">
            @foreach ($nav_items as $item)
                @php $is_active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="nav-link {{ $is_active ? 'is-active' : '' }}"
                   @if($is_active) aria-current="page" @endif>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard*') ? 'is-active' : '' }}">
                        <span>Dashboard</span>
                    </a>
                @endif
                <span class="mx-1 hidden h-5 w-px bg-sky-200 xl:block" aria-hidden="true"></span>
                <span class="nav-user hidden max-w-[8rem] truncate rounded-xl bg-gradient-to-r from-sky-50 to-teal-50 px-2.5 py-1.5 text-xs font-medium text-cam-steel ring-1 ring-sky-200 xl:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="nav-link">Logout</button>
                </form>
            @else
                <span class="mx-1 hidden h-5 w-px bg-sky-200 xl:block" aria-hidden="true"></span>
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'is-active' : '' }}">Login</a>
                <a href="{{ route('register') }}" class="nav-cta">Sign Up</a>
            @endauth
        </nav>

        <div class="flex items-center gap-2 lg:hidden">
            <a href="{{ route('certificates.lookup') }}" class="rounded-xl border border-sky-200 bg-white/90 px-3 py-2 text-xs font-semibold text-cam-steel transition hover:border-teal-300 hover:text-teal-700">Certs</a>
            <button type="button" id="mobileNavToggle" class="mobile-nav-toggle inline-flex h-10 w-10 items-center justify-center rounded-xl border border-sky-200 bg-white/90 text-cam-ink transition hover:border-sky-400 hover:text-cam-steel" aria-expanded="false" aria-controls="mobileNavPanel" aria-label="Open menu">
                <svg class="icon-open h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>
                <svg class="icon-close hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div id="mobileNavPanel" class="mobile-nav-panel relative z-10 lg:hidden" hidden>
        <div class="border-t border-sky-100 bg-white/95 px-4 py-4 backdrop-blur-xl">
            <div class="grid gap-1">
                @foreach ($nav_items as $item)
                    @php $is_active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="mobile-nav-link {{ $is_active ? 'is-active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard*') ? 'is-active' : '' }}">Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="mobile-nav-link w-full text-left">Logout ({{ auth()->user()->name }})</button>
                    </form>
                @else
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="rounded-xl border border-sky-200 px-4 py-2.5 text-center text-sm font-semibold text-cam-ink">Login</a>
                        <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-cam-steel to-cam-sky px-4 py-2.5 text-center text-sm font-semibold text-white">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>

<style>
    .site-header {
        background: rgba(255, 255, 255, 0.78);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: 0 10px 30px rgba(15, 39, 68, 0.05);
        transition: box-shadow 280ms ease, background-color 280ms ease;
    }
    .site-header.is-scrolled {
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 14px 36px rgba(15, 39, 68, 0.1);
    }

    .nav-bg-wash {
        background:
            radial-gradient(ellipse 40% 120% at 8% 50%, rgba(56, 189, 248, 0.18), transparent 60%),
            radial-gradient(ellipse 35% 120% at 92% 40%, rgba(20, 184, 166, 0.16), transparent 60%),
            linear-gradient(90deg, rgba(238, 244, 249, 0.95), rgba(255, 255, 255, 0.75) 40%, rgba(236, 253, 245, 0.9));
    }
    .nav-bg-shine {
        background: linear-gradient(105deg, transparent, rgba(255, 255, 255, 0.55), transparent);
        animation: navShine 7s ease-in-out infinite;
    }

    .nav-block {
        position: absolute;
        border-radius: 0.55rem;
        border: 1px solid rgba(43, 124, 181, 0.22);
        background: linear-gradient(145deg, rgba(43, 124, 181, 0.16), rgba(15, 118, 110, 0.1));
        opacity: 0.7;
    }
    .nav-block-a { width: 1.35rem; height: 1.35rem; top: 0.7rem; left: 18%; animation: navFloatA 8s ease-in-out infinite; }
    .nav-block-b { width: 1rem; height: 1rem; bottom: 0.55rem; left: 34%; background: linear-gradient(145deg, rgba(20, 184, 166, 0.2), rgba(56, 189, 248, 0.12)); animation: navFloatB 9s ease-in-out infinite; }
    .nav-block-c { width: 1.7rem; height: 0.7rem; top: 1.1rem; right: 26%; animation: navFloatC 10s ease-in-out infinite; }
    .nav-block-d { width: 0.85rem; height: 0.85rem; bottom: 0.7rem; right: 14%; background: linear-gradient(145deg, rgba(14, 39, 68, 0.12), rgba(43, 124, 181, 0.18)); animation: navFloatA 7s ease-in-out infinite reverse; }

    .nav-dot {
        position: absolute;
        width: 0.4rem;
        height: 0.4rem;
        border-radius: 999px;
        background: #38bdf8;
        opacity: 0.55;
        animation: navPulse 3.4s ease-in-out infinite;
    }
    .nav-dot-a { top: 1.15rem; left: 46%; }
    .nav-dot-b { bottom: 0.9rem; left: 58%; background: #14b8a6; animation-delay: 0.6s; }
    .nav-dot-c { top: 1.4rem; right: 40%; background: #2b7cb5; animation-delay: 1.1s; }

    .nav-gear {
        position: absolute;
        display: grid;
        place-items: center;
        transform-origin: center center;
        opacity: 0.9;
        will-change: transform;
    }
    .nav-gear svg { width: 100%; height: 100%; display: block; }
    .nav-gear-a {
        width: 4.6rem;
        height: 4.6rem;
        top: -1.15rem;
        left: 9%;
        color: rgba(26, 95, 138, 0.2);
        animation: navSpin 22s linear infinite;
    }
    .nav-gear-b {
        width: 3.4rem;
        height: 3.4rem;
        bottom: -0.85rem;
        left: 22%;
        color: rgba(15, 118, 110, 0.22);
        animation: navSpin 16s linear infinite reverse;
    }
    .nav-gear-c {
        width: 3.8rem;
        height: 3.8rem;
        top: -0.95rem;
        right: 8%;
        color: rgba(43, 124, 181, 0.2);
        animation: navSpin 18s linear infinite;
    }

    @keyframes navShine {
        0%, 100% { transform: translateX(-20%); opacity: 0.2; }
        50% { transform: translateX(180%); opacity: 0.55; }
    }
    @keyframes navFloatA {
        0%, 100% { transform: translate(0, 0) rotate(14deg); }
        50% { transform: translate(6px, -5px) rotate(22deg); }
    }
    @keyframes navFloatB {
        0%, 100% { transform: translate(0, 0) rotate(-10deg); }
        50% { transform: translate(-5px, 4px) rotate(-4deg); }
    }
    @keyframes navFloatC {
        0%, 100% { transform: translate(0, 0) rotate(8deg); }
        50% { transform: translate(5px, 5px) rotate(14deg); }
    }
    @keyframes navSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes navPulse {
        0%, 100% { transform: scale(1); opacity: 0.35; }
        50% { transform: scale(1.5); opacity: 0.8; }
    }

    .brand-mark {
        transition: transform 280ms cubic-bezier(0.22, 1, 0.36, 1), box-shadow 280ms ease;
    }
    .brand-link:hover .brand-mark {
        transform: translateY(-1px) rotate(-5deg) scale(1.05);
        box-shadow: 0 10px 22px rgba(43, 124, 181, 0.32);
    }
    .brand-mark-glow {
        background: radial-gradient(circle at 30% 25%, rgba(255,255,255,0.5), transparent 55%);
    }

    .nav-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        border-radius: 0.8rem;
        padding: 0.5rem 0.85rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        transition: color 200ms ease, background-color 200ms ease, transform 200ms ease, box-shadow 200ms ease;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        left: 0.85rem;
        right: 0.85rem;
        bottom: 0.28rem;
        height: 2px;
        border-radius: 999px;
        background: linear-gradient(90deg, #1a5f8a, #14b8a6);
        transform: scaleX(0);
        transform-origin: center;
        transition: transform 260ms cubic-bezier(0.22, 1, 0.36, 1);
    }
    .nav-link:hover {
        color: #0f2744;
        background: linear-gradient(180deg, #ffffff, #eef8ff);
        transform: translateY(-1px);
    }
    .nav-link:hover::after,
    .nav-link.is-active::after {
        transform: scaleX(1);
    }
    .nav-link.is-active {
        color: #1a5f8a;
        background: linear-gradient(180deg, #ffffff, #ecfeff);
        box-shadow: 0 1px 0 rgba(186, 230, 253, 0.9), 0 8px 18px rgba(43, 124, 181, 0.1);
    }

    .nav-cta {
        display: inline-flex;
        align-items: center;
        border-radius: 0.8rem;
        background: linear-gradient(135deg, #1a5f8a, #2b7cb5 55%, #0f766e);
        padding: 0.5rem 0.95rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff;
        box-shadow: 0 8px 18px rgba(26, 95, 138, 0.25);
        transition: transform 200ms ease, box-shadow 200ms ease, filter 200ms ease;
    }
    .nav-cta:hover {
        transform: translateY(-1px);
        filter: brightness(1.06);
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.28);
        color: #fff;
    }

    .mobile-nav-toggle.is-open .icon-open { display: none; }
    .mobile-nav-toggle.is-open .icon-close { display: block; }

    .mobile-nav-panel {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transform: translateY(-6px);
        transition: max-height 320ms cubic-bezier(0.22, 1, 0.36, 1), opacity 220ms ease, transform 220ms ease;
    }
    .mobile-nav-panel.is-open {
        max-height: 28rem;
        opacity: 1;
        transform: translateY(0);
    }
    .mobile-nav-link {
        display: block;
        border-radius: 0.9rem;
        padding: 0.75rem 0.9rem;
        font-size: 0.925rem;
        font-weight: 600;
        color: #334155;
        transition: background-color 180ms ease, color 180ms ease, transform 180ms ease;
    }
    .mobile-nav-link:hover {
        background: linear-gradient(90deg, #eef4f9, #ecfeff);
        color: #1a5f8a;
        transform: translateX(3px);
    }
    .mobile-nav-link.is-active {
        background: linear-gradient(90deg, rgba(26, 95, 138, 0.12), rgba(20, 184, 166, 0.1));
        color: #1a5f8a;
        box-shadow: inset 3px 0 0 #1a5f8a;
    }

    @media (prefers-reduced-motion: reduce) {
        .nav-link, .nav-link::after, .nav-cta, .brand-mark, .mobile-nav-panel, .mobile-nav-link,
        .nav-gear, .nav-block, .nav-dot, .nav-bg-shine {
            transition: none !important;
            animation: none !important;
            transform: none !important;
        }
    }
</style>

<script>
    (function () {
        var header = document.getElementById('siteHeader');
        var toggle = document.getElementById('mobileNavToggle');
        var panel = document.getElementById('mobileNavPanel');
        if (!header) return;

        function onScroll() {
            header.classList.toggle('is-scrolled', window.scrollY > 8);
        }
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });

        if (toggle && panel) {
            toggle.addEventListener('click', function () {
                var open = !panel.classList.contains('is-open');
                panel.hidden = false;
                requestAnimationFrame(function () {
                    panel.classList.toggle('is-open', open);
                    toggle.classList.toggle('is-open', open);
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                    if (!open) {
                        setTimeout(function () {
                            if (!panel.classList.contains('is-open')) panel.hidden = true;
                        }, 320);
                    }
                });
            });
        }
    })();
</script>
