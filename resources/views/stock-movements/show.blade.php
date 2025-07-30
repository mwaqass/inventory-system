@extends('layouts.app')

@section('title', 'Stock Movement Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Stock Movement Details</h1>
            <p class="text-gray-600 mt-1">View detailed information about this movement</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('stock-movements.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Movements
            </a>
        </div>
    </div>

    <!-- Movement Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Movement #{{ $stockMovement->id }}</h3>
                @php
                    $typeColors = [
                        'in' => 'bg-green-100 text-green-800',
                        'out' => 'bg-red-100 text-red-800',
                        'transfer' => 'bg-blue-100 text-blue-800',
                        'adjustment' => 'bg-yellow-100 text-yellow-800',
                    ];
                    $color = $typeColors[$stockMovement->type] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $color }}">
                    {{ ucfirst($stockMovement->type) }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Product Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Product Name</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->product->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">SKU</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->product->sku }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Category</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->product->category->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Movement Details -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Movement Details</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Quantity</label>
                            <p class="text-sm font-medium {{ $stockMovement->type === 'out' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $stockMovement->type === 'out' ? '-' : '+' }}{{ $stockMovement->quantity }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Warehouse</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->warehouse->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Movement Type</label>
                            <p class="text-sm text-gray-900">{{ ucfirst($stockMovement->type) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Reference Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Reference Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Reference Type</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->reference_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Reference ID</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->reference_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Created By</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->user->name ?? 'System' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timing Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Timing Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Created At</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Updated At</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Quantity Before</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->quantity_before ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Quantity After</label>
                            <p class="text-sm text-gray-900">{{ $stockMovement->quantity_after ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($stockMovement->notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Notes</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900">{{ $stockMovement->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
