<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – Admin – CAM Solutions</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|ibm-plex-sans:400,500,600,700" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cam: {
                            ink: '#0f2744',
                            steel: '#1a5f8a',
                            sky: '#2b7cb5',
                            teal: '#0f766e',
                            mist: '#eef4f9',
                            line: '#d5e3ef',
                        },
                    },
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        sans: ['"IBM Plex Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'IBM Plex Sans', ui-sans-serif, system-ui, sans-serif;
            color: #0f2744;
            background: #eef4f9;
        }
        h1, h2, h3, .font-display {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-cam-mist text-cam-ink antialiased min-h-screen">
    <div class="flex min-h-screen">
        <aside class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-cam-line bg-white text-cam-ink shadow-[4px_0_24px_rgba(15,39,68,0.04)]">
            <div class="flex h-16 items-center gap-2.5 border-b border-cam-line px-4">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-cam-steel text-sm font-bold text-white">CS</span>
                <span class="font-display text-lg font-bold tracking-tight">CAM Solutions</span>
            </div>
            <span class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Admin</span>
            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-2">
                @php
                    $active = 'bg-sky-50 text-cam-steel ring-1 ring-sky-100';
                    $idle = 'text-slate-600 hover:bg-slate-50 hover:text-cam-ink';
                @endphp
                @if(auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    Videos
                </a>
                <a href="{{ route('dashboard.categories.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard.categories*') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    Categories
                </a>
                <a href="{{ route('dashboard.media.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard.media*') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    Media Library
                </a>
                <a href="{{ route('dashboard.certificates.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard.certificates*') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    Certificates
                </a>
                <a href="{{ route('dashboard.slider.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard.slider*') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Slider
                </a>
                <a href="{{ route('dashboard.contact-settings.edit') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard.contact-settings*') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Contact details
                </a>
                <a href="{{ route('dashboard.contact-messages.index') }}"
                    class="flex items-center justify-between gap-2 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard.contact-messages*') ? $active : $idle }}">
                    <span class="flex min-w-0 items-center gap-3">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        <span class="truncate">Messages</span>
                    </span>
                    @if (($unread_contact_messages_count ?? 0) > 0)
                    <span class="flex-shrink-0 rounded-full bg-cam-steel px-1.5 py-0.5 text-[10px] font-bold leading-none text-white tabular-nums">{{ $unread_contact_messages_count }}</span>
                    @endif
                </a>
                <a href="{{ route('dashboard.jobs.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard.jobs*') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Jobs
                </a>
                <a href="{{ route('dashboard.job-applications.index') }}"
                    class="flex items-center justify-between gap-2 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard.job-applications*') ? $active : $idle }}">
                    <span class="flex min-w-0 items-center gap-3">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="truncate">Applications</span>
                    </span>
                    @if (($unread_job_applications_count ?? 0) > 0)
                    <span class="flex-shrink-0 rounded-full bg-teal-600 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white tabular-nums">{{ $unread_job_applications_count }}</span>
                    @endif
                </a>
                @endif
                <a href="{{ route('meetings.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('meetings*') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    Meetings
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('users*') ? $active : $idle }}">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Users
                </a>
                @endif
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-cam-ink">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    View Site
                </a>
            </nav>
            <div class="border-t border-cam-line p-3">
                <div class="rounded-xl bg-cam-mist px-3 py-2 text-sm text-slate-600">
                    {{ auth()->user()->name }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-500 transition hover:bg-slate-50 hover:text-cam-ink">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex flex-1 flex-col pl-64">
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-cam-line bg-white/90 px-6 shadow-sm backdrop-blur">
                <h1 class="font-display text-xl font-semibold text-cam-ink">@yield('page-heading', 'Dashboard')</h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <main class="flex min-h-0 flex-1 flex-col p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
