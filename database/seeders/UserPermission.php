<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder, DB;

class UserPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 
        //
        DB::table('permissions')->insert([
            [
                'id'          => 1,
                'name'        => 'user_add',
                'guard_name'  => 'web',
            ],
            [
                'id'          => 2,
                'name'        => 'user_edit',
                'guard_name'  => 'web',
            ],
            [
                'id'          => 3,
                'name'        => 'user_delete',
                'guard_name'  => 'web',
            ],
            [
                'id'          => 4,
                'name'        => 'role_add',
                'guard_name'  => 'web',
            ],
            [
                'id'          => 5,
                'name'        => 'role_edit',
                'guard_name'  => 'web',
            ],
            [
                'id'          => 6,
                'name'        => 'role_assign',
                'guard_name'  => 'web',
            ]
        ]);
    }
}
