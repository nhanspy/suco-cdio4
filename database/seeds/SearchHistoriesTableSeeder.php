<?php

use App\Entities\SearchHistory;
use App\Entities\Translation;
use App\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchHistoriesTableSeeder extends Seeder
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

        for ($i = 0; $i <= 10000; $i++) {
            $translation = $translations->random();
            $user = $users->random();

            $insertData[] = [
                'user_id' => $user->id,
                'device_id' => null,
                'translation_id' => $translation->id,
                'keyword' => Str::random(10)
            ];

            $translation->total_search = SearchHistory::where('translation_id', $translation->id)->count();
            $translation->save();
        }

        $insertData = collect($insertData);
        $chunks = $insertData->chunk(1000);

        foreach ($chunks as $chunk) {
            SearchHistory::insert($chunk->toArray());
        }
    }
}
