<header class="sticky top-0 z-50 border-b border-cam-line bg-white/90 text-cam-ink shadow-[0_8px_24px_rgba(15,39,68,0.06)] backdrop-blur-xl">
    <div class="container mx-auto flex h-16 items-center justify-between gap-3 px-4">
        <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-cam-steel text-sm font-bold text-white shadow-sm transition group-hover:bg-cam-sky">CS</span>
            <span class="font-display text-lg font-bold tracking-tight text-cam-ink">CAM Solutions</span>
        </a>

        <nav class="hidden items-center gap-1 rounded-2xl border border-cam-line bg-cam-mist/70 p-1 lg:flex">
            @php
                $nav_base = 'px-3 py-2 rounded-xl text-sm font-medium transition';
                $nav_active = 'bg-white text-cam-steel shadow-sm ring-1 ring-cam-line';
                $nav_idle = 'text-slate-600 hover:bg-white/80 hover:text-cam-ink';
            @endphp
            <a href="{{ route('home') }}" class="{{ $nav_base }} {{ request()->routeIs('home') ? $nav_active : $nav_idle }}">Home</a>
            <a href="{{ route('videos') }}" class="{{ $nav_base }} {{ request()->routeIs('videos') ? $nav_active : $nav_idle }}">Videos</a>
            <a href="{{ route('about') }}" class="{{ $nav_base }} {{ request()->routeIs('about') ? $nav_active : $nav_idle }}">About</a>
            <a href="{{ route('jobs.index') }}" class="{{ $nav_base }} {{ request()->routeIs('jobs.*') ? $nav_active : $nav_idle }}">Jobs</a>
            <a href="{{ route('contact') }}" class="{{ $nav_base }} {{ request()->routeIs('contact') ? $nav_active : $nav_idle }}">Contact</a>
            <a href="{{ route('models') }}" class="{{ $nav_base }} {{ request()->routeIs('models') ? $nav_active : $nav_idle }}">Models</a>
            <a href="{{ route('certificates.lookup') }}" class="{{ $nav_base }} {{ request()->routeIs('certificates.*') ? $nav_active : $nav_idle }}">Certificates</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}" class="{{ $nav_base }} {{ request()->routeIs('dashboard*') ? $nav_active : $nav_idle }}">Dashboard</a>
                @endif
                <span class="rounded-xl bg-white px-2.5 py-1.5 text-xs font-medium text-slate-500 ring-1 ring-cam-line">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-cam-ink">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-cam-ink">Login</a>
                <a href="{{ route('register') }}" class="rounded-xl bg-cam-steel px-3 py-2 text-sm font-semibold text-white transition hover:bg-cam-sky">Sign Up</a>
            @endauth
        </nav>

        <div class="flex items-center gap-2 lg:hidden">
            <a href="{{ route('certificates.lookup') }}" class="rounded-xl border border-cam-line bg-white px-3 py-2 text-xs font-medium text-slate-700">Certificates</a>
            <a href="{{ route('contact') }}" class="rounded-xl bg-cam-steel px-3 py-2 text-xs font-semibold text-white">Contact</a>
        </div>
    </div>
</header>
