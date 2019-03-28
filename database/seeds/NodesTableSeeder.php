<?php

use Illuminate\Database\Seeder;

class NodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('nodes')->insert([
            'lft'      => 0,
            'rgt'      => 1,
            'parentID' => 0,
        ]);
    }
}
