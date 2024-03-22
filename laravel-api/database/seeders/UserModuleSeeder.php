<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class UserModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [

            ['user_id' => 1, 'module_id' => 1],
            ['user_id' => 1, 'module_id' => 2],
            ['user_id' => 1, 'module_id' => 3],
            ['user_id' => 1, 'module_id' => 4],
            ['user_id' => 1, 'module_id' => 5],
            ['user_id' => 1, 'module_id' => 6],

        ];

        DB::table('user_modules')->insert($rows);
    }
}
