@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Create New Order</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
                @csrf

                <div class="px-4 py-5 sm:p-6">
                    <!-- Customer Information -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name *</label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('customer_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700">Email *</label>
                                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" required
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('customer_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('customer_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping & Billing</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address *</label>
                                    <textarea name="shipping_address" id="shipping_address" rows="3" required
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                                    <textarea name="billing_address" id="billing_address" rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('billing_address') }}</textarea>
                                    @error('billing_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                        <div id="order-items" class="space-y-4">
                            <div class="order-item bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Product *</label>
                                        <select name="items[0][product_id]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                                    {{ $product->name }} - ${{ number_format($product->selling_price, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                        <input type="number" name="items[0][quantity]" min="1" value="1" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Price *</label>
                                        <input type="number" name="items[0][price]" step="0.01" min="0" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" class="remove-item text-red-600 hover:text-red-800">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-item" class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <!-- Order Status -->
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 1;

    // Add item functionality
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('order-items');
        const newItem = document.querySelector('.order-item').cloneNode(true);

        // Update the indices
        newItem.querySelectorAll('select, input').forEach(input => {
            input.name = input.name.replace('[0]', `[${itemCount}]`);
            input.value = '';
        });

        container.appendChild(newItem);
        itemCount++;
    });

    // Remove item functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const items = document.querySelectorAll('.order-item');
            if (items.length > 1) {
                e.target.closest('.order-item').remove();
            }
        }
    });

    // Auto-fill price when product is selected
    document.addEventListener('change', function(e) {
        if (e.target.name && e.target.name.includes('[product_id]')) {
            const option = e.target.options[e.target.selectedIndex];
            const price = option.dataset.price;
            const priceInput = e.target.closest('.order-item').querySelector('input[name*="[price]"]');
            if (price && priceInput) {
                priceInput.value = price;
            }
        }
    });
});
</script>
@endsection
