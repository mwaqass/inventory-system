@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Create Stock Movement</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('stock-movements.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Stock Movements
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('stock-movements.store') }}" class="space-y-6">
                @csrf

                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Product Selection -->
                        <div class="space-y-6">
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700">Product *</label>
                                <select name="product_id" id="product_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-stock="{{ $product->total_quantity ?? 0 }}">
                                            {{ $product->name }} ({{ $product->sku }}) - Stock: {{ $product->total_quantity ?? 0 }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Warehouse *</label>
                                <select name="warehouse_id" id="warehouse_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">
                                            {{ $warehouse->name }} ({{ $warehouse->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Movement Type *</label>
                                <select name="type" id="type" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Type</option>
                                    <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                                    <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                                    <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Movement Details -->
                        <div class="space-y-6">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reference_type" class="block text-sm font-medium text-gray-700">Reference Type</label>
                                <select name="reference_type" id="reference_type"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">No Reference</option>
                                    <option value="purchase_order" {{ old('reference_type') == 'purchase_order' ? 'selected' : '' }}>Purchase Order</option>
                                    <option value="sales_order" {{ old('reference_type') == 'sales_order' ? 'selected' : '' }}>Sales Order</option>
                                    <option value="return" {{ old('reference_type') == 'return' ? 'selected' : '' }}>Return</option>
                                    <option value="adjustment" {{ old('reference_type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                                    <option value="transfer" {{ old('reference_type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                                @error('reference_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reference_id" class="block text-sm font-medium text-gray-700">Reference ID</label>
                                <input type="text" name="reference_id" id="reference_id" value="{{ old('reference_id') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">ID of the related document (PO, SO, etc.)</p>
                                @error('reference_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Enter any additional notes about this stock movement...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Information Display -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Current Stock Information</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Selected Product</dt>
                                <dd class="text-sm text-gray-900" id="selected-product">-</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Current Stock</dt>
                                <dd class="text-sm text-gray-900" id="current-stock">-</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500">After Movement</dt>
                                <dd class="text-sm text-gray-900" id="after-movement">-</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Stock Movement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const typeSelect = document.getElementById('type');
    const quantityInput = document.getElementById('quantity');
    const selectedProduct = document.getElementById('selected-product');
    const currentStock = document.getElementById('current-stock');
    const afterMovement = document.getElementById('after-movement');

    function updateStockInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const type = typeSelect.value;
        const quantity = parseInt(quantityInput.value) || 0;

        if (selectedOption && selectedOption.value) {
            const productName = selectedOption.text.split(' (')[0];
            const stock = parseInt(selectedOption.dataset.stock) || 0;

            selectedProduct.textContent = productName;
            currentStock.textContent = stock;

            let afterStock = stock;
            if (type === 'in') {
                afterStock = stock + quantity;
            } else if (type === 'out') {
                afterStock = stock - quantity;
            }

            afterMovement.textContent = afterStock;

            // Color coding
            if (type === 'out' && afterStock < 0) {
                afterMovement.className = 'text-sm text-red-600 font-semibold';
            } else if (type === 'in') {
                afterMovement.className = 'text-sm text-green-600 font-semibold';
            } else {
                afterMovement.className = 'text-sm text-gray-900';
            }
        } else {
            selectedProduct.textContent = '-';
            currentStock.textContent = '-';
            afterMovement.textContent = '-';
        }
    }

    productSelect.addEventListener('change', updateStockInfo);
    typeSelect.addEventListener('change', updateStockInfo);
    quantityInput.addEventListener('input', updateStockInfo);
});
</script>
@endsection
