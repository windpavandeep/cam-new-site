<header class="sticky top-0 z-50 border-b border-cyan-400/20 bg-slate-950/85 text-white shadow-[0_8px_30px_rgba(2,6,23,0.7)] backdrop-blur-md">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        {{-- Logo (left) --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg tracking-[0.02em]">
            <span class="text-2xl text-cyan-300">◇</span>
            <span>CAM Solutions</span>
        </a>

        {{-- Menus (right) --}}
        <nav class="flex items-center gap-1 sm:gap-2">
            <a href="{{ route('home') }}"
               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">Home</a>
            <a href="{{ route('videos') }}"
               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('videos') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">Videos</a>
            <a href="{{ route('about') }}"
               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('about') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">About Us</a>
            <a href="{{ route('jobs.index') }}"
               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('jobs.*') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">Find jobs</a>
            <a href="{{ route('contact') }}"
               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('contact') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">Contact Us</a>
            <a href="{{ route('models') }}"
               class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('models') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">Models</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard*') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">Dashboard</a>
                @endif
                <span class="px-2 py-1 text-xs font-medium text-slate-300 bg-slate-700 rounded">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium text-slate-200 hover:bg-slate-800 hover:text-white">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('login') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">Login</a>
                <a href="{{ route('register') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('register') ? 'bg-slate-700 text-amber-400' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">Sign Up</a>
            @endauth
        </nav>
    </div>
</header>
