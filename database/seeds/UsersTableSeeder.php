<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'firstName' => Str::random(10),
            'lastName' => Str::random(10),
            'middleName' => Str::random(1),
            'email' => 'test@gmail.com',
            'password' => Hash::make('123123'),
        ]);
    }
}
