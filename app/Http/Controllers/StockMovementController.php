<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'warehouse', 'user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_type', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Warehouse filter
        if ($request->filled('warehouse')) {
            $query->where('warehouse_id', $request->warehouse);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stockMovements = $query->latest()->paginate(15);
        $warehouses = Warehouse::where('is_active', true)->get();

        return view('stock-movements.index', compact('stockMovements', 'warehouses'));
    }

    public function create()
    {
        $products = Product::active()->get();
        $warehouses = Warehouse::active()->get();

        return view('stock-movements.create', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $warehouse = Warehouse::findOrFail($validated['warehouse_id']);

        // Use the inventory service to handle the stock movement
        $inventoryService = app(\App\Services\InventoryService::class);

        if ($validated['type'] === 'in') {
            $inventoryService->addStock($product, $warehouse, $validated['quantity'], [
                'notes' => $validated['notes'],
            ]);
        } elseif ($validated['type'] === 'out') {
            $inventoryService->removeStock($product, $warehouse, $validated['quantity'], [
                'notes' => $validated['notes'],
            ]);
        } else {
            $inventoryService->adjustStock($product, $warehouse, $validated['quantity'], [
                'notes' => $validated['notes'],
            ]);
        }

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock movement created successfully.');
    }

    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product', 'warehouse', 'user']);

        return view('stock-movements.show', compact('stockMovement'));
    }
}
