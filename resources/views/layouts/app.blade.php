<!DOCTYPE html>
<html lang="en" x-data>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SNSU Library e-Request')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="min-h-screen bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col">
        {{-- Optional global navbar --}}
        @includeWhen(View::exists('partials.navbar'), 'partials.navbar')

        <main class="flex-1">
            @yield('content')
        </main>

        {{-- Global footer (we'll create this file) --}}
        @includeWhen(View::exists('partials.footer'), 'partials.footer')
    </div>

    @stack('scripts')
</body>
</html>
