<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 products
        $products = Product::factory()->count(50)->create();

        // Attach tags to products
        // $tags = Tag::all(); // Get all tags
        // $products->each(function ($product) use ($tags) {
        //     $productTags = $tags->random(rand(1, 3)); // Randomly assign 1 to 3 tags per product
        //     $product->tags()->sync($productTags->pluck('id')); // Attach tags to product
        // });

    }
}
