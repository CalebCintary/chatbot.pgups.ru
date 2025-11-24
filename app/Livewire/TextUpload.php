<?php

namespace App\Livewire;

use app\ChromaDBClient;
use Livewire\Attributes\Validate;
use Livewire\Component;
use MyChatBot;
use NeuronAI\RAG\DataLoader\StringDataLoader;

class TextUpload extends Component
{
    #[Validate('required')]
    public $text;

    #[Validate('required|url')]
    public $source;

    public function render()
    {
        return view('livewire.text-upload');
    }

    public function submit()
    {
        $this->validate();

        $documents = StringDataLoader::for($this->text)->getDocuments();
        foreach ($documents as $document) {
            $document->addMetadata('url', $this->source);
        }
        MyChatBot::make()->addDocuments($documents);
    }
}
