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
            'name'  => 'admin-user',
            'id_user_type' => 1,
            'email' => 'dhiar.praditya@yahoo.co.id',
            'password' => bcrypt('manager247'),
            'job_title' => 'Manage & monitor app.',
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);
    }
}
