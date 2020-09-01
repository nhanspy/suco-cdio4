<?php

namespace App\Observers;

use App\Entities\Translation;
use App\Entities\Like;

class LikeObserver
{
    /**
     * Handle the like "created" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function created(Like $like)
    {
        $translation = Translation::where('id', $like->translation_id)->first();
        $like_count =  Like::where('translation_id', $like->translation_id)->count();

        $translation->total_like = $like_count;

        $translation->save();
    }

    /**
     * Handle the like "deleted" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function deleted(Like $like)
    {
        $translation = Translation::where('id', $like->translation_id)->first();
        $like_count =  Like::where('translation_id', $like->translation_id)->count();

        $translation->total_like = $like_count;

        $translation->save();
    }
}
