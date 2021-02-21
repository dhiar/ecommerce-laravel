<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        for ($i=1; $i <= 30; $i++) {
            Product::create([
                'id_product_category'  => '4',
                'id_admin'  => '2',
                'name'  => 'Madu Hutan NTT (Bunga Kayu Putih) - '.$i,
                'image'  => $i == 1 ? 'public/images/InAftoNK3bFuCcGRAUoz98cCD07ieHZgRX2U36tx.jpeg' : "empty-$i.png" ,
                'price'  => '130000',
                'promo_price'  => '125000',
                'weight'  => '800',
                'stock'  => '200',
                'condition'  => 'New',
                'description'  => '<p><strong>Description</strong></p>',
                'is_published'  => '1',
                'is_promo'  => $i <= 15 ? '0' : '1',
                'slug'  => 'madu-hutan-ntt-(bunga-kayu-putih) - '.$i,
                'transaction'  => '123',
                'viewer'  => '321'
            ]);
        }
    }
}
