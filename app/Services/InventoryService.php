<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryService
{
    /**
     * Add stock to a product in a specific warehouse
     */
    public function addStock(Product $product, Warehouse $warehouse, int $quantity, array $options = [])
    {
        return DB::transaction(function () use ($product, $warehouse, $quantity, $options) {
            // Get current stock level
            $stockLevel = $this->getStockLevel($product, $warehouse);
            $quantityBefore = $stockLevel->quantity_on_hand ?? 0;
            $quantityAfter = $quantityBefore + $quantity;

            // Update stock level
            $this->updateStockLevel($product, $warehouse, [
                'quantity_on_hand' => $quantityAfter,
                'quantity_available' => $quantityAfter - ($stockLevel->quantity_reserved ?? 0),
            ]);

            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => $options['reference_type'] ?? null,
                'reference_id' => $options['reference_id'] ?? null,
                'batch_number' => $options['batch_number'] ?? null,
                'serial_number' => $options['serial_number'] ?? null,
                'notes' => $options['notes'] ?? null,
            ]);

            return $quantityAfter;
        });
    }

    /**
     * Remove stock from a product in a specific warehouse
     */
    public function removeStock(Product $product, Warehouse $warehouse, int $quantity, array $options = [])
    {
        return DB::transaction(function () use ($product, $warehouse, $quantity, $options) {
            // Get current stock level
            $stockLevel = $this->getStockLevel($product, $warehouse);
            $quantityBefore = $stockLevel->quantity_on_hand ?? 0;
            $quantityAfter = $quantityBefore - $quantity;

            // Check if we have enough stock
            if ($quantityAfter < 0) {
                throw new \Exception("Insufficient stock. Available: {$quantityBefore}, Requested: {$quantity}");
            }

            // Update stock level
            $this->updateStockLevel($product, $warehouse, [
                'quantity_on_hand' => $quantityAfter,
                'quantity_available' => $quantityAfter - ($stockLevel->quantity_reserved ?? 0),
            ]);

            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'user_id' => Auth::id(),
                'type' => 'out',
                'quantity' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => $options['reference_type'] ?? null,
                'reference_id' => $options['reference_id'] ?? null,
                'batch_number' => $options['batch_number'] ?? null,
                'serial_number' => $options['serial_number'] ?? null,
                'notes' => $options['notes'] ?? null,
            ]);

            return $quantityAfter;
        });
    }

    /**
     * Adjust stock level (for corrections, damage, etc.)
     */
    public function adjustStock(Product $product, Warehouse $warehouse, int $quantity, array $options = [])
    {
        return DB::transaction(function () use ($product, $warehouse, $quantity, $options) {
            // Get current stock level
            $stockLevel = $this->getStockLevel($product, $warehouse);
            $quantityBefore = $stockLevel->quantity_on_hand ?? 0;
            $quantityAfter = $quantityBefore + $quantity;

            // Update stock level
            $this->updateStockLevel($product, $warehouse, [
                'quantity_on_hand' => $quantityAfter,
                'quantity_available' => $quantityAfter - ($stockLevel->quantity_reserved ?? 0),
            ]);

            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'user_id' => Auth::id(),
                'type' => 'adjustment',
                'quantity' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => $options['reference_type'] ?? null,
                'reference_id' => $options['reference_id'] ?? null,
                'batch_number' => $options['batch_number'] ?? null,
                'serial_number' => $options['serial_number'] ?? null,
                'notes' => $options['notes'] ?? null,
            ]);

            return $quantityAfter;
        });
    }

    /**
     * Transfer stock between warehouses
     */
    public function transferStock(Product $product, Warehouse $fromWarehouse, Warehouse $toWarehouse, int $quantity, array $options = [])
    {
        return DB::transaction(function () use ($product, $fromWarehouse, $toWarehouse, $quantity, $options) {
            // Remove from source warehouse
            $this->removeStock($product, $fromWarehouse, $quantity, [
                'reference_type' => 'transfer',
                'reference_id' => $toWarehouse->id,
                'notes' => "Transfer to {$toWarehouse->name}",
            ]);

            // Add to destination warehouse
            $this->addStock($product, $toWarehouse, $quantity, [
                'reference_type' => 'transfer',
                'reference_id' => $fromWarehouse->id,
                'notes' => "Transfer from {$fromWarehouse->name}",
            ]);

            return true;
        });
    }

    /**
     * Reserve stock for an order
     */
    public function reserveStock(Product $product, Warehouse $warehouse, int $quantity)
    {
        return DB::transaction(function () use ($product, $warehouse, $quantity) {
            $stockLevel = $this->getStockLevel($product, $warehouse);
            $currentReserved = $stockLevel->quantity_reserved ?? 0;
            $newReserved = $currentReserved + $quantity;

            // Check if we have enough available stock
            $available = ($stockLevel->quantity_on_hand ?? 0) - $currentReserved;
            if ($available < $quantity) {
                throw new \Exception("Insufficient available stock. Available: {$available}, Requested: {$quantity}");
            }

            $this->updateStockLevel($product, $warehouse, [
                'quantity_reserved' => $newReserved,
                'quantity_available' => ($stockLevel->quantity_on_hand ?? 0) - $newReserved,
            ]);

            return $newReserved;
        });
    }

    /**
     * Release reserved stock
     */
    public function releaseReservedStock(Product $product, Warehouse $warehouse, int $quantity)
    {
        return DB::transaction(function () use ($product, $warehouse, $quantity) {
            $stockLevel = $this->getStockLevel($product, $warehouse);
            $currentReserved = $stockLevel->quantity_reserved ?? 0;
            $newReserved = max(0, $currentReserved - $quantity);

            $this->updateStockLevel($product, $warehouse, [
                'quantity_reserved' => $newReserved,
                'quantity_available' => ($stockLevel->quantity_on_hand ?? 0) - $newReserved,
            ]);

            return $newReserved;
        });
    }

    /**
     * Get stock level for a product in a warehouse
     */
    public function getStockLevel(Product $product, Warehouse $warehouse)
    {
        return DB::table('product_warehouse')
            ->where('product_id', $product->id)
            ->where('warehouse_id', $warehouse->id)
            ->first();
    }

    /**
     * Update stock level for a product in a warehouse
     */
    public function updateStockLevel(Product $product, Warehouse $warehouse, array $data)
    {
        return DB::table('product_warehouse')
            ->updateOrInsert(
                [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                ],
                array_merge($data, [
                    'updated_at' => now(),
                ])
            );
    }

    /**
     * Get products with low stock
     */
    public function getLowStockProducts()
    {
        return Product::whereHas('warehouses', function ($query) {
            $query->whereRaw('product_warehouse.quantity_on_hand <= products.reorder_point');
        })->with(['category', 'supplier'])->get();
    }

    /**
     * Get total inventory value
     */
    public function getTotalInventoryValue()
    {
        return DB::table('product_warehouse')
            ->join('products', 'product_warehouse.product_id', '=', 'products.id')
            ->sum(DB::raw('product_warehouse.quantity_on_hand * products.cost_price'));
    }

    /**
     * Get inventory value by warehouse
     */
    public function getInventoryValueByWarehouse()
    {
        return DB::table('product_warehouse')
            ->join('products', 'product_warehouse.product_id', '=', 'products.id')
            ->join('warehouses', 'product_warehouse.warehouse_id', '=', 'warehouses.id')
            ->select('warehouses.name', DB::raw('SUM(product_warehouse.quantity_on_hand * products.cost_price) as total_value'))
            ->groupBy('warehouses.id', 'warehouses.name')
            ->get();
    }
}
