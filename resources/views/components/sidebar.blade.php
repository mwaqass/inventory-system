<div x-data="{ sidebarOpen: false }" class="relative">
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-800 to-blue-900 transform lg:translate-x-0"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">

        <!-- Sidebar header -->
        <div class="flex items-center justify-between h-16 px-6 bg-blue-900">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <h1 class="text-xl font-bold text-white">Inventory System</h1>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation menu -->
        <nav class="mt-8 px-4 space-y-2">
            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('products.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('products.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Products
            </a>

            <a href="{{ route('orders.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('orders.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('orders.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Orders
            </a>

            <a href="{{ route('purchase-orders.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('purchase-orders.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('purchase-orders.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Purchase Orders
            </a>

            <a href="{{ route('stock-movements.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('stock-movements.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('stock-movements.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Stock Movements
            </a>

            <a href="{{ route('categories.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('categories.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Categories
            </a>

            <a href="{{ route('suppliers.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('suppliers.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('suppliers.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Suppliers
            </a>

            <a href="{{ route('warehouses.index') }}"
               class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('warehouses.*') ? 'bg-blue-700 text-white shadow-lg' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('warehouses.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Warehouses
            </a>
        </nav>

        <!-- User section -->
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <div class="bg-blue-800 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-blue-200">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="ml-auto">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-blue-200 hover:text-white transition-colors duration-200">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button @click="sidebarOpen = true" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 bg-white shadow-lg">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</div>
