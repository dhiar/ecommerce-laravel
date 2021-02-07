<?php

use Illuminate\Database\Seeder;
use App\Navigation;

class NavigationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Navigation::create([
            'name'  => 'Info Website'
        ]);
        Navigation::create([
            'name'  => 'Bantuan'
        ]);
    }
}
