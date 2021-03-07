<?php

use Illuminate\Database\Seeder;
use App\Slide;

class SlideSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Slide::create([
            'title'  => 'Madu Randu Jepara',
            'url'  => '#',
            'description'  => '<p>Madu dari nektar bunga Randu. Lokasi lebah berada di Jepara.</p>',
            'image'  => 'public/slides/XTisw5z2UVzXSBQEgRYGM0Z4GGvSOV2l48xp2Cie.png',
            'active'  => '1',
            'id_product'  => '16'
        ]);

        Slide::create([
            'title'  => 'Madu Hutan Kayu Putih',
            'url'  => '#',
            'description'  => '<p>Madu dari nektar bunga Kayu Putih. Lokasi lebah berada di Pulau Amfoang, NTT.</p>',
            'image'  => 'public/slides/W4rkWP5bwq96VIgeGzQ0MToUUbC0tSN1IpQQ9xGM.png',
            'active'  => '1',
            'id_product'  => '1'
        ]);
    }
}
