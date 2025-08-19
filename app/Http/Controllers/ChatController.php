<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class ChatController extends Controller
{
    public function generate(Request $request, GeminiService $gemini) {
        $prompt = $request->input('prompt');
        $response = $gemini->generatedContent($prompt);

        return response()->json([
            'prompt' => $prompt,
            'response' => $response
        ]);
    }
}
