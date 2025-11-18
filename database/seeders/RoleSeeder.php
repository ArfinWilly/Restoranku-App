<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_name' => 'admin' , 'description' => 'Administrator']  ,
            ['role_name' => 'cashier' , 'description' => 'Kasir'] ,
            ['role_name' => 'chef' , 'description' => 'Koki'] , 
            ['role_name' => 'customer' , 'description' => 'Pelanggan'] 
        ] ;

        DB::table('roles')->insert($roles) ;
        
        }
    }
    
