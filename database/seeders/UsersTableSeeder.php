<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Edwin Holguin',
            'email' => 'edwin@pro.com',
            'email_verified_at' => now(),
            'password' => Hash::make('edwin'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
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
