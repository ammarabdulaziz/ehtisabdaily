<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MotivationalQuoteController extends Controller
{
    public function __construct(
        private GeminiService $geminiService
    ) {}

    public function generate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'days_completed' => 'required|integer|min:0',
                'days_remaining' => 'required|integer|min:0',
                'percentage' => 'required|numeric|min:0|max:100',
            ]);

            Log::info('MotivationalQuoteController: Starting quote generation', [
                'days_completed' => $request->days_completed,
                'days_remaining' => $request->days_remaining,
                'percentage' => $request->percentage,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);

            $quote = $this->geminiService->generateMotivationalQuote(
                $request->days_completed,
                $request->days_remaining,
                $request->percentage
            );

            Log::info('MotivationalQuoteController: Quote generation completed', [
                'quote_received' => !empty($quote),
                'quote_data' => $quote,
            ]);

            // If quote generation failed (empty array), return error response
            if (empty($quote)) {
                Log::warning('MotivationalQuoteController: Empty quote returned from GeminiService', [
                    'days_completed' => $request->days_completed,
                    'days_remaining' => $request->days_remaining,
                    'percentage' => $request->percentage,
                ]);

                return response()->json([
                    'error' => 'Unable to generate motivational quote at this time',
                    'message' => 'Please try again later',
                    'debug_info' => [
                        'timestamp' => now()->toISOString(),
                        'request_id' => uniqid('req_', true),
                        'gemini_api_available' => !empty(config('services.gemini.api_key')),
                    ]
                ], 503); // Service Unavailable
            }

            return response()->json($quote);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('MotivationalQuoteController: Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('MotivationalQuoteController: Unexpected error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'gemini_api_key_configured' => !empty(config('services.gemini.api_key')),
            ]);
            throw $e;
        }
    }

    public function debug(): JsonResponse
    {
        try {
            $debugInfo = [
                'timestamp' => now()->toISOString(),
                'environment' => app()->environment(),
                'app_debug' => config('app.debug'),
                'gemini_api_key_configured' => !empty(config('services.gemini.api_key')),
                'gemini_api_key_length' => strlen(config('services.gemini.api_key') ?? ''),
                'gemini_base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'curl_available' => function_exists('curl_init'),
                'http_client_available' => class_exists(\Illuminate\Support\Facades\Http::class),
            ];

            // Test a simple API call to Gemini
            try {
                $testResponse = \Illuminate\Support\Facades\Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-goog-api-key' => config('services.gemini.api_key'),
                ])->timeout(10)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => 'Say "Hello" in JSON format: {"message": "Hello"}',
                                ],
                            ],
                        ],
                    ],
                ]);

                $debugInfo['gemini_test'] = [
                    'status' => $testResponse->status(),
                    'successful' => $testResponse->successful(),
                    'response_size' => strlen($testResponse->body()),
                    'has_content' => !empty($testResponse->json('candidates.0.content.parts.0.text')),
                ];

                if ($testResponse->successful()) {
                    $content = $testResponse->json('candidates.0.content.parts.0.text');
                    $debugInfo['gemini_test']['content_preview'] = substr($content ?? '', 0, 200);
                } else {
                    $debugInfo['gemini_test']['error_response'] = $testResponse->body();
                }
            } catch (\Exception $e) {
                $debugInfo['gemini_test'] = [
                    'error' => $e->getMessage(),
                    'error_type' => get_class($e),
                ];
            }

            return response()->json($debugInfo);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Debug endpoint failed',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}