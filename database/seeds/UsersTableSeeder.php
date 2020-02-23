<?php

use App\User;
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
        User::create([
            'email' => 'john@gmail.com',
            'fullname' => 'John Doe',
            'password' => Hash::make('john123'),
            'gender' => 'male'
        ]);

        User::create([
            'email' => 'jane@gmail.com',
            'fullname' => 'Jane Doe',
            'password' => Hash::make('jane123'),
            'gender' => 'female'
        ]);
    }
}
