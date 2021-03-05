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
        $this->call(DeliveryStatusSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(UserCustomerSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(BaseSeeder::class);
        $this->call(SocmedSeeder::class);
        $this->call(RekeningSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(NavigationSeeder::class);
        $this->call(FooterSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductBrandSeeder::class);
        $this->call(CategoryBrandSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(GrosirSeeder::class);
        $this->call(ProductImageSeeder::class);
    }
}
