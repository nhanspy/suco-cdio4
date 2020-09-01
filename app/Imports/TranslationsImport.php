<?php

namespace App\Imports;

use App\Repositories\ProjectRepository;
use App\Repositories\TranslationRepository;
use App\Services\Auth\AuthService;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class TranslationsImport implements OnEachRow, WithStartRow
{
    private $auth;

    private $startRow;

    /** @var TranslationRepository */
    private $translationRepo;

    /** @var ProjectRepository */
    private $projectRepo;

    public function __construct()
    {
        $this->auth = app(AuthService::class);
        $this->startRow = config('excel.sheet.start_row', 1);
        $this->translationRepo = app(TranslationRepository::class);
        $this->projectRepo = app(ProjectRepository::class);
    }

    public function startRow(): int
    {
        return $this->startRow;
    }

    public function onRow(Row $row)
    {
        $projectName = $row->getDelegate()->getWorksheet()->getTitle();

        $project = $this->projectRepo->where(['name' => $projectName])->first();

        $row = $row->toArray();

        if ($project && $row[1] && $row[2]) {
            $trans = $this->translationRepo->updateOrCreate(['phrase' => $row[1], 'meaning' => $row[2]], [
                'phrase' => $row[1],
                'meaning' => $row[2],
                'description' => $row[3],
                'admin_id' => $this->auth->guard('admin')->id(),
                'project_id' => $project->id
            ]);
        }
    }
}
