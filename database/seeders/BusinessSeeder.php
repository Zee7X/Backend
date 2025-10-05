<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BusinessSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $categories = Category::all();

        for ($i = 1; $i <= 15; $i++) {
            $category = $categories->random();

            Business::create([
                'name' => $faker->company,
                'slug' => Str::slug($faker->unique()->company),
                'category_id' => $category->id,
                'description' => $faker->paragraph,
                'logo_path' => null,
            ]);
        }
    }
}
