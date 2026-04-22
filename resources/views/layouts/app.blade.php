<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') – CAM Solutions</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700" rel="stylesheet">
    <!-- Tailwind CDN (no build step) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif;
        }
        body.mech-theme {
            background-color: #020617;
            color: #e2e8f0;
        }
        .mech-theme .bg-white {
            background-color: rgba(15, 23, 42, 0.86) !important;
            border-color: rgba(148, 163, 184, 0.28) !important;
        }
        .mech-theme .bg-slate-50,
        .mech-theme .bg-slate-100 {
            background-color: rgba(15, 23, 42, 0.75) !important;
        }
        .mech-theme .text-slate-800,
        .mech-theme .text-slate-700 {
            color: #e2e8f0 !important;
        }
        .mech-theme .text-slate-600,
        .mech-theme .text-slate-500,
        .mech-theme .text-slate-400 {
            color: #94a3b8 !important;
        }
        .mech-theme .border-slate-200,
        .mech-theme .border-slate-300 {
            border-color: rgba(148, 163, 184, 0.3) !important;
        }
        .mech-theme input,
        .mech-theme select,
        .mech-theme textarea {
            background-color: rgba(15, 23, 42, 0.9) !important;
            color: #e2e8f0 !important;
            border-color: rgba(148, 163, 184, 0.35) !important;
        }
        .mech-theme .bg-amber-50 {
            background-color: rgba(217, 119, 6, 0.2) !important;
        }
    </style>
    @stack('styles')
</head>

<body class="mech-theme bg-slate-950 text-slate-100 antialiased min-h-screen flex flex-col relative">
    <div class="pointer-events-none fixed inset-0 -z-20 opacity-30 bg-center bg-cover bg-fixed" style="background-image: url('{{ asset('images/bg.png') }}');"></div>
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top,rgba(56,189,248,0.12),transparent_45%),linear-gradient(160deg,rgba(2,6,23,0.86),rgba(15,23,42,0.9)_45%,rgba(3,7,18,0.95))]"></div>
    @include('partials.header')

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-slate-950/90 text-slate-300 py-6 mt-auto border-t border-slate-700/60 backdrop-blur-sm">
        <div class="container mx-auto px-4 text-center text-sm">
            &copy; {{ date('Y') }} CAM Solutions. Learn CNC Programming with Mastercam.
        </div>
    </footer>

    @include('partials.model-download-script')
    @stack('scripts')
</body>

</html>