<?php

use App\DeliveryStatus;
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
        $this->call(UserAdminSeeder::class);
        $this->call(DeliveryStatusSeeder::class);
    }
}
