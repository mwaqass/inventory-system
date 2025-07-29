<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Inventory System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="bg-white rounded-full p-4 shadow-lg">
                    <svg class="w-12 h-12 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-center text-gray-900 mt-4">Inventory System</h1>
            <p class="text-center text-gray-600 mt-2">Manage your inventory efficiently</p>
        </div>

        <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-2xl rounded-2xl border border-gray-100">
            {{ $slot }}
        </div>

        <div class="mt-8 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} Inventory System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
