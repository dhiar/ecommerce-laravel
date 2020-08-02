<?php

use Illuminate\Database\Seeder;
use App\UserTypes;

class UserTypesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        UserTypes::create([
            'name'  => 'Admin'
        ]);
        UserTypes::create([
            'name'  => 'Pemilik'
        ]);
        UserTypes::create([
            'name'  => 'Pelanggan'
        ]);
    }
}
