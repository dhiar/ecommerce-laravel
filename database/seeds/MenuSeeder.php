<?php

use Illuminate\Database\Seeder;
use App\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Menu::create([
            'name'  => 'Dasboard'
        ]);
        Menu::create([
            'name'  => 'Products'
        ]);
        Menu::create([
            'name'  => 'Orders'
        ]);
        Menu::create([
            'name'  => 'Users'
        ]);
        Menu::create([
            'name'  => 'UserInterface'
        ]);
        Menu::create([
            'name'  => 'Menu'
        ]);
        Menu::create([
            'name'  => 'Report'
        ]);
        Menu::create([
            'name'  => 'Account'
        ]);
    }
}
