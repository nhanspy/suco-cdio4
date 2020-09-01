<?php

namespace App\Observers;

use App\Entities\Comment;
use App\Entities\Translation;
use Exception;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $translation = Translation::where('id', $comment->translation_id)->first();

        $translation->total_comment = Comment::where('translation_id', $translation->id)->count();

        $translation->save();
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        try {
            $translation = Translation::where('id', $comment->translation_id)->first();

            $translation->total_comment = Comment::where('translation_id', $translation->id)->count();

            $translation->save();
        } catch (Exception $e) {
            $comment->restore();
        }
    }
}
