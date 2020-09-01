<?php

use App\Entities\Like;
use App\Entities\Translation;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikesTableSeeder extends Seeder
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

        for($i = 0; $i < 30000; $i++ ) {
            $user = $users->random();
            $translation = $translations->random();

            $insertData[] = [
                'user_id' => $user->id ,
                'translation_id' => $translation->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        $insertData = collect($insertData);
        $chunks = $insertData->chunk(1000);

        foreach ($chunks as $chunk) {
            Like::insert($chunk->toArray());
        }
    }
}
