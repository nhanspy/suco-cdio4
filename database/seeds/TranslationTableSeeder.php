<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Entities\Translation;
use App\Entities\Project;
use App\Entities\Role;
use Faker\Factory as Faker;

class TranslationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakerJapan = Faker::create('ja_JP');
        $fakerEng = Faker::create('en_US');

        $admins = Role::where('name', 'SuperAdmin')->first()->admins()->inRandomOrder()->get()->toArray();
        $projects = Project::all()->toArray();

        $insertData = [];

        for ($i = 0; $i < 3000; $i++) {
            $admin = $admins[array_rand($admins)];
            $project = $projects[array_rand($projects)];

            $insertData[] = [
                'admin_id' => $admin['id'],
                'project_id' => $project['id'],
                'phrase' => $fakerJapan->realText(55),
                'meaning' => $fakerEng->realText(155),
                'description' => $fakerEng->realText(450),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        $insertData = collect($insertData);
        $chunks = $insertData->chunk(1000);

        foreach ($chunks as $chunk) {
            Translation::insert($chunk->toArray());
        }

        Translation::reindex();
    }
}
