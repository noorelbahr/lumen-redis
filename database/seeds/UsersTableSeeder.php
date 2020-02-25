<?php

use App\User;
use App\Models\Role;
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
        $roleAdmin  = Role::where('slug', 'admin')->first();
        $roleUser   = Role::where('slug', 'user')->first();

        $admin = User::create([
            'email' => 'admin@gmail.com',
            'fullname' => 'John Doe',
            'password' => Hash::make('john123'),
            'gender' => 'male'
        ]);

        $user = User::create([
            'email' => 'user@gmail.com',
            'fullname' => 'Jane Doe',
            'password' => Hash::make('jane123'),
            'gender' => 'female'
        ]);

        $admin->roles()->attach($roleAdmin);
        $user->roles()->attach($roleUser);
    }
}
