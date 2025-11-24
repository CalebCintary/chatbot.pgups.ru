<?php

namespace App\Livewire;

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use MyChatBot;
use NeuronAI\RAG\DataLoader\StringDataLoader;

class Scrapper extends Component
{
    public $linksToSearch = [''];
    public $searchedLinks = [];
    public $regexFilter = "";

    public function searchLinks()
    {
        $client = new Client();
        $links = [];

        foreach ($this->linksToSearch as $link) {
            $response = $client->request('GET', $link);
            $body = $response->getBody()->getContents();

            $dom = new DOMDocument();
            $dom->loadXML($body);

            foreach ($dom->getElementsByTagName('loc')->getIterator() as $loc) {
                $links[] = $loc->textContent;
            }
        }

        $this->searchedLinks = array_unique($links);

        Log::info('');
    }

    public function rescrap()
    {
        $this->linksToSearch = $this->searchedLinks;
        $this->searchedLinks = [];
        $this->searchLinks();
    }

    public function applyFilter()
    {
        $links = [];
        foreach ($this->searchedLinks as $searchedLink) {
            if (preg_match($this->regexFilter, $searchedLink)) {
                $links[] = $searchedLink;
            }
        }

        $this->searchedLinks = array_unique($links);
    }

    public function collectInfo()
    {
        $client = new Client();
        $chatBot = MyChatBot::make();
//        Flux::modal('progress')->show();
        $i = 0;
        foreach ($this->searchedLinks as $link) {
            try {
                $response = $client->request('GET', $link);
                $body = $response->getBody()->getContents();

                if (!$body || $body == '') {
                    Log::warning('Empty response: ' . $link);
                    continue;
                }

                libxml_use_internal_errors(true);
                $dom = new DOMDocument();
                $dom->loadHTML($body);

                $domXpath = new DOMXPath($dom);
                $query = $domXpath->query('//*[@class="page-content"]//text()');
                $fullPageText = "";
                foreach ($query as $node) {
                    $textContent = trim($node->textContent);
                    if ($textContent && $textContent != '') {
                        $fullPageText .= $textContent . " ";
                    }
                }

                $fullPageText = trim($fullPageText);
//                $chat = ScrapperAgent::make()->chat(
//                    new UserMessage($body)
//                );


                if ($fullPageText && $fullPageText != '') {
                    try {
                        $documents = StringDataLoader::for($fullPageText)->getDocuments();
                        foreach ($documents as $document) {
                            $document->addMetadata('url', $link);
                        }
                        $chatBot->addDocuments($documents);
                    } catch (ServerException $e) {
                        Log::error($e->getMessage(), [
                            'link' => $link,
                            'fullText' => $fullPageText,
                        ]);
                    }
                }
                usleep(100000);

                $this->stream('progress', $i . '/' . count($this->searchedLinks), replace: true);
            } catch (ClientException|ServerException $e) {
                Log::error($e->getMessage());
            } finally {
                ++$i;
            }
        }
    }

    public function test()
    {
        for ($i = 0; $i < 5; $i++) {

            usleep(1000000);
        }
    }

    public function render()
    {
//        Flux::modal('progress')->show();
        return view('livewire.scrapper');
    }
}
