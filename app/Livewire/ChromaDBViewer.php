<?php

namespace App\Livewire;

use App\AI\ChromaDB\ChromaDBClient;
use App\AI\ChromaDB\ChromaDBCollection;
use Codewithkyrian\ChromaDB\ChromaDB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ChromaDBViewer extends Component
{
    public $similaritySearch;
    public $sourceSearch;

    public $chromaCollection;

    public function records()
    {
        $chromaDBClient = new ChromaDBClient();
        $chromaCollections = $chromaDBClient->listCollections();

        $records = [];
        if (
            ($this->similaritySearch && $this->similaritySearch != "") ||
            ($this->sourceSearch && $this->sourceSearch != "")
        ) {
            $records = $chromaDBClient->queryCollectionRecords($chromaCollections[0], $this->similaritySearch, $this->sourceSearch);
        } else {
            $records = $chromaDBClient->getCollectionRecords($chromaCollections[0]);
        }

        return $records;
    }

    public function deleteRecord($collectionId, $recordId)
    {
        $chromaDBClient = new ChromaDBClient();
        $chromaDBClient->deleteRecordRaw($collectionId, $recordId);
    }

    public function render()
    {
        $chromaDBClient = new ChromaDBClient();
        return view('livewire.chroma-d-b', [
            'records' => $this->records(),
            'chromaCollections' => $chromaDBClient->listCollections(),
        ]);
    }
}
