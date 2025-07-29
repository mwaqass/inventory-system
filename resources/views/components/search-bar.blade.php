<div x-data="{
    query: '',
    isOpen: false,
    results: [],
    loading: false,
    async search() {
        if (this.query.length < 2) {
            this.results = [];
            return;
        }

        this.loading = true;
        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
            const data = await response.json();
            this.results = data.results || [];
        } catch (error) {
            console.error('Search error:', error);
            this.results = [];
        } finally {
            this.loading = false;
        }
    }
}" class="relative">

    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input
            x-model="query"
            @input.debounce.300ms="search()"
            @focus="isOpen = true"
            @click.away="isOpen = false"
            type="text"
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            placeholder="Search products, orders, suppliers..."
        >
        <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <!-- Search Results Dropdown -->
    <div x-show="isOpen && (results.length > 0 || loading)"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">

        <div class="py-2">
            <template x-for="result in results" :key="result.id">
                <a :href="result.url" class="block px-4 py-2 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900" x-text="result.title"></p>
                            <p class="text-xs text-gray-500" x-text="result.subtitle"></p>
                        </div>
                        <div class="ml-auto">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800" x-text="result.type"></span>
                        </div>
                    </div>
                </a>
            </template>

            <div x-show="results.length === 0 && !loading && query.length >= 2" class="px-4 py-2 text-sm text-gray-500">
                No results found for "<span x-text="query"></span>"
            </div>
        </div>
    </div>
</div>
