<?php

namespace App\Traits;

use Elasticsearch\ClientBuilder;

trait CustomElasticsearchTrait
{
    public static function deleteIndexByProjectId($projectId)
    {
        $instance = new static;

        $query = [
            'index' => $instance->getIndexName(),
            'type' => null,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'match_all' => (object) []
                        ],
                        'filter'=> [
                            'terms' => [
                                'project_id' => [$projectId]
                            ]
                        ]
                    ]
                ],
                'size' => 9999
            ]
        ];

        return $instance->deleteByQuery($query);
    }

    public function deleteByQuery(array $query)
    {
        $clientBuilder = new ClientBuilder();
        $client = $clientBuilder->build();

        return $client->deleteByQuery($query);
    }
}
