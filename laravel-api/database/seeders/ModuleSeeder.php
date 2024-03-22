<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['name' => 'Modulo de Organizaciones'],
            ['name' => 'Modulo de Usuarios'],
            ['name' => 'Modulo de Productos'],
            ['name' => 'Modulo de Entradas'],
            ['name' => 'Modulo de Salidas'],
            ['name' => 'Modulo de Inventario'],
        ];

        DB::table('modules')->insert($rows);
    }
}
