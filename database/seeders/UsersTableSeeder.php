<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'sales_permission']);
        Permission::create(['name' => 'product_permission']);
        Permission::create(['name' => 'income_permission']);
        Permission::create(['name' => 'document_permission']);
        Permission::create(['name' => 'inventory_permission']);
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('sales_permission');
        $role->givePermissionTo('product_permission');
        $role->givePermissionTo('income_permission');
        $role->givePermissionTo('document_permission');
        $role->givePermissionTo('inventory_permission');
        // $role->hasPermissionTo('sales_permission');
        $user = User::create([
            'id' => 1,
            'name' => 'Edwin Holguin',
            'email' => 'edwin@pro.com',
            'email_verified_at' => now(),
            'password' => Hash::make('edwin'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $user->assignRole('Admin');
        for($i = 0; $i < 1; $i++){
            DB::table('products')->insert([
                'name' => 'COCA COLA MINI',
                'stock' => rand (0 ,100),
                'sale_price' => rand (0 ,3000),
                'entry_price' => rand (0 ,3000),
                'code' => 'ABC123'.$i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            DB::table('products')->insert([
                'name' => 'COCA COLA 350',
                'stock' => rand (0 ,100),
                'sale_price' => rand (0 ,3000),
                'entry_price' => rand (0 ,3000),
                'code' => 'ABC12'.$i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        DB::table('status_sales')->insert([
            'name' => 'SIN PAGAR'
        ]);
        DB::table('status_sales')->insert([
            'name' => 'ABONO'
        ]);
        DB::table('status_sales')->insert([
            'name' => 'PAGADO'
        ]);
        DB::table('status_sales')->insert([
            'name' => 'PERDIDA'
        ]);
        DB::table('status_sales')->insert([
            'name' => 'DEUDA'
        ]);
        DB::table('type_documents')->insert([
            'name' => 'PEDIDOS',
            'prefix' => 'PED'
        ]);
        DB::table('type_documents')->insert([
            'name' => 'VENTAS',
            'prefix' => 'VEN'
        ]);
    }
}
