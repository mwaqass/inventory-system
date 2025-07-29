@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Product Details</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Product
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Product Information -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Product Name</dt>
                                <dd class="text-sm text-gray-900">{{ $product->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                <dd class="text-sm text-gray-900">{{ $product->sku }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Category</dt>
                                <dd class="text-sm text-gray-900">{{ $product->category->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="text-sm text-gray-900">{{ $product->description ?: 'No description available' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Pricing Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing & Stock</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Selling Price</dt>
                                <dd class="text-sm text-gray-900">${{ number_format($product->selling_price, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                                <dd class="text-sm text-gray-900">${{ number_format($product->cost_price ?? 0, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Stock</dt>
                                <dd class="text-sm text-gray-900">{{ $product->total_quantity ?? 0 }} units</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Reorder Point</dt>
                                <dd class="text-sm text-gray-900">{{ $product->reorder_point ?? 0 }} units</dd>
                            </div>
                            @if($product->cost_price > 0)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($product->profit_margin, 1) }}%</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock by Warehouse -->
        @if($product->warehouses->count() > 0)
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stock by Warehouse</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warehouse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity on Hand</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reserved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($product->warehouses as $warehouse)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $warehouse->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $warehouse->pivot->quantity_on_hand ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $warehouse->pivot->quantity_available ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $warehouse->pivot->quantity_reserved ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(($warehouse->pivot->quantity_on_hand ?? 0) <= $product->reorder_point)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Low Stock</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Stock Movements -->
        @if($product->stockMovements->count() > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Stock Movements</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warehouse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($product->stockMovements->take(10) as $movement)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' :
                                           ($movement->type === 'out' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($movement->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->warehouse->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->notes ?: '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
