<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories and warehouses for relationships
        $categories = Category::all();
        $warehouses = Warehouse::all();

        if ($categories->isEmpty() || $warehouses->isEmpty()) {
            $this->command->warn('Categories or Warehouses not found. Please run CategorySeeder and WarehouseSeeder first.');
            return;
        }

        $products = [
            // Electronics
            [
                'name' => 'iPhone 15 Pro',
                'sku' => 'IPH15PRO-256',
                'description' => 'Latest iPhone with A17 Pro chip, 256GB storage',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
                'selling_price' => 999.99,
                'cost_price' => 750.00,
                'reorder_point' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'sku' => 'SAMS24-128',
                'description' => 'Android flagship with AI features, 128GB storage',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
                'selling_price' => 899.99,
                'cost_price' => 680.00,
                'reorder_point' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'MacBook Air M2',
                'sku' => 'MBA-M2-512',
                'description' => '13-inch MacBook Air with M2 chip, 512GB SSD',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
                'selling_price' => 1199.99,
                'cost_price' => 900.00,
                'reorder_point' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Dell XPS 13',
                'sku' => 'DELLXPS13-512',
                'description' => 'Premium Windows laptop, 13-inch, 512GB SSD',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
                'selling_price' => 1099.99,
                'cost_price' => 820.00,
                'reorder_point' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'AirPods Pro',
                'sku' => 'AIRPODS-PRO',
                'description' => 'Wireless earbuds with active noise cancellation',
                'category_id' => $categories->where('name', 'Electronics')->first()->id,
                'selling_price' => 249.99,
                'cost_price' => 180.00,
                'reorder_point' => 25,
                'is_active' => true,
            ],

            // Clothing
            [
                'name' => 'Nike Air Max 270',
                'sku' => 'NIKE-AM270-10',
                'description' => 'Comfortable running shoes with Air Max technology',
                'category_id' => $categories->where('name', 'Clothing')->first()->id,
                'selling_price' => 129.99,
                'cost_price' => 85.00,
                'reorder_point' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'sku' => 'ADIDAS-UB22-9',
                'description' => 'Premium running shoes with Boost technology',
                'category_id' => $categories->where('name', 'Clothing')->first()->id,
                'selling_price' => 179.99,
                'cost_price' => 120.00,
                'reorder_point' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Levi\'s 501 Jeans',
                'sku' => 'LEVIS-501-32',
                'description' => 'Classic straight fit jeans, size 32x32',
                'category_id' => $categories->where('name', 'Clothing')->first()->id,
                'selling_price' => 89.99,
                'cost_price' => 55.00,
                'reorder_point' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'H&M Cotton T-Shirt',
                'sku' => 'HM-TSHIRT-L',
                'description' => 'Basic cotton t-shirt, size Large',
                'category_id' => $categories->where('name', 'Clothing')->first()->id,
                'selling_price' => 19.99,
                'cost_price' => 12.00,
                'reorder_point' => 50,
                'is_active' => true,
            ],

            // Home & Garden
            [
                'name' => 'IKEA MALM Bed Frame',
                'sku' => 'IKEA-MALM-QUEEN',
                'description' => 'Queen size bed frame in white',
                'category_id' => $categories->where('name', 'Home & Garden')->first()->id,
                'selling_price' => 199.99,
                'cost_price' => 140.00,
                'reorder_point' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Philips Hue Smart Bulb',
                'sku' => 'PHILIPS-HUE-WHITE',
                'description' => 'Smart LED bulb with WiFi connectivity',
                'category_id' => $categories->where('name', 'Home & Garden')->first()->id,
                'selling_price' => 49.99,
                'cost_price' => 35.00,
                'reorder_point' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'Dyson V15 Detect',
                'sku' => 'DYSON-V15-ABS',
                'description' => 'Cordless vacuum with laser technology',
                'category_id' => $categories->where('name', 'Home & Garden')->first()->id,
                'selling_price' => 699.99,
                'cost_price' => 520.00,
                'reorder_point' => 5,
                'is_active' => true,
            ],

            // Books & Media
            [
                'name' => 'The Psychology of Money',
                'sku' => 'BOOK-PSYCH-MONEY',
                'description' => 'Timeless lessons on wealth, greed, and happiness',
                'category_id' => $categories->where('name', 'Books & Media')->first()->id,
                'selling_price' => 24.99,
                'cost_price' => 15.00,
                'reorder_point' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Atomic Habits',
                'sku' => 'BOOK-ATOMIC-HABITS',
                'description' => 'An easy & proven way to build good habits',
                'category_id' => $categories->where('name', 'Books & Media')->first()->id,
                'selling_price' => 26.99,
                'cost_price' => 16.00,
                'reorder_point' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Rich Dad Poor Dad',
                'sku' => 'BOOK-RICH-DAD',
                'description' => 'What the rich teach their kids about money',
                'category_id' => $categories->where('name', 'Books & Media')->first()->id,
                'selling_price' => 22.99,
                'cost_price' => 14.00,
                'reorder_point' => 20,
                'is_active' => true,
            ],

            // Sports & Outdoors
            [
                'name' => 'Yeti Rambler 20oz',
                'sku' => 'YETI-RAMBLER-20',
                'description' => 'Stainless steel water bottle with insulation',
                'category_id' => $categories->where('name', 'Sports & Outdoors')->first()->id,
                'selling_price' => 39.99,
                'cost_price' => 25.00,
                'reorder_point' => 35,
                'is_active' => true,
            ],
            [
                'name' => 'Nike Basketball',
                'sku' => 'NIKE-BBALL-OFFICIAL',
                'description' => 'Official size basketball for indoor/outdoor use',
                'category_id' => $categories->where('name', 'Sports & Outdoors')->first()->id,
                'selling_price' => 69.99,
                'cost_price' => 45.00,
                'reorder_point' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Adidas Soccer Ball',
                'sku' => 'ADIDAS-SOCCER-SIZE5',
                'description' => 'Professional soccer ball, size 5',
                'category_id' => $categories->where('name', 'Sports & Outdoors')->first()->id,
                'selling_price' => 89.99,
                'cost_price' => 60.00,
                'reorder_point' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Add random stock quantities to warehouses
            foreach ($warehouses as $warehouse) {
                $quantity = rand(5, 100);
                $product->warehouses()->attach($warehouse->id, [
                    'quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Products seeded successfully!');
    }
}
