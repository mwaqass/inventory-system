<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $user = \App\Models\User::first();

        if ($products->isEmpty()) {
            $this->command->warn('Products not found. Please run ProductSeeder first.');
            return;
        }

        if (!$user) {
            $this->command->warn('No user found. Please run AdminUserSeeder first.');
            return;
        }

        $customers = [
            ['name' => 'John Smith', 'email' => 'john.smith@email.com', 'phone' => '+1-555-0101'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.j@email.com', 'phone' => '+1-555-0102'],
            ['name' => 'Michael Brown', 'email' => 'michael.b@email.com', 'phone' => '+1-555-0103'],
            ['name' => 'Emily Davis', 'email' => 'emily.d@email.com', 'phone' => '+1-555-0104'],
            ['name' => 'David Wilson', 'email' => 'david.w@email.com', 'phone' => '+1-555-0105'],
            ['name' => 'Lisa Anderson', 'email' => 'lisa.a@email.com', 'phone' => '+1-555-0106'],
            ['name' => 'Robert Taylor', 'email' => 'robert.t@email.com', 'phone' => '+1-555-0107'],
            ['name' => 'Jennifer Martinez', 'email' => 'jennifer.m@email.com', 'phone' => '+1-555-0108'],
            ['name' => 'Christopher Lee', 'email' => 'chris.l@email.com', 'phone' => '+1-555-0109'],
            ['name' => 'Amanda Garcia', 'email' => 'amanda.g@email.com', 'phone' => '+1-555-0110'],
        ];

        $statuses = ['pending', 'confirmed', 'picking', 'picked', 'shipping', 'shipped', 'delivered', 'cancelled'];
        $statusWeights = [15, 20, 15, 15, 15, 15, 5]; // Percentage distribution

        for ($i = 1; $i <= 50; $i++) {
            $customer = $customers[array_rand($customers)];
            $status = $this->getWeightedRandomStatus($statuses, $statusWeights);
            $orderDate = $this->getRandomOrderDate();

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'],
                'customer_phone' => $customer['phone'],
                'status' => $status,
                'total_amount' => 0, // Will be calculated after adding items
                'notes' => $this->getRandomNotes($status),
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Add 1-3 random products to the order (or all products if less than 3)
            $numItems = min(rand(1, 3), $products->count());
            $selectedProducts = $products->random($numItems);
            $totalAmount = 0;

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->selling_price;
                $itemTotal = $quantity * $unitPrice;
                $totalAmount += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotal,
                ]);
            }

            // Update order total
            $order->update(['total_amount' => $totalAmount]);
        }

        $this->command->info('Orders seeded successfully!');
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
        $daysAgo = rand(0, 90); // Orders from last 90 days
        return Carbon::now()->subDays($daysAgo);
    }

    private function getRandomNotes($status)
    {
        $notes = [
            'pending' => [
                'Customer requested express shipping',
                'Gift wrapping requested',
                'Please call customer before delivery',
                'Customer prefers afternoon delivery',
                'Special handling required',
            ],
            'confirmed' => [
                'Order confirmed by customer',
                'Payment received and verified',
                'Order approved for processing',
                'Customer confirmed details',
                'Ready for picking',
            ],
            'picking' => [
                'Items being picked from warehouse',
                'Picking in progress',
                'Gathering items from inventory',
                'Quality check during picking',
                'Picking list generated',
            ],
            'picked' => [
                'All items picked successfully',
                'Items ready for packaging',
                'Picking completed',
                'Items verified and counted',
                'Ready for shipping preparation',
            ],
            'shipping' => [
                'Preparing for shipment',
                'Packaging in progress',
                'Shipping label being generated',
                'Items being packaged',
                'Final quality check',
            ],
            'shipped' => [
                'Shipped via FedEx Ground',
                'Tracking number: 123456789',
                'Shipped via UPS 2nd Day Air',
                'Package insured for full value',
                'Signature required upon delivery',
            ],
            'delivered' => [
                'Delivered successfully',
                'Customer confirmed receipt',
                'Left with neighbor as requested',
                'Delivered to front porch',
                'Customer satisfied with order',
            ],
            'cancelled' => [
                'Customer requested cancellation',
                'Out of stock items',
                'Payment issue',
                'Customer changed mind',
                'Duplicate order cancelled',
            ],
        ];

        $statusNotes = $notes[$status] ?? ['No special notes'];
        return $statusNotes[array_rand($statusNotes)];
    }
}
