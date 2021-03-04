<?php

use Illuminate\Database\Seeder;
use App\ProductImage;

class ProductImageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ProductImage::create([
            'id_product'  => '16',
            'image'  => 'public/images/OrsBWJBKESNFKSQy4AQt0aPgwjiqpsYeNaXh4cG2.jpeg'
        ]);
        ProductImage::create([
            'id_product'  => '16',
            'image'  => 'public/images/dHqcHKXSGOgHBs3oQa1G35sreMzlZJIwbJZhng4D.jpeg'
        ]);
        ProductImage::create([
            'id_product'  => '16',
            'image'  => 'public/images/J0JDOeO5DWCorc3qsSXx927cnjSZCJMOMaRdnmJH.jpeg'
        ]);
    }
}
