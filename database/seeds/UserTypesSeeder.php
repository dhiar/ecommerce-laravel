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
        // bisa men-delete admin
        UserTypes::create([
            'name'  => 'Super Admin'
        ]);

        // Tidak bisa men-delete admin
        UserTypes::create([
            'name'  => 'Admin'
        ]);
        
        // Admin Owner Product
        UserTypes::create([
            'name'  => 'Owner Product'
        ]);

        // Table User
        UserTypes::create([
            'name'  => 'Customer'
        ]);
    }
}
