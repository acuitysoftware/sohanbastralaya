<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
        	[
        		'parent_id' => 0,
                'name' => 'Site Settings',
            ],
            [
            	'parent_id' => 0,
                'name' => 'Godown Management',
            ],
            [
            	'parent_id' => 0,
                'name' => 'Product Management',
            ],
            [
            	'parent_id' => 0,
                'name' => 'Sub Admin Management',
            ],
            [
            	'parent_id' => 0,
                'name' => 'Order Management',
            ],
            [
            	'parent_id' => 0,
                'name' => 'Customer Management',
            ],
            [
            	'parent_id' => 0,
                'name' => 'Product Report',
            ],
            [
            	'parent_id' => 1,
                'name' => 'Admin Setting',
            ],
            [
            	'parent_id' => 1,
                'name' => 'Change Password',
            ],
            [
            	'parent_id' => 2,
                'name' => 'Godown Stock',
            ],
            [
            	'parent_id' => 3,
                'name' => 'Product List',
            ],
            [
                'parent_id' => 3,
                'name' => 'Product Add',
            ],
            [
                'parent_id' => 3,
                'name' => 'Product Edit',
            ],
            [
            	'parent_id' => 3,
                'name' => 'Product View',
            ],
            [
            	'parent_id' => 3,
                'name' => 'Product Delete',
            ],
            [
            	'parent_id' => 3,
                'name' => 'Add To cart',
            ],
            [
            	'parent_id' => 3,
                'name' => 'Limited products List',
            ],
            [
            	'parent_id' => 3,
                'name' => 'Upload Product Image',
            ],
            [
            	'parent_id' => 4,
                'name' => 'User List',
            ],
            [
            	'parent_id' => 4,
                'name' => 'User Add',
            ],
            [
            	'parent_id' => 4,
                'name' => 'User Edit',
            ],
            [
            	'parent_id' => 4,
                'name' => 'User Delete',
            ],
            [
            	'parent_id' => 4,
                'name' => 'User Permission',
            ],
            [
            	'parent_id' => 5,
                'name' => 'Order List',
            ],
            [
            	'parent_id' => 5,
                'name' => 'Order View',
            ],
            [
            	'parent_id' => 5,
                'name' => 'Order Delete',
            ],
            [
            	'parent_id' => 5,
                'name' => 'Daily Order Report',
            ],
            [
            	'parent_id' => 5,
                'name' => 'Sub Admin Orders',
            ],
            [
            	'parent_id' => 5,
                'name' => 'Sub Admin Orders View',
            ],
            [
            	'parent_id' => 6,
                'name' => 'Customer List',
            ],
            [
            	'parent_id' => 6,
                'name' => 'Customer View',
            ],
            [
            	'parent_id' => 6,
                'name' => 'Customers Card List',
            ],
            [
            	'parent_id' => 6,
                'name' => 'Customers Card Edit',
            ],
            [
            	'parent_id' => 6,
                'name' => 'Customers Card View',
            ],
            [
            	'parent_id' => 7,
                'name' => 'Report',
            ],
            [
            	'parent_id' => 7,
                'name' => 'Stock Report',
            ],
            [
            	'parent_id' => 7,
                'name' => 'Stock Inserted',
            ],
            [
            	'parent_id' => 7,
                'name' => 'Product Selling Order',
            ],
            [
            	'parent_id' => 7,
                'name' => 'Purchase Report',
            ],
            [
            	'parent_id' => 7,
                'name' => 'User Purchase Report',
            ],
            
        ];
        foreach ($datas as $data) {
            Permission::create($data);
        }
    }
}
