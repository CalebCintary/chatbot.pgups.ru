<?php

namespace App\AI\ChromaDB;

class ChromaDBRecord
{
    public string $id;
    public string $document;
    public string $sourceUrl;
    public ChromaDBCollection $collection;

    /**
     * @param string $id
     * @param string $document
     * @param string $sourceUrl
     */
    public function __construct(ChromaDBCollection $collection, string $id, string $document, string $sourceUrl)
    {
        $this->collection = $collection;
        $this->id = $id;
        $this->document = $document;
        $this->sourceUrl = $sourceUrl;
    }
}
