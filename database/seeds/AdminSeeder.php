<?php

use Illuminate\Database\Seeder;
use App\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name'  => 'super-admin',
            'id_user_type' => 1,
            'email' => 'dhiar.praditya@yahoo.co.id',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor app.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);

        Admin::create([
            'name'  => 'admin',
            'id_user_type' => 2,
            'email' => 'darrenzie@gmail.com',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor app.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);

        Admin::create([
            'name'  => 'owner-product',
            'id_user_type' => 3,
            'email' => 'owner-product@gmail.com',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor owner product.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);
    }
}
