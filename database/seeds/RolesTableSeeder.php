<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            [
                'slug' => 'admin',
                'name' => 'Admin',
                'permissions' => json_encode([
                    'users.list' => true,
                    'users.detail' => true,
                    'users.create' => true,
                    'users.update' => true,
                    'users.delete' => true,
                    'roles.list' => true,
                    'roles.detail' => true,
                    'roles.create' => true,
                    'roles.update' => true,
                    'roles.delete' => true,
                    'roles.permissions' => true,
                    'news.list' => true,
                    'news.detail' => true,
                    'news.create' => true,
                    'news.update' => true,
                    'news.delete' => true,
                    'news.comment' => true,
                    'news.like' => true,
                    'news.unlike' => true
                ])
            ],
            [
                'slug' => 'user',
                'name' => 'User',
                'permissions' => json_encode([
                    'users.list' => false,
                    'users.detail' => false,
                    'users.create' => false,
                    'users.update' => false,
                    'users.delete' => false,
                    'roles.list' => false,
                    'roles.detail' => false,
                    'roles.create' => false,
                    'roles.update' => false,
                    'roles.delete' => false,
                    'roles.permissions' => false,
                    'news.list' => true,
                    'news.detail' => true,
                    'news.create' => false,
                    'news.update' => false,
                    'news.delete' => false,
                    'news.comment' => true,
                    'news.like' => true,
                    'news.unlike' => true
                ])
            ]
        ]);
    }
}
