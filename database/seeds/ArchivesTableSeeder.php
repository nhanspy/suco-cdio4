<?php

use App\Entities\Translation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Entities\User;
use App\Entities\Archive;
use Illuminate\Support\Facades\DB;

class ArchivesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $translations = Translation::all();
        $insertData = [];

        for($i = 0; $i < 10000; $i++ ) {
            $user = $users->random();
            $translation = $translations->random();

            $insertData[] = [
                'user_id' => $user->id,
                'translation_id' => $translation->id,
                'project_id' => $translation->project_id,
                'device_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        $insertData = collect($insertData);
        $chunks = $insertData->chunk(1000);

        foreach ($chunks as $chunk) {
            Archive::insert($chunk->toArray());
        }
    }
}
