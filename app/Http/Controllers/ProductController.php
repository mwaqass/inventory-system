<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'warehouses'])
            ->withCount('warehouses as total_quantity');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Warehouse filter
        if ($request->filled('warehouse')) {
            $query->whereHas('warehouses', function ($q) use ($request) {
                $q->where('warehouse_id', $request->warehouse);
            });
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();

        return view('products.create', compact('categories', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:100',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'initial_quantity' => 'nullable|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'is_active' => 'boolean',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'selling_price' => $request->selling_price,
            'cost_price' => $request->cost_price,
            'reorder_point' => $request->reorder_point ?? 10,
            'is_active' => $request->has('is_active'),
        ]);

        // Add initial stock to warehouse if specified
        if ($request->filled('warehouse_id') && $request->initial_quantity > 0) {
            $product->warehouses()->attach($request->warehouse_id, [
                'quantity_on_hand' => $request->initial_quantity,
                'quantity_available' => $request->initial_quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'warehouses']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();

        return view('products.edit', compact('product', 'categories', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id . '|max:100',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'selling_price' => $request->selling_price,
            'cost_price' => $request->cost_price,
            'reorder_point' => $request->reorder_point ?? 10,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Adjust stock for a product
     */
    public function adjustStock(Request $request, Product $product)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer',
            'type' => 'required|in:in,out,adjustment',
            'reason' => 'nullable|string',
        ]);

        // Handle stock adjustment logic here
        // This would typically involve creating a stock movement record

        return redirect()->back()->with('success', 'Stock adjusted successfully.');
    }

    /**
     * Transfer stock between warehouses
     */
    public function transferStock(Request $request, Product $product)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Handle stock transfer logic here
        // This would typically involve creating stock movement records

        return redirect()->back()->with('success', 'Stock transferred successfully.');
    }
}
