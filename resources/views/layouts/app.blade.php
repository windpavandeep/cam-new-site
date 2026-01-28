<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Home') – CAM Solution</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css">
    <style>
        body { font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
    @include('partials.header')

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-slate-800 text-slate-300 py-6 mt-auto">
        <div class="container mx-auto px-4 text-center text-sm">
            &copy; {{ date('Y') }} CAM Solution. Learn CNC Programming with Mastercam.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
