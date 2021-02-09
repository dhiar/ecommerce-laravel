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
            'page_id'  => '1',
            'navigation_id' => '1'
        ]);
        Footer::create([
            'page_id'  => '2',
            'navigation_id' => '2'
        ]);
        Footer::create([
            'page_id'  => '3',
            'navigation_id' => '1'
        ]);
        Footer::create([
            'page_id'  => '4',
            'navigation_id' => '1'
        ]);
        Footer::create([
            'page_id'  => '5',
            'navigation_id' => '1'
        ]);
        Footer::create([
            'page_id'  => '6',
            'navigation_id' => '2'
        ]);
        Footer::create([
            'page_id'  => '7',
            'navigation_id' => '2'
        ]);
    }
}
