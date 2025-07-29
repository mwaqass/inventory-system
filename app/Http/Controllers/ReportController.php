<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function inventory()
    {
        $products = Product::with(['category', 'supplier'])
            ->withSum('warehouses as total_quantity', 'product_warehouse.quantity_on_hand')
            ->orderBy('total_quantity', 'desc')
            ->paginate(50);

        $totalValue = $this->inventoryService->getTotalInventoryValue();
        $inventoryByWarehouse = $this->inventoryService->getInventoryValueByWarehouse();

        return view('reports.inventory', compact('products', 'totalValue', 'inventoryByWarehouse'));
    }

    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-d', strtotime('-1 month')));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['items.product'])
            ->get();

        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        return view('reports.sales', compact(
            'orders',
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }

    public function purchases(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-d', strtotime('-1 month')));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $purchaseOrders = PurchaseOrder::whereBetween('created_at', [$startDate, $endDate])
            ->with(['supplier', 'items.product'])
            ->get();

        $totalPurchases = $purchaseOrders->sum('total_amount');
        $totalOrders = $purchaseOrders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalPurchases / $totalOrders : 0;

        $topSuppliers = DB::table('purchase_orders')
            ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
            ->whereBetween('purchase_orders.created_at', [$startDate, $endDate])
            ->select(
                'suppliers.name',
                DB::raw('COUNT(purchase_orders.id) as order_count'),
                DB::raw('SUM(purchase_orders.total_amount) as total_amount')
            )
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get();

        return view('reports.purchases', compact(
            'purchaseOrders',
            'totalPurchases',
            'totalOrders',
            'averageOrderValue',
            'topSuppliers',
            'startDate',
            'endDate'
        ));
    }

    public function lowStock()
    {
        $lowStockProducts = $this->inventoryService->getLowStockProducts();

        $reorderSuggestions = $lowStockProducts->map(function ($product) {
            return [
                'product' => $product,
                'suggested_quantity' => $product->reorder_quantity,
                'estimated_cost' => $product->reorder_quantity * $product->cost_price,
            ];
        });

        $totalEstimatedCost = $reorderSuggestions->sum('estimated_cost');

        return view('reports.low-stock', compact('lowStockProducts', 'reorderSuggestions', 'totalEstimatedCost'));
    }

    public function stockMovements(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-d', strtotime('-1 month')));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $movements = StockMovement::whereBetween('created_at', [$startDate, $endDate])
            ->with(['product', 'warehouse', 'user'])
            ->latest()
            ->paginate(50);

        $movementSummary = DB::table('stock_movements')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('type')
            ->get();

        return view('reports.stock-movements', compact('movements', 'movementSummary', 'startDate', 'endDate'));
    }
}
