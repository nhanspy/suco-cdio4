<?php

use App\Entities\Role;
use App\Entities\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleId = Role::where('name', 'SuperAdmin')->first()->id;

        $admin = Admin::create([
            'name' => 'Nam',
            'email' => 'admin@email.com',
            'password' => Hash::make('123123123'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        $admin->roles()->attach($roleId);

        $admin = Admin::create([
            'name' => 'Logger Nam',
            'email' => 'logernam@gmail.com',
            'password' => Hash::make('123123123'),
            'avatar' => '/images/default/auth/avatar2.jpg'
        ]);

        $admin->roles()->attach($roleId);

        $admin = Admin::create([
            'name' => 'Toan',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'avatar' => '/images/default/auth/avatar.jpg'
        ]);

        $admin->roles()->attach($roleId);

        factory(Admin::class, 10)->create()->each(function ($admin) use($roleId) {
            $admin->save();
            $admin->roles()->attach($roleId);
        });
    }
}
