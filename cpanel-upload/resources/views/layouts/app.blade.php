<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Home') – CAM Solution</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700" rel="stylesheet">
    @php
        $buildManifest = base_path('build/manifest.json');
        $buildHot = base_path('hot');
        $hasViteManifest = file_exists($buildManifest) || file_exists($buildHot);
        $assetUrl = rtrim(config('app.asset_url', config('app.url')), '/');
    @endphp
    @if ($hasViteManifest)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            // Fallback: try to load built CSS/JS directly if manifest doesn't exist
            $cssFiles = glob(base_path('build/assets/app-*.css'));
            $jsFiles = glob(base_path('build/assets/app-*.js'));
        @endphp
        @if (!empty($cssFiles))
            @foreach ($cssFiles as $cssFile)
                <link rel="stylesheet" href="{{ $assetUrl }}/build/assets/{{ basename($cssFile) }}">
            @endforeach
        @endif
        @if (!empty($jsFiles))
            @foreach ($jsFiles as $jsFile)
                <script src="{{ $assetUrl }}/build/assets/{{ basename($jsFile) }}" defer></script>
            @endforeach
        @endif
        @if (empty($cssFiles))
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css">
        @endif
    @endif
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
