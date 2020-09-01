<?php

namespace App\Observers;

use App\Entities\Archive;
use App\Entities\Project;
use App\Entities\Translation;

class ProjectObserver
{
    /**
     * Handle the project "deleted" event.
     *
     * @param  Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        $translations = Translation::where('project_id', $project->id)->get(['id']);

        $dataIds = [];

        foreach ($translations as $translation) {
            $dataIds[] = $translation->id;
        }

        Archive::whereIn('translation_id', $dataIds)->delete();
        Translation::deleteIndexByProjectId($project->id);
        Translation::where('project_id', $project->id)->delete();
    }

    /**
     * Handle the project "restored" event.
     *
     * @param  Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        $translations = Translation::where('project_id', $project->id)->get(['id']);

        $dataIds = [];

        foreach ($translations as $translation) {
            $dataIds[] = $translation->id;
        }

        Archive::onlyTrashed()->whereIn('translation_id', $dataIds)->restore();
        Translation::onlyTrashed()->where('project_id', $project->id)->restore();
        Translation::where('project_id', $project->id)->get()->addToIndex();
    }
}
