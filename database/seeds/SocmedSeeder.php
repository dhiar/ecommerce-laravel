<?php

use Illuminate\Database\Seeder;
use App\Socmed;

class SocmedSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Socmed::create([
            'name'  => 'Facebook',
            'icon'  => 'facebook-f',
            'link'  => 'https://facebook.com/your_account'
        ]);

        Socmed::create([
            'name'  => 'Twitter',
            'icon'  => 'twitter',
            'link'  => 'https://twitter.com/your_account'
        ]);

        Socmed::create([
            'name'  => 'Linkedin',
            'icon'  => 'linkedin-in',
            'link'  => 'https://linkedin.com/in/your_account'
        ]);

        Socmed::create([
            'name'  => 'Instagram',
            'icon'  => 'instagram',
            'link'  => 'https://instagram.com/your_account'
        ]);

        Socmed::create([
            'name'  => 'Youtube',
            'icon'  => 'youtube',
            'link'  => 'https://youtube.com/your_account'
        ]);
    }
}
