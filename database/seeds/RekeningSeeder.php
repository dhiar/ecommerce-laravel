<?php

use Illuminate\Database\Seeder;
use App\Rekening;

class RekeningSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Rekening::create([
            'rekening'  => 'BCA',
            'name' => 'Usva Dhiar Praditya',
            'number' => '12345678910'
        ]);
    }
}
