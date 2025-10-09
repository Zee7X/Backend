<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    public function run()
    {
        $businesses = [
            [
                'name' => 'The Gourmet Kitchen',
                'category' => 'Restaurant',
                'logo' => 'logoipsum-374.png',
                'description' => 'Fine dining restaurant offering a curated menu of international cuisine in an elegant setting.'
            ],
            [
                'name' => 'Mama Mia Ristorante',
                'category' => 'Restaurant',
                'logo' => 'logoipsum-376.png',
                'description' => 'Authentic Italian dishes with fresh ingredients and a cozy, family-friendly atmosphere.'
            ],
            [
                'name' => 'Coffee Corner',
                'category' => 'Cafe',
                'logo' => 'logoipsum-379.png',
                'description' => 'Casual cafe serving artisanal coffee, pastries, and light bites for daily relaxation.'
            ],
            [
                'name' => 'Brew & Beans',
                'category' => 'Cafe',
                'logo' => 'logoipsum-380.png',
                'description' => 'Specialty coffee shop known for its freshly roasted beans and cozy ambiance.'
            ],
            [
                'name' => 'FitLife Gym',
                'category' => 'Fitness',
                'logo' => 'logoipsum-381.png',
                'description' => 'State-of-the-art gym with personal training, group classes, and modern equipment.'
            ],
            [
                'name' => 'Zen Yoga Studio',
                'category' => 'Fitness',
                'logo' => 'logoipsum-382.png',
                'description' => 'Peaceful yoga studio offering classes for all levels and meditation workshops.'
            ],
            [
                'name' => 'Glamour Studio',
                'category' => 'Salon',
                'logo' => 'logoipsum-383.png',
                'description' => 'Premium salon providing haircuts, styling, and beauty treatments with professional care.'
            ],
            [
                'name' => 'Chic Cuts Salon',
                'category' => 'Salon',
                'logo' => 'logoipsum-384.png',
                'description' => 'Trendy salon specializing in modern haircuts and color services for all ages.'
            ],
            [
                'name' => 'FreshMart',
                'category' => 'Grocery',
                'logo' => 'logoipsum-386.png',
                'description' => 'Neighborhood grocery store offering fresh produce, daily essentials, and friendly service.'
            ],
            [
                'name' => 'Green Basket',
                'category' => 'Grocery',
                'logo' => 'logoipsum-387.png',
                'description' => 'Organic grocery store focused on healthy and sustainable products.'
            ],
            [
                'name' => 'Tech World',
                'category' => 'Electronics',
                'logo' => 'logoipsum-390.png',
                'description' => 'Electronics retailer with a wide selection of gadgets, devices, and tech accessories.'
            ],
            [
                'name' => 'Gadget Hub',
                'category' => 'Electronics',
                'logo' => 'logoipsum-391.png',
                'description' => 'Your go-to store for the latest smartphones, laptops, and electronics.'
            ],
            [
                'name' => 'Readers Haven',
                'category' => 'Bookstore',
                'logo' => 'logoipsum-392.png',
                'description' => 'Cozy bookstore with a wide variety of books, magazines, and reading nooks.'
            ],
            [
                'name' => 'Book Nook',
                'category' => 'Bookstore',
                'logo' => 'logoipsum-394.png',
                'description' => 'A small bookstore filled with rare books, novels, and a friendly atmosphere.'
            ],
            [
                'name' => 'Urban Threads',
                'category' => 'Clothing',
                'logo' => 'logoipsum-395.png',
                'description' => 'Fashion boutique offering trendy urban clothing and accessories for men and women.'
            ],
            [
                'name' => 'Fashion House',
                'category' => 'Clothing',
                'logo' => 'logoipsum-396.png',
                'description' => 'Modern clothing store with stylish outfits for every occasion.'
            ],
            [
                'name' => 'Ocean View Diner',
                'category' => 'Restaurant',
                'logo' => 'logoipsum-399.png',
                'description' => 'Casual seaside diner serving fresh seafood and classic dishes with a view.'
            ],
            [
                'name' => 'Espresso Express',
                'category' => 'Cafe',
                'logo' => 'logoipsum-403.png',
                'description' => 'Fast and friendly cafe with quality espresso drinks and snacks for busy mornings.'
            ],
            [
                'name' => 'Powerhouse Training',
                'category' => 'Fitness',
                'logo' => 'logoipsum-405.png',
                'description' => 'High-intensity training facility focused on strength and endurance programs.'
            ],
            [
                'name' => 'Style Lounge',
                'category' => 'Salon',
                'logo' => 'logoipsum-407.png',
                'description' => 'Upscale salon offering haircuts, coloring, and spa treatments in a stylish setting.'
            ],
        ];

        $categories = Category::all()->keyBy('name');

        foreach ($businesses as $item) {
            $category = $categories[$item['category']] ?? null;
            if (!$category) continue;

            Business::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'category_id' => $category->id,
                'description' => $item['description'],
                'logo_path' => 'logos/' . $item['logo'],
            ]);
        }
    }
}
