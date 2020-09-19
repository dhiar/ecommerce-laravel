<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTypesSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(SubMenuSeeder::class);
        $this->call(DeliveryStatusSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(UserOwnerExampleSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
