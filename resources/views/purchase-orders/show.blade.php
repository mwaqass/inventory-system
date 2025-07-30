@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Purchase Order Details</h1>
            <p class="text-gray-600 mt-1">View detailed information about this purchase order</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Purchase Order
            </a>
            <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Purchase Orders
            </a>
        </div>
    </div>

    <!-- Purchase Order Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">{{ $purchaseOrder->po_number }}</h3>
                @php
                    $statusColors = [
                        'draft' => 'bg-gray-100 text-gray-800',
                        'sent' => 'bg-blue-100 text-blue-800',
                        'confirmed' => 'bg-yellow-100 text-yellow-800',
                        'received' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $color = $statusColors[$purchaseOrder->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $color }}">
                    {{ ucfirst($purchaseOrder->status) }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Supplier Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Supplier Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Supplier Name</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->supplier->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Email</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->supplier->email }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Phone</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->supplier->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Order Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">PO Number</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->po_number }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Status</label>
                            <p class="text-sm text-gray-900">{{ ucfirst($purchaseOrder->status) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Total Amount</label>
                            <p class="text-sm font-medium text-gray-900">${{ number_format($purchaseOrder->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Created By</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->user->name ?? 'System' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Important Dates</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Order Date</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->order_date->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Expected Delivery</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('M j, Y') : 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Actual Delivery</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->actual_delivery_date ? $purchaseOrder->actual_delivery_date->format('M j, Y') : 'Not delivered' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Created At</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Additional Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500">Items Count</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->items->count() }} items</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Last Updated</label>
                            <p class="text-sm text-gray-900">{{ $purchaseOrder->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($purchaseOrder->notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Notes</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900">{{ $purchaseOrder->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Purchase Order Items -->
            @if($purchaseOrder->items->count() > 0)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Order Items</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Received</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($purchaseOrder->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->product->sku }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($item->unit_cost, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($item->total_cost, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->received_quantity ?? 0 }} / {{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($purchaseOrder->total_amount, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
