<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TranslationsExport implements WithMultipleSheets
{
    /**
     * Hold array of project name
     * @var array
     */
    private $projects;

    public function __construct($projects = [])
    {
        $this->projects = $projects;
    }

    public function sheets(): array
    {
        $sheet = [];

        foreach ($this->projects as $project) {
            $sheet[] = new TemplateExport($project);
        }

        return $sheet;
    }
}
