<?php

namespace App\Services;

use App\Entities\TranslationStatistic;
use App\Exports\TemplateExport;
use App\Exports\TranslationsExport;
use App\Imports\TranslationsImport;
use App\Repositories\ProjectRepository;
use App\Repositories\SearchHistoryRepository;
use App\Repositories\TranslationHistoryRepository;
use App\Repositories\TranslationRepository;
use App\Services\Auth\AuthService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TranslationService
{
    /** @var TranslationRepository */
    private $translationRepo;

    /** @var ProjectRepository */
    private $projectRepo;

    /** @var TranslationHistoryRepository */
    private $historyRepo;

    /** @var SearchHistoryRepository */
    private $searchHisRepo;

    /** @var AuthService */
    private $auth;

    /** @var String */
    private $filePath;

    public function __construct()
    {
        $this->translationRepo = app(TranslationRepository::class);
        $this->projectRepo = app(ProjectRepository::class);
        $this->historyRepo = app(TranslationHistoryRepository::class);
        $this->auth = app(AuthService::class);
        $this->searchHisRepo = app(SearchHistoryRepository::class);
        $this->filePath = config('upload.import.file_path', '');
    }

    public function all($perPage = null)
    {
        $result = $this->translationRepo->orderBy('id', 'desc')->paginate($perPage);

        return $result;
    }

    /**
     * @param $translationId
     * @return mixed
     * @throws Exception
     */
    public function show($translationId)
    {
        $translation = $this->translationRepo->with('admin')->with('project')->findOrFail($translationId);
        $translation['liked'] = $this->auth->like()->hasLiked($translationId);
        $translation['archived'] = $this->auth->archive()->hasArchived($translationId);

        return $this->transformProjectDetail($translation);
    }

    public function typeExists()
    {
        return $this->translationRepo->typeExists();
    }

    public function addAllToIndex()
    {
        return $this->translationRepo->addAllToIndex();
    }

    public function createIndex()
    {
        return $this->translationRepo->createIndex();
    }

    public function search($key, $projects, $columns = ['*'], $page = 1, $perPage = null)
    {
        $result = $this->translationRepo->search($key, $projects, $columns, $page, $perPage)->paginate($perPage);

        // haven't found a way to not showing hits property
        $result->hits = null;

        return $result;
    }

    public function create($projectId, $translationData)
    {
        $project = $this->projectRepo->findOrFail($projectId);

        DB::beginTransaction();

        try {
            $translationData['admin_id'] = $this->auth->guard('admin')->id();
            $translation = $this->translationRepo->create($translationData);

            $translation->projects()->attach($project->id);

            DB::commit();

            return $translation;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e);
        }
    }

    public function update($translationId, $translationData)
    {
        $translation = $this->translationRepo->findOrFail($translationId)->toArray();

        DB::beginTransaction();

        try {
            // move  a translation to history
            $translation['translation_id'] = $translation['id'];
            $this->historyRepo->create($translation);

            $translationData['admin_id'] = $this->auth->guard('admin')->id();
            $translation = $this->translationRepo->update($translationId, $translationData);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e);
        }

        return $translation;
    }

    public function delete($translationId)
    {
        $translation = $this->translationRepo->findOrFail($translationId);

        DB::beginTransaction();

        try {
            $result = $this->translationRepo->delete($translationId);

            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e);
        }
    }

    public function exportProject($projects)
    {
        $validProject = [];

        if ($projects) {
            foreach ($projects as $projectId) {
                $project = $this->projectRepo->find($projectId);
                if ($project) {
                    $validProject[] = $project;
                }
            }
        } else {
            $validProject = $this->projectRepo->get();
        }

        return Excel::download(new TranslationsExport($validProject), 'translations.xlsx');
    }

    public function exportTemplate()
    {
        return Excel::download(new TemplateExport(), 'template.xlsx');
    }

    public function import($filepath)
    {
        $import = new TranslationsImport();
        Excel::import($import, $filepath);
    }

    /**
     * @param $file
     * @return bool
     */
    public function uploadFile($file)
    {
        $fileName = uniqid('excel_') . '.' . $file->extension();

        $result = Storage::putFileAs($this->filePath, $file, $fileName);

        return Storage::disk('local')->path($result);
    }

    public function getTopSearch($perPage = null)
    {
        return $this->translationRepo->orderBy('total_search', 'DESC')
            ->paginate($perPage);
    }

    public function getTopLike($perPage = null)
    {
        return $this->translationRepo->orderBy('total_like', 'DESC')
            ->paginate($perPage);
    }

    public function getTopComment($perPage = null)
    {
        return $this->translationRepo->orderBy('total_comment', 'DESC')
            ->paginate($perPage);
    }

    private function transformProjectDetail($translation)
    {
        $translation['related_project'] = [['id' => $translation->project->id, 'name' => $translation->project->name]];
        unset($translation->project);

        return $translation;
    }

    public function getRecentSearch($perPage = null)
    {
        return $this->searchHisRepo->with('user')->with('translation')
            ->orderBy('id', 'DESC')
            ->paginate($perPage);
    }

    public function count()
    {
        return $this->translationRepo->count();
    }

    public function searchCount()
    {
        return $this->searchHisRepo->count();
    }
}
