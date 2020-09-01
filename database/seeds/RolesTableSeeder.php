<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'SuperAdmin',
                'description' => 'The highest Administrator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Comtor',
                'description' => 'Who will write translation for each project',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
