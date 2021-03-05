<?php

use Illuminate\Database\Seeder;
use App\ProductBrand;

class ProductBrandSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ProductBrand::create([
            'name'  => 'Madu Hutan (NTT)',
            'slug'  => 'madu-hutan-(ntt)'
        ]);
    }
}
