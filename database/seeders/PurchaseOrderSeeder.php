<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrderItem;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        $user = \App\Models\User::first();

        if ($products->isEmpty() || $suppliers->isEmpty()) {
            $this->command->warn('Products or Suppliers not found. Please run ProductSeeder and SupplierSeeder first.');
            return;
        }

        if (!$user) {
            $this->command->warn('No user found. Please run AdminUserSeeder first.');
            return;
        }

        $statuses = ['draft', 'sent', 'confirmed', 'received', 'cancelled'];
        $statusWeights = [15, 25, 30, 25, 5]; // Percentage distribution

        for ($i = 1; $i <= 30; $i++) {
            $supplier = $suppliers->random();
            $status = $this->getWeightedRandomStatus($statuses, $statusWeights);
            $orderDate = $this->getRandomOrderDate();

            $purchaseOrder = PurchaseOrder::create([
                'user_id' => $user->id,
                'po_number' => 'PO-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'supplier_id' => $supplier->id,
                'status' => $status,
                'order_date' => $orderDate,
                'total_amount' => 0, // Will be calculated after adding items
                'expected_delivery_date' => $this->getExpectedDeliveryDate($orderDate, $status),
                'notes' => $this->getRandomNotes($status),
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Add 2-5 random products to the purchase order (or all products if less than 5)
            $numItems = min(rand(2, 5), $products->count());
            $selectedProducts = $products->random($numItems);
            $totalAmount = 0;

            foreach ($selectedProducts as $product) {
                $quantity = rand(10, 100); // Larger quantities for purchase orders
                $unitPrice = $product->cost_price; // Use cost price for purchase orders
                $itemTotal = $quantity * $unitPrice;
                $totalAmount += $itemTotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_cost' => $unitPrice,
                    'total_cost' => $itemTotal,
                ]);
            }

            // Update purchase order total
            $purchaseOrder->update(['total_amount' => $totalAmount]);
        }

        $this->command->info('Purchase Orders seeded successfully!');
    }

    private function getWeightedRandomStatus($statuses, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = mt_rand(1, $totalWeight);
        $currentWeight = 0;

        for ($i = 0; $i < count($statuses); $i++) {
            $currentWeight += $weights[$i];
            if ($random <= $currentWeight) {
                return $statuses[$i];
            }
        }

        return $statuses[0];
    }

    private function getRandomOrderDate()
    {
        $daysAgo = rand(0, 120); // Purchase orders from last 120 days
        return \Carbon\Carbon::now()->subDays($daysAgo);
    }

    private function getExpectedDeliveryDate($orderDate, $status)
    {
        if (in_array($status, ['draft', 'cancelled'])) {
            return null;
        }

        $deliveryDays = rand(7, 30); // 1-4 weeks delivery time
        return $orderDate->copy()->addDays($deliveryDays);
    }

    private function getRandomNotes($status)
    {
        $notes = [
            'draft' => [
                'Draft order - pending review',
                'Need to confirm pricing with supplier',
                'Awaiting approval from management',
                'Draft created for budget planning',
                'Pending supplier availability check',
            ],
            'sent' => [
                'Purchase order sent to supplier',
                'Awaiting supplier confirmation',
                'Order submitted via email',
                'Sent via supplier portal',
                'Purchase order faxed to supplier',
            ],
            'confirmed' => [
                'Supplier confirmed order receipt',
                'Production scheduled for next week',
                'Materials being sourced',
                'Quality check in progress',
                'Packaging and shipping preparation',
            ],
            'received' => [
                'Order received and inspected',
                'All items accounted for',
                'Quality check passed',
                'Items added to inventory',
                'Payment processed',
            ],
            'cancelled' => [
                'Cancelled due to supplier issues',
                'Budget constraints led to cancellation',
                'Supplier unable to meet delivery date',
                'Alternative supplier found',
                'Order cancelled by management',
            ],
        ];

        $statusNotes = $notes[$status] ?? ['No special notes'];
        return $statusNotes[array_rand($statusNotes)];
    }
}
