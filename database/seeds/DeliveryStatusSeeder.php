<?php

use Illuminate\Database\Seeder;
use App\DeliveryStatus;

class DeliveryStatusSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DeliveryStatus::create([
            'name'  => 'Verifikasi'
        ]);
        DeliveryStatus::create([
            'name'  => 'Dikemas'
        ]);
        DeliveryStatus::create([
            'name'  => 'Dikirim'
        ]);
        DeliveryStatus::create([
            'name'  => 'Diterima'
        ]);
        DeliveryStatus::create([
            'name'  => 'Cancel'
        ]);
    }
}
