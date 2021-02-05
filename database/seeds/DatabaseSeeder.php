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
        $this->call(PageSeeder::class);
        $this->call(DeliveryStatusSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(UserOwnerExampleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(BaseSeeder::class);
        $this->call(SocmedSeeder::class);
        $this->call(RekeningSeeder::class);
    }
}
