<?php

use Illuminate\Database\Seeder;
use App\Grosir;

class GrosirSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Grosir::create([
            'id_product'  => '16',
            'min'  => '10',
            'price'  => '1000000',
        ]);

        Grosir::create([
            'id_product'  => '16',
            'min'  => '20',
            'price'  => '1950000',
        ]);
    }
}
