<?php

use Illuminate\Database\Seeder;
use App\Entities\Project;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Project::class, 50)->create();
    }
}
