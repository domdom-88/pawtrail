<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pawtrail — Walk. Share. Connect.</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-b from-orange-50 to-white min-h-screen flex items-center justify-center">

    <div class="text-center px-6 py-12 max-w-md">
        <img src="{{ asset('images/pawtrail-logo.png') }}" alt="Pawtrail" class="mx-auto w-56 mb-6">

        <p class="text-gray-600 mb-8">
            Log every walk, pin every spot, and share the trail with fellow dog owners.
        </p>

        <div class="flex justify-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-gray-800 text-white px-6 py-2 rounded-md font-medium hover:bg-gray-700">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-gray-800 text-white px-6 py-2 rounded-md font-medium hover:bg-gray-700">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="border border-gray-800 text-gray-800 px-6 py-2 rounded-md font-medium hover:bg-gray-100">
                    Register
                </a>
            @endauth
        </div>
    </div>

</body>
</html>