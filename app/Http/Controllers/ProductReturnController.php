<?php

namespace App\Http\Controllers;

use App\Models\ProductReturn;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReturnController extends Controller
{
    public function index()
    {
        $returns = ProductReturn::with(['order', 'user'])
            ->latest()
            ->paginate(20);

        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        $orders = Order::where('status', 'delivered')->get();
        $products = Product::active()->get();

        return view('returns.create', compact('orders', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'type' => 'required|in:return,exchange,refund',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $return = ProductReturn::create([
            'return_number' => 'RET-' . strtoupper(uniqid()),
            'order_id' => $validated['order_id'],
            'type' => $validated['type'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'user_id' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $return->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $return->calculateTotals();

        return redirect()->route('returns.index')
            ->with('success', 'Return created successfully.');
    }

    public function show(ProductReturn $return)
    {
        $return->load(['order', 'user', 'items.product']);

        return view('returns.show', compact('return'));
    }

    public function edit(ProductReturn $return)
    {
        $orders = Order::where('status', 'delivered')->get();
        $products = Product::active()->get();
        $return->load('items.product');

        return view('returns.edit', compact('return', 'orders', 'products'));
    }

    public function update(Request $request, ProductReturn $return)
    {
        $validated = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'type' => 'required|in:return,exchange,refund',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $return->update($validated);

        return redirect()->route('returns.show', $return)
            ->with('success', 'Return updated successfully.');
    }

    public function destroy(ProductReturn $return)
    {
        $return->delete();

        return redirect()->route('returns.index')
            ->with('success', 'Return deleted successfully.');
    }

    public function updateStatus(Request $request, ProductReturn $return)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,received,processed,cancelled',
        ]);

        $return->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Return status updated successfully.');
    }
}
