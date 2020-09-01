<?php

use App\Entities\Translation;
use App\Entities\User;
use App\Entities\Comment;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakerEng = Faker::create('en_US');

        $users = User::all();
        $translations = Translation::all();
        $insertData = [];

        for($i = 0; $i < 20000; $i++ ) {
            $user = $users->random();
            $translation = $translations->random();

            $insertData[] = [
                'user_id' => $user->id ,
                'translation_id' => $translation->id,
                'content' => $fakerEng->realText('93'),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
        }

        Comment::insert($insertData);
    }
}
