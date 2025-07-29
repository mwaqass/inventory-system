<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use Carbon\Carbon;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        $users = User::all();

        if ($products->isEmpty() || $warehouses->isEmpty() || $users->isEmpty()) {
            $this->command->error('Products, warehouses, or users not found. Please run ProductSeeder, WarehouseSeeder, and UserSeeder first.');
            return;
        }

        $user = $users->first();

        $movementTypes = ['in', 'out', 'transfer', 'adjustment'];
        $typeWeights = [35, 25, 25, 15]; // Percentage distribution

        for ($i = 1; $i <= 100; $i++) {
            $product = $products->random();
            $warehouse = $warehouses->random();
            $movementType = $this->getWeightedRandomType($movementTypes, $typeWeights);
            $movementDate = $this->getRandomMovementDate();

            // Determine quantity based on movement type
            $quantity = $this->getQuantityForType($movementType);

            // For transfers, get a different destination warehouse
            $destinationWarehouse = null;
            if ($movementType === 'transfer') {
                $destinationWarehouse = $warehouses->where('id', '!=', $warehouse->id)->random();
            }

            // Calculate quantity before and after
            $quantityBefore = rand(0, 1000); // Random starting quantity
            $quantityAfter = $quantityBefore;

            switch ($movementType) {
                case 'in':
                    $quantityAfter = $quantityBefore + $quantity;
                    break;
                case 'out':
                    $quantityAfter = max(0, $quantityBefore - $quantity);
                    break;
                case 'adjustment':
                    $quantityAfter = $quantity; // Adjustment sets the quantity directly
                    break;
                case 'transfer':
                    $quantityAfter = max(0, $quantityBefore - $quantity);
                    break;
            }

            StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'user_id' => $user->id,
                'destination_warehouse_id' => $destinationWarehouse?->id,
                'type' => $movementType,
                'quantity' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'notes' => $this->getRandomNotes($movementType) . ' (Ref: ' . $this->getReferenceForType($movementType, $i) . ')',
                'created_at' => $movementDate,
                'updated_at' => $movementDate,
            ]);
        }

        $this->command->info('Stock Movements seeded successfully!');
    }

    private function getWeightedRandomType($types, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = mt_rand(1, $totalWeight);
        $currentWeight = 0;

        for ($i = 0; $i < count($types); $i++) {
            $currentWeight += $weights[$i];
            if ($random <= $currentWeight) {
                return $types[$i];
            }
        }

        return $types[0];
    }

    private function getRandomMovementDate()
    {
        $daysAgo = rand(0, 180); // Movements from last 6 months
        return \Carbon\Carbon::now()->subDays($daysAgo);
    }

    private function getQuantityForType($type)
    {
        switch ($type) {
            case 'in':
                return rand(10, 200); // Large quantities for stock in
            case 'out':
                return rand(1, 50); // Smaller quantities for stock out
            case 'transfer':
                return rand(5, 100); // Medium quantities for transfers
            case 'adjustment':
                return rand(-20, 20); // Can be negative for adjustments
            default:
                return rand(1, 50);
        }
    }

    private function getReferenceForType($type, $index)
    {
        switch ($type) {
            case 'in':
                return 'PO-' . str_pad(rand(1, 30), 6, '0', STR_PAD_LEFT);
            case 'out':
                return 'ORD-' . str_pad(rand(1, 50), 6, '0', STR_PAD_LEFT);
            case 'transfer':
                return 'TRF-' . str_pad($index, 6, '0', STR_PAD_LEFT);
            case 'adjustment':
                return 'ADJ-' . str_pad($index, 6, '0', STR_PAD_LEFT);
            default:
                return 'MOV-' . str_pad($index, 6, '0', STR_PAD_LEFT);
        }
    }

    private function getRandomNotes($type)
    {
        $notes = [
            'in' => [
                'Received from supplier',
                'Purchase order received',
                'Quality check passed',
                'Added to inventory',
                'Bulk shipment received',
            ],
            'out' => [
                'Customer order fulfilled',
                'Shipped to customer',
                'Sample sent to customer',
                'Damaged item removed',
                'Promotional giveaway',
            ],
            'transfer' => [
                'Transferred between warehouses',
                'Inventory rebalancing',
                'Seasonal stock movement',
                'Warehouse consolidation',
                'Regional distribution',
            ],
            'adjustment' => [
                'Inventory count adjustment',
                'Damaged goods write-off',
                'Expired items removed',
                'Found items added',
                'System correction',
            ],
        ];

        $typeNotes = $notes[$type] ?? ['No special notes'];
        return $typeNotes[array_rand($typeNotes)];
    }
}
