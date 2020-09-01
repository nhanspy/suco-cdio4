<?php

namespace App\Repositories;

use App\Entities\Translation;

class TranslationRepository extends BaseRepository
{
    public function model()
    {
        return Translation::class;
    }

    public function createIndex()
    {
        $response = $this->model->createIndex($shards = null, $replicas = null);

        $this->model->putMapping($ignoreConflicts = false);

        return $response;
    }

    public function addAllToIndex()
    {
        return $this->model->addAllToIndex();
    }

    public function typeExists()
    {
        return $this->model->typeExists();
    }

    public function searchByQuery($query)
    {
        return $this->model->searchByQuery($query);
    }

    public function search($key, $project, $columns = ['*'], $page = 1, $perPage = null)
    {
        $perPage = $perPage ?: config('repository.pagination.limit', 15);
        $filterProject = [];

        if ($project) {
            $filterProject = [
                'terms' => [
                    'project_id' => $project
                ],
            ];
        }

        if (!$key) {
            $queryString = [
                'query_string' => [
                    'query' => '*',
                    'fields' => $columns
                ]
            ];
        } else {
            $queryString = [
                'multi_match' => [
                    'query' => $key,
                    'type' => 'phrase_prefix',
                    'fields' => $columns
                ]
            ];
        }

        $queryComplexData = [
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $queryString,
                        'filter'=> $filterProject
                    ]
                ],
                'from' => $perPage * ($page - 1),
                'size' => $perPage
            ]
        ];

        $this->model = $this->model->complexSearch($queryComplexData);

        return $this;
    }

    private function isJapanese($text)
    {
        return preg_match('/[\x{4E00}-\x{9FBF}\x{3040}-\x{309F}\x{30A0}-\x{30FF}]/u', $text);
    }
}
