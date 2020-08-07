<?php

use Illuminate\Database\Seeder;
use App\User;

class UserAdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'  => 'admin',
            'email' => 'darrenzie@gmail.com',
            'password' => bcrypt('manager247'),
            'address' => 'Indonesia',
            'phone' => '123456789',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);
    }
}
