<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') – CAM Solutions</title>
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
        :root {
            --cam-ink: #0f2744;
            --cam-steel: #1a5f8a;
            --cam-sky: #2b7cb5;
            --cam-teal: #0f766e;
            --cam-mist: #eef4f9;
            --cam-line: #d5e3ef;
        }

        body {
            font-family: 'IBM Plex Sans', ui-sans-serif, system-ui, sans-serif;
            color: var(--cam-ink);
            background: var(--cam-mist);
        }

        h1, h2, h3, .font-display {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }

        .cam-surface {
            background: #fff;
            border: 1px solid var(--cam-line);
            box-shadow: 0 10px 30px rgba(15, 39, 68, 0.06);
        }

        .cam-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 0.75rem;
            background: var(--cam-steel);
            color: #fff;
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            transition: background 180ms ease, transform 180ms ease, box-shadow 180ms ease;
        }

        .cam-btn:hover {
            background: var(--cam-sky);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(26, 95, 138, 0.22);
        }

        .cam-btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 0.75rem;
            border: 1px solid var(--cam-line);
            background: #fff;
            color: var(--cam-ink);
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            transition: border-color 180ms ease, color 180ms ease, transform 180ms ease;
        }

        .cam-btn-ghost:hover {
            border-color: var(--cam-sky);
            color: var(--cam-steel);
            transform: translateY(-1px);
        }

        .reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 650ms cubic-bezier(0.16, 1, 0.3, 1), transform 650ms cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.is-in {
            opacity: 1;
            transform: none;
        }

        .float-soft {
            animation: floatSoft 5.5s ease-in-out infinite;
        }

        @keyframes floatSoft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .cam-bg-shapes {
            overflow: hidden;
        }

        .cam-bg-shapes .bubble,
        .cam-bg-shapes .block {
            position: absolute;
            pointer-events: none;
            will-change: transform;
        }

        .cam-bg-shapes .bubble {
            border-radius: 9999px;
            filter: blur(0.5px);
        }

        .cam-bg-shapes .block {
            border-radius: 1.25rem;
        }

        .bubble-a { width: 14rem; height: 14rem; top: 8%; left: -3%; background: rgba(43, 124, 181, 0.12); animation: driftA 18s ease-in-out infinite; }
        .bubble-b { width: 9rem; height: 9rem; top: 28%; right: 6%; background: rgba(15, 118, 110, 0.12); animation: driftB 14s ease-in-out infinite; }
        .bubble-c { width: 6rem; height: 6rem; bottom: 18%; left: 18%; background: rgba(26, 95, 138, 0.1); animation: driftC 16s ease-in-out infinite; }
        .bubble-d { width: 11rem; height: 11rem; bottom: 8%; right: -2%; background: rgba(56, 189, 248, 0.14); animation: driftA 20s ease-in-out infinite reverse; }
        .bubble-e { width: 4.5rem; height: 4.5rem; top: 55%; left: 48%; background: rgba(14, 165, 233, 0.16); animation: driftB 12s ease-in-out infinite; }

        .block-a { --r: 18deg; width: 7rem; height: 7rem; top: 12%; right: 18%; background: rgba(26, 95, 138, 0.08); border: 1px solid rgba(26, 95, 138, 0.12); animation: driftSpin 22s ease-in-out infinite; }
        .block-b { --r: -12deg; width: 5rem; height: 5rem; bottom: 22%; left: 8%; background: rgba(15, 118, 110, 0.1); border: 1px solid rgba(15, 118, 110, 0.14); animation: driftSpin 17s ease-in-out infinite; }
        .block-c { --r: 28deg; width: 3.5rem; height: 3.5rem; top: 42%; left: 62%; background: rgba(14, 165, 233, 0.14); border: 1px solid rgba(14, 165, 233, 0.18); animation: driftSpin 15s ease-in-out infinite; }
        .block-d { --r: -8deg; width: 9rem; height: 4rem; bottom: 12%; right: 28%; background: linear-gradient(135deg, rgba(26, 95, 138, 0.12), rgba(15, 118, 110, 0.1)); border: 1px solid rgba(26, 95, 138, 0.1); animation: driftSpin 19s ease-in-out infinite; }

        @keyframes driftA {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(18px, -22px); }
        }
        @keyframes driftB {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-16px, 18px); }
        }
        @keyframes driftC {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(12px, 14px); }
        }
        @keyframes driftSpin {
            0%, 100% { transform: translate(0, 0) rotate(var(--r, 12deg)); }
            50% { transform: translate(12px, 14px) rotate(var(--r, 12deg)); }
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
            .float-soft { animation: none; }
            .cam-bg-shapes .bubble,
            .cam-bg-shapes .block { animation: none !important; }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-cam-mist text-cam-ink antialiased min-h-screen flex flex-col relative">
    <div class="pointer-events-none fixed inset-0 -z-20 bg-[radial-gradient(ellipse_at_top,_rgba(43,124,181,0.12),_transparent_50%),radial-gradient(ellipse_at_bottom_right,_rgba(15,118,110,0.1),_transparent_45%),linear-gradient(180deg,#f8fbfd_0%,#eef4f9_55%,#eaf3f8_100%)]"></div>
    <div class="cam-bg-shapes pointer-events-none fixed inset-0 -z-10" aria-hidden="true">
        <span class="bubble bubble-a"></span>
        <span class="bubble bubble-b"></span>
        <span class="bubble bubble-c"></span>
        <span class="bubble bubble-d"></span>
        <span class="bubble bubble-e"></span>
        <span class="block block-a"></span>
        <span class="block block-b"></span>
        <span class="block block-c"></span>
        <span class="block block-d"></span>
    </div>

    @include('partials.header')

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="mt-auto border-t border-cam-line bg-white/90 py-8 backdrop-blur-sm">
        <div class="container mx-auto px-4 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} <span class="font-semibold text-cam-ink">CAM Solutions</span>. Learn CNC programming with Mastercam.
        </div>
    </footer>

    @include('partials.model-download-script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = document.querySelectorAll('.reveal');
            if (!items.length) return;
            if (!('IntersectionObserver' in window)) {
                items.forEach((el) => el.classList.add('is-in'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-in');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            items.forEach((el) => io.observe(el));
        });
    </script>
    @stack('scripts')
</body>

</html>
