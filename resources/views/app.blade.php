<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

        <title inertia>{{ config('app.name', 'Sapa BK') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#1e3a8a">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Sapa BK">
        <meta name="application-name" content="Sapa BK">
        <meta name="msapplication-TileColor" content="#1e3a8a">
        <meta name="msapplication-tap-highlight" content="no">

        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">

        <!-- Favicon & Icons -->
        <link rel="icon" type="image/png" sizes="96x96" href="/icons/favicon-96x96.png">
        <link rel="icon" type="image/x-icon" href="/icons/favicon.ico">
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((registration) => {
                            console.log('SW registered:', registration.scope);
                        })
                        .catch((error) => {
                            console.log('SW registration failed:', error);
                        });
                });
            }
        </script>
    </body>
</html>
