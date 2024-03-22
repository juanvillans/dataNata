<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Database\Seeders\ModuleSeeder;
use Database\Seeders\UserModuleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            ModuleSeeder::class,
            UserModuleSeeder::class,
            HierarchyEntitySeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            TypePresentationSeeder::class,
            TypeAdministrationSeeder::class,
            MedicamentSeeder::class,
            ProductSeeder::class,
            ConditionSeeder::class,
            OrganizationSeeder::class,
            EntrySeeder::class,
            // OutputSeeder::class,

        ]);

    }
}
