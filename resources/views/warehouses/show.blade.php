@extends('layouts.app')

@section('title', 'Warehouse Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Warehouse Details</h1>
            <p class="text-gray-600 mt-1">View detailed information about this warehouse</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('warehouses.edit', $warehouse) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Warehouse
            </a>
            <a href="{{ route('warehouses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Warehouses
            </a>
        </div>
    </div>

    <!-- Warehouse Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">{{ $warehouse->name }}</h3>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $warehouse->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Basic Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Warehouse Name</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Warehouse Code</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->code }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Manager</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->manager ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Capacity</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->capacity ? number_format($warehouse->capacity) . ' units' : 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Contact Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Phone</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Email</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->email ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Status</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->is_active ? 'Active' : 'Inactive' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Created At</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Address Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Address</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->address ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">City</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->city ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">State/Province</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->state ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Postal Code</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->postal_code ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Country</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->country ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Statistics</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Products Stored</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->products->count() }} products</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Total Stock</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->products->sum('pivot.quantity') ?? 0 }} units</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($warehouse->notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Notes</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900">{{ $warehouse->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Products -->
            @if($warehouse->products->count() > 0)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Products in this Warehouse</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($warehouse->products->take(10) as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->sku }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->pivot->quantity ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($warehouse->products->count() > 10)
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">Showing 10 of {{ $warehouse->products->count() }} products</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
