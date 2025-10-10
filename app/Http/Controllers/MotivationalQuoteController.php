<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MotivationalQuoteController extends Controller
{
    public function __construct(
        private GeminiService $geminiService
    ) {}

    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'days_completed' => 'required|integer|min:0',
            'days_remaining' => 'required|integer|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $quote = $this->geminiService->generateMotivationalQuote(
            $request->days_completed,
            $request->days_remaining,
            $request->percentage
        );

        // If quote generation failed (empty array), return error response
        if (empty($quote)) {
            return response()->json([
                'error' => 'Unable to generate motivational quote at this time',
                'message' => 'Please try again later'
            ], 503); // Service Unavailable
        }

        return response()->json($quote);
    }
}