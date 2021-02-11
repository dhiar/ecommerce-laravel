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
            'name'  => 'Komputer',
            'slug'  => 'komputer'
        ]);

        ProductCategory::create([
            'name'  => 'Laptop',
            'slug'  => 'laptop'
        ]);

        ProductCategory::create([
            'name'  => 'Gadget',
            'slug'  => 'gadget'
        ]);

        ProductCategory::create([
            'name'  => 'Madu Hutan',
            'slug'  => 'madu-hutan'
        ]);

        ProductCategory::create([
            'name'  => 'Pakaian Pria',
            'slug'  => 'pakaian-pria'
        ]);

        
    }
}
