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
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main content -->
        <div class="lg:ml-72">
            <!-- Top header -->
            <div class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center space-x-4">
                        <h1 class="text-xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                        <div class="hidden md:block w-64">
                            <x-search-bar />
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-500">{{ date('F j, Y') }}</div>
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
