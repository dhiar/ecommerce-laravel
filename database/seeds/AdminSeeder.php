<?php

use Illuminate\Database\Seeder;
use App\{Admin, Address};

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $address = Address::create(['name' => 'Indonesia']);
        Admin::create([
            'name'  => 'super-admin',
            'id_user_type' => 1,
            'id_address' => $address->id,
            'email' => 'dhiar.praditya@yahoo.co.id',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor app.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);

        $address = $address->replicate();
        $address->save();
        Admin::create([
            'name'  => 'admin',
            'id_user_type' => 2,
            'id_address' => $address->id,
            'email' => 'darrenzie@gmail.com',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor app.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);
        
        $address = $address->replicate();
        $address->save();
        Admin::create([
            'name'  => 'owner-product',
            'id_user_type' => 3,
            'id_address' => $address->id,
            'email' => 'owner-product@gmail.com',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor owner product.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);
        
        $address = $address->replicate();
        $address->save();
        Admin::create([
            'name'  => 'Rocky Ahmad',
            'id_user_type' => 3,
            'id_address' => $address->id,
            'email' => 'rocky-ahmad@gmail.com',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor owner product.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);
        
        $address = $address->replicate();
        $address->save();
        Admin::create([
            'name'  => 'Nuruddin Zangky',
            'id_user_type' => 3,
            'id_address' => $address->id,
            'email' => 'nuruddin-zangky@gmail.com',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor owner product.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);
    }
}
