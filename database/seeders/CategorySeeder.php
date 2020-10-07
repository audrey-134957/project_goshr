<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'Cuisine', 'slug'      => 'cuisine']);
        Category::create(['name' => 'Décoration', 'slug'   => 'decoration']);
        Category::create(['name' => 'Beauté', 'slug'       => 'beaute']);
        Category::create(['name' => 'Vêtements', 'slug'    => 'vetements']);
        Category::create(['name' => 'Bricolage', 'slug'    => 'bricolage']);
        Category::create(['name' => 'Organisation', 'slug' => 'organisation']);
    }
}
