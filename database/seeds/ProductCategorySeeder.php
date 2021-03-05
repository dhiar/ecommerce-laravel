<?php

use Illuminate\Database\Seeder;
use App\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::create([
            'name'  => 'Laptop',
            'slug'  => 'laptop'
        ]);

        ProductCategory::create([
            'name'  => 'Gadget',
            'slug'  => 'gadget'
        ]);

        ProductCategory::create([
            'name'  => 'Kesehatan (Madu)',
            'slug'  => 'Kesehatan-(madu)'
        ]);

        ProductCategory::create([
            'name'  => 'Pakaian Pria',
            'slug'  => 'pakaian-pria'
        ]);

        
    }
}
