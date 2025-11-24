<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MyChatBot;
use NeuronAI\Chat\Messages\UserMessage;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $prompt = $request->prompt;
        return MyChatBot::make()->chat(
            new UserMessage($prompt)
        );
    }
}
