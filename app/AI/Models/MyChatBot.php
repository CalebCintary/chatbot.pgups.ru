<?php

declare(strict_types=1);

namespace App\AI\Models;


use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\HttpClientOptions;
use NeuronAI\Providers\Ollama\Ollama;
use NeuronAI\RAG\Embeddings\EmbeddingsProviderInterface;
use NeuronAI\RAG\Embeddings\OllamaEmbeddingsProvider;
use NeuronAI\RAG\RAG;
use NeuronAI\RAG\VectorStore\ChromaVectorStore;
use NeuronAI\RAG\VectorStore\VectorStoreInterface;
use NeuronAI\SystemPrompt;

class MyChatBot extends RAG
{
    protected function provider(): AIProviderInterface
    {
        return new Ollama(
            url: 'http://localhost:11434/api',
            model: 'deepseek-r1:14b',
            parameters: [],
            httpOptions: new HttpClientOptions(timeout: 300)
        );
    }

    public function instructions(): string
    {
        $str = "Ты — ассистент университета Петербургский Государственный Университет Путей Сообщения. Ты экспертно разбираешься в:
    - Учебных процессах и правилах
    - Документообороте университета
    - Структуре подразделений
    - Академических процедурах


    Правила ответа:
    1. Опирайся ТОЛЬКО на предоставленную базу знаний
    2. Если информации нет - не выдумывай
    3. Сохраняй официально-дружелюбный тон
    4. Выводи информацию в Markdown";
        return (string) new SystemPrompt(
            background: [$str],
        );
    }

    protected function embeddings(): EmbeddingsProviderInterface
    {
        return new OllamaEmbeddingsProvider(
            model: 'nomic-embed-text',
            url: 'http://localhost:11434/api',
        );
    }

//    protected function preProcessors(): array
//    {
//        return [
//            new QueryTransformationPreProcessor(
//                provider: $this->resolveProvider(),
//                transformation: QueryTransformationType::REWRITING,
//            ),
//        ];
//    }

    protected function vectorStore(): VectorStoreInterface
    {
        return new ChromaVectorStore(
            collection: 'neuron-ai',
            host: 'http://localhost:8080',
            topK: 8
        );
    }
}
