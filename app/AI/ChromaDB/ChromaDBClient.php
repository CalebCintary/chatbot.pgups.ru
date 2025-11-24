<?php

namespace App\AI\ChromaDB;

use GuzzleHttp\Client;
use App\AI\Models\MyChatBot;

class ChromaDBClient
{
    private string $url;
    private string $tenant;
    private string $database;


    private Client $httpClient;

    public function __construct(
        string $url = 'http://localhost:8080/',
        string $tenant = 'default_tenant',
        string $database = 'default_database',
    )
    {
        $this->httpClient = new Client();
        $this->tenant = $tenant;
        $this->database = $database;
        $this->url = $url;
    }

    public function listCollections()
    {
        $response = $this->httpClient->request('GET',
            $this->url . 'api/v2/tenants/' . $this->tenant . '/databases/' . $this->database . '/collections');
        $collections = json_decode($response->getBody()->getContents());
        $realCollections = [];
        foreach ($collections as $collection) {
            $realCollections[] = new ChromaDBCollection(
                $collection->id,
                $collection->name,
                $collection->tenant,
                $collection->database,
            );
        }
        return $realCollections;
    }

    public function getCollectionRecords(ChromaDBCollection $collection): array
    {
        $response = $this->httpClient->request('POST',
            $this->url . "api/v2/tenants/$this->tenant/databases/$this->database/collections/$collection->id/get",
            [
                'body' => '{ "n_result": 5 }'
            ]
        );

        $contents = $response->getBody()->getContents();
        $collectionRecords = json_decode($contents);
        $realCollectionRecords = [];
        for ($i = 0; $i < count($collectionRecords->ids); $i++) {
            $realCollectionRecords[] = new ChromaDBRecord(
                $collection,
                $collectionRecords->ids[$i],
                $collectionRecords->documents[$i],
                $collectionRecords->metadatas[$i]->url,
            );
        }

        return $realCollectionRecords;
    }

    public function queryCollectionRecords(ChromaDBCollection $collection, string $query = null, string $url = null): array
    {
        $var = [
            "query_embeddings" => [
                MyChatBot::make()->resolveEmbeddingsProvider()->embedText($query),
            ]
        ];
        if ($url) {
            $var['where'] = [
                "url" => $url,
            ];
        }
        $response = $this->httpClient->request('POST',
            $this->url . "api/v2/tenants/$this->tenant/databases/$this->database/collections/$collection->id/query",
            [
                "json" => $var
            ]
        );

        $contents = $response->getBody()->getContents();
        $collectionRecords = json_decode($contents);
        $realCollectionRecords = [];
        for ($i = 0; $i < count($collectionRecords->ids[0]); $i++) {
            $realCollectionRecords[] = new ChromaDBRecord(
                $collection,
                $collectionRecords->ids[0][$i],
                $collectionRecords->documents[0][$i],
                $collectionRecords->metadatas[0][$i]->url,
            );
        }

        return $realCollectionRecords;
    }

    public function deleteRecord(ChromaDBRecord $record)
    {
        $response = $this->httpClient->request('post',
            $this->url . "api/v2/tenants/$this->tenant/databases/$this->database/collections/$record->collection->id/delete", [
                'json' => [
                    'ids' => [
                        $record->id
                    ]
                ]
            ]);
    }

    public function deleteRecordRaw(string $collectionId, string $recordId)
    {
        $response = $this->httpClient->request('post',
            $this->url . "api/v2/tenants/$this->tenant/databases/$this->database/collections/$collectionId/delete", [
                'json' => [
                    'ids' => [
                        $recordId
                    ]
                ]
            ]);
    }

}
