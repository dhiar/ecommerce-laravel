<?php

use Illuminate\Database\Seeder;
use App\Base;

class BaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Base::create([
            'shop_name'  => 'Green Store',
            'address' => 'Pandak Kulon, RT.06, RW.02',
            'phone' => '081289482090',
            'description' => 'Green store adalah sebuah toko yang menyediakan/menjual produk-produk alami.',
            'logo' => ''
        ]);
    }
}
