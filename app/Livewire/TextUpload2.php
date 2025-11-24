<?php

namespace App\Livewire;

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use Livewire\Attributes\Validate;
use Livewire\Component;
use MyChatBot;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\RAG\DataLoader\StringDataLoader;
use NeuronAI\RAG\Splitter\DelimiterTextSplitter;
use ScrapperAgent;

class TextUpload2 extends Component
{
    #[Validate('required|url')]
    public $source;
    #[Validate('required')]
    public $text;

    public function extractText()
    {
        $client = new Client();
        $response = $client->request('GET', $this->source);
        $this->text = $response->getBody()->getContents();

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($this->text);

        $domXpath = new DOMXPath($dom);
        $query = $domXpath->query('//*[@class="page-content"]//text()');
        $fullPageText = "";
        foreach ($query as $node) {
            $textContent = trim($node->textContent);
            if ($textContent && $textContent != '') {
                $fullPageText .= $textContent . " ";
            }
        }

        $this->text = trim($fullPageText);
    }

    public function enhanceText()
    {
        $chat = ScrapperAgent::make()->chat(new UserMessage($this->text));
        $this->text = $chat->getContent();
    }

    public function submit()
    {
        $this->validate();

        $documents = StringDataLoader::for($this->text)
            ->withSplitter(
                new DelimiterTextSplitter(
                    maxLength: 1000,
                    separator: PHP_EOL,
                    wordOverlap: 0
                )
            )
            ->getDocuments();
        foreach ($documents as $document) {
            $document->addMetadata('url', $this->source);
        }
        MyChatBot::make()->addDocuments($documents);
    }

    public function render()
    {
        return view('livewire.text-upload2');
    }
}
