<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            NodesTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemsTableSeeder::class,
            ]);
    }
}
