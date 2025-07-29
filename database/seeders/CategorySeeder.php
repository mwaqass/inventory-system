<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Apparel and fashion items',
                'is_active' => true,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home improvement and garden supplies',
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Sports equipment and outdoor gear',
                'is_active' => true,
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, movies, and music',
                'is_active' => true,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Car parts and accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Health & Beauty',
                'slug' => 'health-beauty',
                'description' => 'Health and beauty products',
                'is_active' => true,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Toys and entertainment items',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create subcategories
        $electronics = Category::where('slug', 'electronics')->first();
        if ($electronics) {
            Category::create([
                'name' => 'Smartphones',
                'slug' => 'smartphones',
                'description' => 'Mobile phones and accessories',
                'parent_id' => $electronics->id,
                'is_active' => true,
            ]);

            Category::create([
                'name' => 'Laptops',
                'slug' => 'laptops',
                'description' => 'Portable computers',
                'parent_id' => $electronics->id,
                'is_active' => true,
            ]);
        }

        $clothing = Category::where('slug', 'clothing')->first();
        if ($clothing) {
            Category::create([
                'name' => 'Men\'s Clothing',
                'slug' => 'mens-clothing',
                'description' => 'Clothing for men',
                'parent_id' => $clothing->id,
                'is_active' => true,
            ]);

            Category::create([
                'name' => 'Women\'s Clothing',
                'slug' => 'womens-clothing',
                'description' => 'Clothing for women',
                'parent_id' => $clothing->id,
                'is_active' => true,
            ]);
        }
    }
}
