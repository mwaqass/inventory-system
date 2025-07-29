<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Supplier filter
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        $purchaseOrders = $query->latest()->paginate(15);
        $suppliers = Supplier::where('is_active', true)->get();

        return view('purchase-orders.index', compact('purchaseOrders', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->get();
        $products = Product::active()->get();

        return view('purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'po_number' => 'PO-' . strtoupper(uniqid()),
            'supplier_id' => $validated['supplier_id'],
            'order_date' => $validated['order_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'notes' => $validated['notes'],
            'user_id' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $purchaseOrder->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'total_cost' => $item['quantity'] * $item['unit_cost'],
            ]);
        }

        $purchaseOrder->calculateTotals();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'user', 'items.product']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::active()->get();
        $products = Product::active()->get();
        $purchaseOrder->load('items.product');

        return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'notes' => 'nullable|string',
        ]);

        $purchaseOrder->update($validated);

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order updated successfully.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order deleted successfully.');
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,confirmed,received,cancelled',
        ]);

        $purchaseOrder->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Purchase order status updated successfully.');
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'received_items' => 'required|array',
            'received_items.*.item_id' => 'required|exists:purchase_order_items,id',
            'received_items.*.quantity' => 'required|integer|min:1',
        ]);

        $inventoryService = app(\App\Services\InventoryService::class);

        foreach ($validated['received_items'] as $receivedItem) {
            $item = $purchaseOrder->items()->findOrFail($receivedItem['item_id']);
            $quantity = $receivedItem['quantity'];

            // Update received quantity
            $item->update([
                'received_quantity' => $item->received_quantity + $quantity,
            ]);

            // Add stock to warehouse (assuming default warehouse for now)
            $warehouse = Warehouse::first();
            if ($warehouse) {
                $inventoryService->addStock($item->product, $warehouse, $quantity, [
                    'reference_type' => PurchaseOrder::class,
                    'reference_id' => $purchaseOrder->id,
                    'notes' => "Received from PO {$purchaseOrder->po_number}",
                ]);
            }
        }

        // Check if all items are received
        $allReceived = $purchaseOrder->items()->where('received_quantity', '<', DB::raw('quantity'))->count() === 0;

        if ($allReceived) {
            $purchaseOrder->update([
                'status' => 'received',
                'actual_delivery_date' => now(),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Items received successfully.');
    }
}
