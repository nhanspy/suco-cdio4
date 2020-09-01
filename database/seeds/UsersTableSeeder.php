<?php

use App\Entities\User;
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
            'name' => 'Nam',
            'email' => 'nam@auth.com',
            'password' => Hash::make('123123123'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        User::create([
            'name' => 'Auth',
            'email' => 'auth@auth.com',
            'password' => Hash::make('12345678'),
            'avatar' => '/images/default/auth/avatar.jpg'
        ]);

        User::create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => Hash::make('12345678'),
            'avatar' => '/images/default/auth/avatar2.jpg',
        ]);

        User::create([
            'name' => 'Toan',
            'email' => 'toan@auth.com',
            'password' => Hash::make('12345678'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        User::create([
            'name' => 'Len Super Cute',
            'email' => 'nguyenthingoclen1613@gmail.com',
            'password' => Hash::make('12345678'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        User::create([
            'name' => 'Len Super Cute',
            'email' => 'nhutbui2903@gmail.com',
            'password' => Hash::make('12345678'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        User::create([
            'name' => 'Toan',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        User::create([
            'name' => 'Fooo Bar',
            'email' => 'TheAdministrator@admin.com',
            'password' => Hash::make('12345678'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        User::create([
            'name' => 'Nam is Super Man',
            'email' => 'logernam@gmail.com',
            'password' => Hash::make('123123123'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        factory(User::class, 250)->create();
    }
}
