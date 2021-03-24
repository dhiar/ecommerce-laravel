<?php

use Illuminate\Database\Seeder;
use App\{User, Address};

class UserCustomerSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $address = Address::create(['name' => 'Indonesia']);
        User::create([
            'name'  => 'user-customer',
            'email' => 'darrenzie@gmail.com',
            'password' => bcrypt('manager247'),
            'id_address' => $address->id,
            'id_user_type' => 4,
            'phone' => '081289482090',
            'photo' => 'https://www.travelcontinuously.com/wp-content/uploads/2018/04/empty-avatar.png'
        ]);
    }
}
