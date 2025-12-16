<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SNSU Library E-Request – Login</title>

    {{-- Tailwind + Alpine --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Background image behind everything */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: url('{{ asset('assets/images/library-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            opacity: 0.25;
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-[950px] bg-gradient-to-br from-green-200 to-green-300 rounded-[32px] shadow-2xl p-10 flex flex-col md:flex-row gap-10">

        {{-- LEFT SIDE --}}
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-extrabold text-center mb-8">
                SNSU LIBRARY E-REQUEST
            </h1>

            {{-- Error --}}
            @if($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded-full text-center">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf

                <input
                    type="text"
                    name="login"
                    value="{{ old('login') }}"
                    placeholder="Username or Email"
                    required
                    class="w-full px-5 py-3 rounded-full border-2 border-gray-300 focus:border-green-700 focus:ring-0"
                >

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="w-full px-5 py-3 rounded-full border-2 border-gray-300 focus:border-green-700 focus:ring-0"
                >

                <button
                    type="submit"
                    class="w-full py-3 bg-green-700 text-white rounded-full font-bold shadow hover:bg-green-800 transition">
                    Sign in
                </button>

                <div class="text-center text-gray-600 font-medium">or</div>

                <button type="button"
                    disabled
                    class="w-full py-3 bg-white border border-gray-300 rounded-full font-semibold flex items-center justify-center gap-2">
                    <span class="font-bold text-lg">G</span> Sign in with Google
                </button>
            </form>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="flex-1 text-right flex flex-col justify-center select-none">
            <h2 class="text-3xl md:text-4xl font-bold mb-2">Request</h2>
            <h2 class="text-6xl md:text-7xl italic font-bold text-gray-800 mb-2">Read</h2>
            <h2 class="text-4xl md:text-5xl font-bold">Learn</h2>
        </div>

    </div>

    {{-- FOOTER LOGOS --}}
    <div class="fixed bottom-5 right-5 flex items-center gap-3">
        <span class="italic font-semibold text-sm">For Nation's Greater Heights</span>

        <div class="w-12 h-12 rounded-full bg-white border border-gray-300 bg-center bg-cover"
             style="background-image: url('{{ asset('assets/images/snsu-logo.png') }}');"></div>

        <div class="w-12 h-12 rounded-full bg-white border border-gray-300 bg-center bg-cover"
             style="background-image: url('{{ asset('assets/images/library-logo.png') }}');"></div>
    </div>

</body>
</html>
