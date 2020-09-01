<?php

use App\Entities\Notification;
use App\Entities\Project;
use Illuminate\Database\Seeder;

class ProjectNotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifies = Notification::all();

        foreach ($notifies as $notify) {
            $projectIds = Project::inRandomOrder()->offset(0)->take(3)->get()->map(function ($project) {
               return $project->id;
            });

            $notify->projects()->attach($projectIds);
        }
    }
}
