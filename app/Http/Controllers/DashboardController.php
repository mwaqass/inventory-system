<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get low stock alerts
        $lowStockProducts = $this->inventoryService->getLowStockProducts();

        // Get recent orders
        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent purchase orders
        $recentPurchaseOrders = PurchaseOrder::with(['supplier', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Get inventory value by warehouse
        $inventoryByWarehouse = $this->inventoryService->getInventoryValueByWarehouse();

        // Get top products by stock value
        $topProducts = $this->getTopProductsByValue();

        return view('dashboard', compact(
            'stats',
            'recentActivities',
            'lowStockProducts',
            'recentOrders',
            'recentPurchaseOrders',
            'inventoryByWarehouse',
            'topProducts'
        ));
    }

    private function getDashboardStats()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalPurchaseOrders = PurchaseOrder::count();
        $pendingPurchaseOrders = PurchaseOrder::where('status', 'draft')->count();
        $totalWarehouses = Warehouse::count();
        $totalInventoryValue = $this->inventoryService->getTotalInventoryValue();

        // Get today's stats
        $today = date('Y-m-d 00:00:00');
        $todayOrders = Order::where('created_at', '>=', $today)->count();
        $todayStockMovements = StockMovement::where('created_at', '>=', $today)->count();

        return [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'total_purchase_orders' => $totalPurchaseOrders,
            'pending_purchase_orders' => $pendingPurchaseOrders,
            'total_warehouses' => $totalWarehouses,
            'total_inventory_value' => $totalInventoryValue,
            'today_orders' => $todayOrders,
            'today_stock_movements' => $todayStockMovements,
        ];
    }

    private function getRecentActivities()
    {
        return StockMovement::with(['product', 'warehouse', 'user'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($movement) {
                return [
                    'id' => $movement->id,
                    'type' => $movement->type,
                    'description' => $movement->description,
                    'product_name' => $movement->product->name,
                    'warehouse_name' => $movement->warehouse->name,
                    'quantity' => $movement->quantity,
                    'user_name' => $movement->user->name,
                    'created_at' => $movement->created_at->diffForHumans(),
                ];
            });
    }

    private function getTopProductsByValue()
    {
        return DB::table('product_warehouse')
            ->join('products', 'product_warehouse.product_id', '=', 'products.id')
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(product_warehouse.quantity_on_hand) as total_quantity'),
                DB::raw('SUM(product_warehouse.quantity_on_hand * products.cost_price) as total_value')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_value', 'desc')
            ->limit(10)
            ->get();
    }

    public function getChartData()
    {
        // Get stock movements for the last 30 days
        $stockMovements = StockMovement::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            'type'
        )
        ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
        ->groupBy('date', 'type')
        ->get()
        ->groupBy('date');

        // Get orders for the last 30 days
        $orders = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as total_amount')
        )
        ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
        ->groupBy('date')
        ->get();

        return response()->json([
            'stock_movements' => $stockMovements,
            'orders' => $orders,
        ]);
    }
}
