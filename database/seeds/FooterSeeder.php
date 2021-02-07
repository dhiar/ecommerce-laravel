<?php

use Illuminate\Database\Seeder;
use App\Footer;

class FooterSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Footer::create([
            'id_page'  => '1',
            'id_navigation' => '1'
        ]);
        Footer::create([
            'id_page'  => '2',
            'id_navigation' => '2'
        ]);
        Footer::create([
            'id_page'  => '3',
            'id_navigation' => '1'
        ]);
        Footer::create([
            'id_page'  => '4',
            'id_navigation' => '1'
        ]);
        Footer::create([
            'id_page'  => '5',
            'id_navigation' => '1'
        ]);
        Footer::create([
            'id_page'  => '6',
            'id_navigation' => '2'
        ]);
        Footer::create([
            'id_page'  => '7',
            'id_navigation' => '2'
        ]);
    }
}
