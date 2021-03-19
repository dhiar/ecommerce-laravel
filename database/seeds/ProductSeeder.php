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
            $product = Product::create([
                'id_category_brand'  => '1',
                'id_admin'  => '2',
                'name'  => $i <= 15 ? 'Madu Hutan NTT (Bunga Kayu Putih) - '.$i : 'Madu Randu - '.$i,
                'image'  => $i == 1 ? 'public/images/InAftoNK3bFuCcGRAUoz98cCD07ieHZgRX2U36tx.jpeg' : $i == 16 ? "public/products/WZ0JP5OAOA7wZgAZZADSjBZNZzTWy6ZcWdYFX64j.jpeg" : "empty-$i.png",
                'price'  => '130000',
                'promo_price'  => '125000',
                'weight'  => '800',
                'stock'  => '200',
                'condition'  => 'New',
                'description'  => '<p><strong>Description</strong></p>',
                'is_published'  => '1',
                'is_promo'  => $i <= 15 ? '0' : '1',
                'slug'  => $i <= 15 ? 'madu-hutan-ntt-(bunga-kayu-putih)-'.$i : 'madu-randu---'.$i,
                'transaction'  => '123',
                'count_view'  => 100 + $i,
                'count_like' => 500 - $i,
            ]);
            
            if ($i <= 7) {
                $product->syncTagsWithType(['madurandu', 'madusragen', 'maduternak'], 'product');
            } else if ($i <= 15) {
                $product->syncTagsWithType(['madukayuputih', 'madusragen', 'maduternak'], 'product');                
            } else if ($i <= 22) {
                $product->syncTagsWithType(['madukayuputih', 'maduntt', 'maduhutan'], 'product');
            } else if ($i <= 30) {
                $product->syncTagsWithType(['madurambutan', 'madusragen', 'maduternak'], 'product');    
            }
        }
    }
}
