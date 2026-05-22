<header class="sticky top-0 z-50 border-b border-cyan-400/20 bg-[linear-gradient(180deg,rgba(2,6,23,0.92),rgba(2,6,23,0.78))] text-white shadow-[0_10px_30px_rgba(2,6,23,0.7)] backdrop-blur-xl">
    <div class="container mx-auto flex h-16 items-center justify-between gap-3 px-4">
        {{-- Logo (left) --}}
        <a href="{{ route('home') }}" class="group flex items-center gap-2 text-lg font-bold tracking-[0.02em]">
            <span class="text-2xl text-cyan-300 transition group-hover:scale-110 group-hover:text-cyan-200">◇</span>
            <span class="text-slate-100">CAM Solutions</span>
        </a>

        {{-- Menus (right) --}}
        <nav class="hidden items-center gap-1 rounded-xl border border-slate-700/70 bg-slate-900/60 p-1 lg:flex">
            @php
                $nav_base = 'px-3 py-2 rounded-lg text-sm font-medium transition';
                $nav_active = 'border border-cyan-400/60 bg-cyan-500/15 text-cyan-100 shadow-[0_0_0_1px_rgba(34,211,238,0.15)]';
                $nav_idle = 'border border-transparent text-slate-200 hover:border-slate-600/80 hover:bg-slate-800/80 hover:text-white';
            @endphp
            <a href="{{ route('home') }}"
               class="{{ $nav_base }} {{ request()->routeIs('home') ? $nav_active : $nav_idle }}">Home</a>
            <a href="{{ route('videos') }}"
               class="{{ $nav_base }} {{ request()->routeIs('videos') ? $nav_active : $nav_idle }}">Videos</a>
            <a href="{{ route('about') }}"
               class="{{ $nav_base }} {{ request()->routeIs('about') ? $nav_active : $nav_idle }}">About Us</a>
            <a href="{{ route('jobs.index') }}"
               class="{{ $nav_base }} {{ request()->routeIs('jobs.*') ? $nav_active : $nav_idle }}">Find jobs</a>
            <a href="{{ route('contact') }}"
               class="{{ $nav_base }} {{ request()->routeIs('contact') ? $nav_active : $nav_idle }}">Contact Us</a>
            <a href="{{ route('models') }}"
               class="{{ $nav_base }} {{ request()->routeIs('models') ? $nav_active : $nav_idle }}">Models</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}"
                       class="{{ $nav_base }} {{ request()->routeIs('dashboard*') ? $nav_active : $nav_idle }}">Dashboard</a>
                @endif
                <span class="rounded-lg border border-slate-600/80 bg-slate-800/90 px-2.5 py-1.5 text-xs font-medium text-slate-200">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-lg border border-slate-600/80 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-200 transition hover:border-cyan-400/60 hover:text-cyan-100">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-lg border border-slate-600/80 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-200 transition hover:border-cyan-400/60 hover:text-cyan-100 {{ request()->routeIs('login') ? 'border-cyan-400/70 text-cyan-100' : '' }}">Login</a>
                <a href="{{ route('register') }}"
                   class="rounded-lg bg-cyan-500 px-3 py-2 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400 {{ request()->routeIs('register') ? 'ring-2 ring-cyan-300/60' : '' }}">Sign Up</a>
            @endauth
        </nav>
        <div class="flex items-center gap-2 lg:hidden">
            <a href="{{ route('videos') }}" class="rounded-lg border border-slate-600/80 bg-slate-900 px-3 py-2 text-xs font-medium text-slate-200 transition hover:border-cyan-400/60 hover:text-cyan-100">Videos</a>
            <a href="{{ route('contact') }}" class="rounded-lg bg-cyan-500 px-3 py-2 text-xs font-semibold text-slate-950 transition hover:bg-cyan-400">Contact</a>
        </div>
    </div>
</header>
