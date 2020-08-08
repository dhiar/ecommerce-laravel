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
            'name'  => 'Pending'
        ]);
        DeliveryStatus::create([
            'name'  => 'Verifikasi'
        ]);
        DeliveryStatus::create([
            'name'  => 'Sedang Dikirim'
        ]);
        DeliveryStatus::create([
            'name'  => 'Diterima'
        ]);
    }
}
