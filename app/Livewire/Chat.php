<?php

namespace App\Livewire;

use Livewire\Component;
use MyChatBot;
use NeuronAI\Chat\Messages\UserMessage;

class Chat extends Component
{
    public $userPrompt;

    public $messages = [];

    public $modelAnswer = "";

    public function sendMessage()
    {
        $this->messages[] = [
            'type' => 'user',
            'prompt' => $this->userPrompt,
            'modelAnswer' => ""
        ];
        $this->stream('user-message', $this->userPrompt);
        $stream = MyChatBot::make()->stream(
            new UserMessage($this->userPrompt)
        );
        $modelAnswer = '';
        foreach ($stream as $s) {
            $this->modelAnswer .= $s;
            $this->stream('model-stream-answer', $s);
        }

        $this->messages[count($this->messages) - 1]['modelAnswer'] = $this->modelAnswer;
        $this->modelAnswer = '';
        $this->userPrompt = '';

        $this->dispatch('loading-completed')->self();
    }

    public function sendMessage2()
    {

    }

    public function randomPlaceholder(): string
    {
        return [
            'Напиши, как Леше бы отреагировал на этот вопрос...',
            'Спроси у Мальца, как он считает, что ты должен...',
            'Проверь, как Малець бы ответил на этот вопрос, если бы он был в курсе...',
            'Скажи Мальцу, что ты сделал, пока он был в отпуске...',
            'Напомни Мальцу, что ты тоже умеешь думать, не только он! '
        ][rand(0, 4)];
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
