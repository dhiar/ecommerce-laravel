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
            'name'  => 'Owner'
        ]);
        UserTypes::create([
            'name'  => 'Customer'
        ]);
        UserTypes::create([
            'name'  => 'Guest'
        ]);
    }
}
