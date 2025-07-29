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
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-white to-purple-50">
            <!-- Background decoration -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                <div class="absolute top-40 left-40 w-80 h-80 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            </div>

            <!-- Logo and content -->
            <div class="relative z-10 w-full sm:max-w-md px-6 py-8 bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-white/20">
                <div class="text-center mb-8">
                    <a href="/" class="inline-block">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Inventory System</h2>
                        <p class="text-gray-600 mt-1">Manage your inventory efficiently</p>
                    </a>
                </div>

                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="relative z-10 mt-8 text-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} Inventory System. All rights reserved.
                </p>
            </div>
        </div>

        <style>
            @keyframes blob {
                0% {
                    transform: translate(0px, 0px) scale(1);
                }
                33% {
                    transform: translate(30px, -50px) scale(1.1);
                }
                66% {
                    transform: translate(-20px, 20px) scale(0.9);
                }
                100% {
                    transform: translate(0px, 0px) scale(1);
                }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    </body>
</html>
