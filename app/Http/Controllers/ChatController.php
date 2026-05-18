<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'string',
        ]);

        $userMessage = $validated['message'];

        $apiKey = env('GROQ_API_KEY');
        if ($validated) {
            $response = Http::withToken($apiKey)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ]
            ]);
            if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'GROQ API request failed',
                'error' => $response->json()
            ], 500);
        }

        $reply = $response->json()['choices'][0]['message']['content'] ?? 'No response';

        return response()->json([
            'success' => true,
            'user_message' => $userMessage,
            'reply' => $reply
        ]);

        }

        return response()->json([
            'success' => false,
            'message' => 'Validation failed'
        ], 422);
    }
}
