<?php

namespace App\Observers;

use App\Entities\Archive;
use App\Entities\Translation;
use Exception;
use Illuminate\Support\Facades\Log;

class TranslationObserver
{
    /**
     * Handle the translation "created" event.
     *
     * @param  Translation  $translation
     * @return void
     */
    public function created(Translation $translation)
    {
        try {
            $translation->addToIndex();
        } catch (Exception $e) {
            Log::error("[ADD_TRANSLATION_INDEX][FAILURE] => " . $e->getMessage());
        }
    }

    /**
     * Handle the translation "updated" event.
     *
     * @param  Translation  $translation
     * @return void
     */
    public function updated(Translation $translation)
    {
        $translation->reindex();
    }

    /**
     * Handle the translation "deleted" event.
     *
     * @param  Translation  $translation
     * @return void
     */
    public function deleted(Translation $translation)
    {
        Archive::where('translation_id', $translation->id)->delete();
        $translation->removeFromIndex();
    }

    /**
     * Handle the translation "restored" event.
     *
     * @param  Translation  $translation
     * @return void
     */
    public function restored(Translation $translation)
    {
        try {
            Archive::onlyTrashed()->where('translation_id', $translation->id)->restore();

            $translation->addToIndex();
        } catch (Exception $e) {
            Log::error("[RESTORE_TRANSLATION_INDEX][FAILURE] => " . $e->getMessage());
        }
    }
}
