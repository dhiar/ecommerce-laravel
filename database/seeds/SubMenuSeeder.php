<?php

use Illuminate\Database\Seeder;
use App\Sub_menu;

class SubMenuSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Sub_menu::create([
            'id_menu'  => 1,
            'title' => 'Dasboard',
            'url' => 'admin/dasboard',
            'icon' => 'fas fa-tachometer-alt',
            'active' => true
        ]);

        Sub_menu::create([
            'id_menu'  => 2,
            'title' => 'Products',
            'url' => 'admin/products',
            'icon' => 'fas fa-box-open',
            'active' => true
        ]);

        Sub_menu::create([
            'id_menu'  => 2,
            'title' => 'Product Types',
            'url' => 'admin/product/types',
            'icon' => 'fas fa-th-large',
            'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 2,
					'title' => 'Product Brands',
					'url' => 'admin/product/brands',
					'icon' => 'far fa-image',
					'active' => true
			]);
				
				Sub_menu::create([
					'id_menu'  => 3,
					'title' => 'Orders',
					'url' => 'admin/orders',
					'icon' => 'fas fa-shopping-cart',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 3,
					'title' => 'Customers',
					'url' => 'admin/order/customers',
					'icon' => 'fas fa-users',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 4,
					'title' => 'Users',
					'url' => 'admin/users',
					'icon' => 'fas fa-user-tie',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 4,
					'title' => 'User Types',
					'url' => 'admin/user/types',
					'icon' => 'fas fa-user-cog',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 4,
					'title' => 'Token',
					'url' => 'admin/user/tokens',
					'icon' => 'fas fa-key',
					'active' => true
				]);
				
				Sub_menu::create([
						'id_menu'  => 5,
						'title' => 'Base',
						'url' => 'admin/ui',
						'icon' => 'fas fa-store-alt',
						'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 5,
					'title' => 'Slide',
					'url' => 'admin/ui/slide',
					'icon' => 'far fa-images',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 6,
					'title' => 'Menu',
					'url' => 'admin/menu',
					'icon' => 'fas fa-fw fa-folder',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 6,
					'title' => 'Submenu',
					'url' => 'admin/menu/submenu',
					'icon' => 'fas fa-fw fa-folder-open',
					'active' => true
				]);
				
				Sub_menu::create([
					'id_menu'  => 7,
					'title' => 'Little Stock',
					'url' => 'admin/report',
					'icon' => 'fas fa-fw fa-industry',
					'active' => true
				]);
				
				Sub_menu::create([
					'id_menu'  => 7,
					'title' => 'Daily Report',
					'url' => 'admin/report/daily',
					'icon' => 'fas fa-fw fa-book',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 7,
					'title' => 'Weekly Report',
					'url' => 'admin/report/weekly',
					'icon' => 'fas fa-fw fa-book',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 7,
					'title' => 'Monthly Report',
					'url' => 'admin/report/monthly',
					'icon' => 'fas fa-fw fa-book',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 7,
					'title' => 'Annual Report',
					'url' => 'admin/report/annual',
					'icon' => 'fas fa-fw fa-book',
					'active' => true
				]);
				
				Sub_menu::create([
					'id_menu'  => 7,
					'title' => 'Report by Date',
					'url' => 'admin/report/by_date',
					'icon' => 'fas fa-fw fa-book',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 7,
					'title' => 'Custom Report',
					'url' => 'admin/report/custom',
					'icon' => 'fas fa-fw fa-book',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 8,
					'title' => 'Profile',
					'url' => 'admin/account',
					'icon' => 'fas fa-user',
					'active' => true
				]);

				Sub_menu::create([
					'id_menu'  => 8,
					'title' => 'Logout',
					'url' => 'admin/auth/logout',
					'icon' => 'fas fa-sign-out-alt',
					'active' => true
				]);
    }
}
