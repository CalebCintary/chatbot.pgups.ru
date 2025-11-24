<?php

namespace App\AI\ChromaDB;

class ChromaDBCollection
{
    public string $id;
    public string $name;
    public string $tenant;
    public string $database;

//    private ChromaDBClient $client;

    /**
     * @param string $id
     * @param string $name
     * @param string $tenant
     * @param string $database
     */
    public function __construct(string $id, string $name, string $tenant, string $database)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tenant = $tenant;
        $this->database = $database;
    }
}
