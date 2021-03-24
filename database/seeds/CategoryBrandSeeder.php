<?php

use Illuminate\Database\Seeder;
use App\CategoryBrand;

class CategoryBrandSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        CategoryBrand::create([
            'id_category'  => '3',
            'id_brand'  => '1'
        ]);

        CategoryBrand::create([
            'id_category'  => '3',
            'id_brand'  => '2'
        ]);
    }
}
