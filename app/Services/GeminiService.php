<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;

    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function generateDuaContent(string $glimpse): array
    {
        try {
            $prompt = $this->buildPrompt($glimpse);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => $this->apiKey,
            ])->post($this->baseUrl, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');

                return $this->parseResponse($content);
            }

            Log::error('Gemini API error', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Gemini service error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'glimpse' => $glimpse,
            ]);

            return [];
        }
    }

    private function buildPrompt(string $glimpse): string
    {
        return "Based on this glimpse of a dua: '{$glimpse}', please provide the following information in JSON format:

{
    \"title\": \"A concise, descriptive title for this dua\",
    \"arabic_text\": \"The complete Arabic text of the dua\",
    \"english_translation\": \"Direct English translation\",
    \"transliteration\": \"Phonetic pronunciation guide\",
    \"english_meaning\": \"Detailed explanation and context\",
    \"categories\": [\"array of relevant categories\"],
    \"source\": \"Islamic source (Quran, Hadith, etc.)\",
    \"reference\": \"Specific reference if available\",
    \"benefits\": \"Benefits and virtues of reciting this dua\",
    \"recitation_count\": \"number of times to recite\",
}

Please ensure the response is valid JSON and all fields are appropriate for Islamic duas. If any information cannot be determined from the glimpse, use appropriate default values or leave as empty string.";
    }

// \"occasions\": [\"array of relevant occasions\"],
// \"tags\": [\"relevant tags for organization\"]

    private function parseResponse(string $content): array
    {
        try {
            // Clean the response content
            $cleanedContent = trim($content);

            // Try to extract JSON from the response
            if (preg_match('/\{.*\}/s', $cleanedContent, $matches)) {
                $jsonContent = $matches[0];
                $parsed = json_decode($jsonContent, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $this->sanitizeData($parsed);
                }
            }

            Log::warning('Failed to parse Gemini response as JSON', ['content' => $content]);

            return [];

        } catch (\Exception $e) {
            Log::error('Error parsing Gemini response', [
                'message' => $e->getMessage(),
                'content' => $content,
            ]);

            return [];
        }
    }

    private function sanitizeData(array $data): array
    {
        // Ensure all expected fields exist with proper types

        $sanitized = [
            'title' => $data['title'] ?? '',
            'arabic_text' => $data['arabic_text'] ?? '',
            'english_translation' => $data['english_translation'] ?? '',
            'transliteration' => $data['transliteration'] ?? '',
            'english_meaning' => $data['english_meaning'] ?? '',
            'categories' => is_array($data['categories'] ?? null) ? $data['categories'] : [],
            // 'occasions' => is_array($data['occasions'] ?? null) ? $data['occasions'] : [],
            'source' => $data['source'] ?? '',
            'reference' => $data['reference'] ?? '',
            'benefits' => $data['benefits'] ?? '',
            'recitation_count' => is_numeric($data['recitation_count'] ?? null) ? (int) $data['recitation_count'] : 1,
            // 'tags' => is_array($data['tags'] ?? null) ? collect($data['tags'])->map(fn ($tag) => ucfirst($tag))->toArray() : [],
        ];

        // Validate recitation count
        if ($sanitized['recitation_count'] < 1 || $sanitized['recitation_count'] > 100) {
            $sanitized['recitation_count'] = 1;
        }

        return $sanitized;
    }
}
