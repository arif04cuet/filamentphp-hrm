<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder, DB;

class UserRole extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 
        //
        DB::table('roles')->insert([
            [
                'id'          => 1,
                'name'        => 'SuperAdmin',
                'guard_name'  => 'web',
                'is_system'   => 1,
                'doptor_id'   => 0,
            ],
        ]);
    }
}
