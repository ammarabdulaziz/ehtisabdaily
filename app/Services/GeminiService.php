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

    public function generateMotivationalQuote(int $daysCompleted, int $daysRemaining, float $percentage, ?int $nearMilestone = null): array
    {
        try {
            $prompt = $this->buildMotivationalPrompt($daysCompleted, $daysRemaining, $percentage, $nearMilestone);

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
                return $this->parseMotivationalResponse($content);
            }

            Log::error('Gemini API error for motivational quote', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Gemini service error for motivational quote', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
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

    private function buildMotivationalPrompt(int $daysCompleted, int $daysRemaining, float $percentage, ?int $nearMilestone = null): string
    {
        $basePrompt = "Generate a motivational quote for someone on a personal growth journey. The person has completed {$daysCompleted} days, has {$daysRemaining} days remaining, and is {$percentage}% through their journey to Ramadan 2026.";

        $milestoneGuidance = "";
        if ($nearMilestone !== null) {
            $milestoneGuidance = "

IMPORTANT: This person is at or near a significant milestone ({$nearMilestone} days). This is a critical time when people are most vulnerable to relapse. The quote should:
- Emphasize staying strong and not giving up
- Acknowledge the milestone achievement while warning against complacency
- Include specific relapse prevention messaging
- Be more cautious and protective in tone
- Focus on maintaining momentum rather than celebrating too much";
        }

        $jsonFields = $nearMilestone !== null ? 
            '{
    "quote": "A motivational quote (mix Islamic, non-Islamic, and realistic perspectives)",
    "type": "islamic|general|realistic",
    "context": "Brief context about why this quote is relevant to their current progress",
    "quranic_verse": {
        "arabic": "Arabic text of a relevant Quranic verse that supports the motivational message",
        "translation": "English translation of the verse",
        "reference": "Surah Name (Chapter:Verse) - e.g., Al-Baqarah (2:286)"
    },
    "milestone_warning": {
        "message": "Specific relapse prevention message for this milestone",
        "milestone_days": ' . $nearMilestone . '
    }
}' :
            '{
    "quote": "A motivational quote (mix Islamic, non-Islamic, and realistic perspectives)",
    "type": "islamic|general|realistic",
    "context": "Brief context about why this quote is relevant to their current progress",
    "quranic_verse": {
        "arabic": "Arabic text of a relevant Quranic verse that supports the motivational message",
        "translation": "English translation of the verse",
        "reference": "Surah Name (Chapter:Verse) - e.g., Al-Baqarah (2:286)"
    }
}';

        return $basePrompt . $milestoneGuidance . "

Please provide the response as a single JSON object (not an array) with these fields:

" . $jsonFields . "

Guidelines:
- Return ONLY a single JSON object, not an array
- Include Islamic wisdom, general motivation, and realistic encouragement
- Make it relevant to their current progress stage
- Keep quotes between 10-30 words
- Be encouraging but realistic
- Mix different types of motivation (spiritual, practical, emotional)
- Consider their progress percentage when crafting the message
- ALWAYS include a relevant Quranic verse that supports and enhances the motivational message, Maybe the rewards which Allah tells in the Quran for those who strive to struggle in the way of Allah.
- Select verses that align with themes like patience, perseverance, gratitude, trust in Allah, reward from allah or steadfastness
- Ensure the verse complements the quote rather than repeating the same message
- Do not wrap the response in markdown code blocks";
    }

    private function parseMotivationalResponse(string $content): array
    {
        try {
            // Clean the response content
            $cleanedContent = trim($content);

            // Remove markdown code blocks if present - handle both ```json and ``` formats
            $cleanedContent = preg_replace('/```(?:json)?\s*/', '', $cleanedContent);
            $cleanedContent = preg_replace('/```\s*$/', '', $cleanedContent);
            $cleanedContent = trim($cleanedContent);

            // Try to extract JSON from the response (both single object and array)
            if (preg_match('/\[.*\]/s', $cleanedContent, $arrayMatches)) {
                // Handle array response
                $jsonContent = $arrayMatches[0];
                $parsed = json_decode($jsonContent, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($parsed) && !empty($parsed)) {
                    // Return the first quote from the array
                    return $this->sanitizeMotivationalData($parsed[0]);
                }
            } elseif (preg_match('/\{.*\}/s', $cleanedContent, $objectMatches)) {
                // Handle single object response
                $jsonContent = $objectMatches[0];
                $parsed = json_decode($jsonContent, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $this->sanitizeMotivationalData($parsed);
                }
            }

            // If the above didn't work, try parsing the entire cleaned content
            $parsed = json_decode($cleanedContent, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                return $this->sanitizeMotivationalData($parsed);
            }

            Log::warning('Failed to parse Gemini motivational response as JSON', [
                'content' => $content,
                'cleaned_content' => $cleanedContent
            ]);

            return [];

        } catch (\Exception $e) {
            Log::error('Error parsing Gemini motivational response', [
                'message' => $e->getMessage(),
                'content' => $content,
            ]);

            return [];
        }
    }

    private function sanitizeMotivationalData(array $data): array
    {
        $sanitized = [
            'quote' => $data['quote'] ?? 'Keep going, you\'re doing great!',
            'type' => in_array($data['type'] ?? '', ['islamic', 'general', 'realistic']) ? $data['type'] : 'general',
            'context' => $data['context'] ?? 'Stay strong on your journey!',
        ];

        // Add Quranic verse if present and valid
        if (isset($data['quranic_verse']) && is_array($data['quranic_verse'])) {
            $verse = $data['quranic_verse'];
            if (!empty($verse['arabic']) && !empty($verse['translation']) && !empty($verse['reference'])) {
                $sanitized['quranic_verse'] = [
                    'arabic' => trim($verse['arabic']),
                    'translation' => trim($verse['translation']),
                    'reference' => trim($verse['reference']),
                ];
            }
        }

        // Add milestone warning if present
        if (isset($data['milestone_warning']) && is_array($data['milestone_warning'])) {
            $sanitized['milestone_warning'] = [
                'message' => $data['milestone_warning']['message'] ?? 'Stay strong and don\'t give up now!',
                'milestone_days' => (int) ($data['milestone_warning']['milestone_days'] ?? 0),
            ];
        }

        return $sanitized;
    }

    public function moderateSearchQuery(string $query): array
    {
        try {
            $prompt = $this->buildModerationPrompt($query);

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
                return $this->parseModerationResponse($content);
            }

            Log::error('Gemini API error for content moderation', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            // Default to blocking if API fails for safety
            return [
                'isAppropriate' => false,
                'reason' => 'Content moderation service unavailable. Please try a different search term.',
            ];
        } catch (\Exception $e) {
            Log::error('Gemini service error for content moderation', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'query' => $query,
            ]);

            // Default to blocking if service fails for safety
            return [
                'isAppropriate' => false,
                'reason' => 'Content moderation service unavailable. Please try a different search term.',
            ];
        }
    }

    private function buildModerationPrompt(string $query): string
    {
        return "Analyze this YouTube search query for inappropriate content: \"{$query}\"

You are a content moderator for an Islamic educational platform. Your job is to block content that is clearly inappropriate, while allowing legitimate educational and beneficial content.

BLOCK content that is clearly:
1. Explicitly sexual, pornographic, or adult-oriented
2. Violent, graphic, or disturbing material
3. Profane, offensive language, or hate speech
4. Content promoting sinful behavior (gambling, drugs, alcohol, etc.)
5. Material that clearly contradicts Islamic values

ALLOW content that is:
- Educational, academic, or informative
- Religious, spiritual, or Islamic content
- News, documentaries, or factual content
- Entertainment that is family-friendly
- Channel names, usernames, or technical terms
- General topics like science, history, cooking, etc.

Be reasonable and balanced. Only block content that is clearly inappropriate. Educational content, channel names, and legitimate topics should be allowed.

Return your analysis as a JSON object with this exact structure:
{
    \"isAppropriate\": true/false,
    \"reason\": \"Brief explanation of why the content is appropriate or inappropriate\"
}

Guidelines:
- Allow educational and beneficial content
- Only block clearly inappropriate material
- Consider context - channel names and educational terms are usually fine
- Be fair and balanced in your assessment";
    }

    private function parseModerationResponse(string $content): array
    {
        try {
            // Clean the response content
            $cleanedContent = trim($content);

            // Remove markdown code blocks if present
            $cleanedContent = preg_replace('/```(?:json)?\s*/', '', $cleanedContent);
            $cleanedContent = preg_replace('/```\s*$/', '', $cleanedContent);
            $cleanedContent = trim($cleanedContent);

            // Try to extract JSON from the response
            if (preg_match('/\{.*\}/s', $cleanedContent, $matches)) {
                $jsonContent = $matches[0];
                $parsed = json_decode($jsonContent, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($parsed['isAppropriate'])) {
                    return $this->sanitizeModerationData($parsed);
                }
            }

            // If the above didn't work, try parsing the entire cleaned content
            $parsed = json_decode($cleanedContent, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($parsed['isAppropriate'])) {
                return $this->sanitizeModerationData($parsed);
            }

            Log::warning('Failed to parse Gemini moderation response as JSON', [
                'content' => $content,
                'cleaned_content' => $cleanedContent
            ]);

            // Default to blocking if parsing fails for safety
            return [
                'isAppropriate' => false,
                'reason' => 'Unable to analyze content. Please try a different search term.',
            ];

        } catch (\Exception $e) {
            Log::error('Error parsing Gemini moderation response', [
                'message' => $e->getMessage(),
                'content' => $content,
            ]);

            // Default to blocking if parsing fails for safety
            return [
                'isAppropriate' => false,
                'reason' => 'Unable to analyze content. Please try a different search term.',
            ];
        }
    }

    private function sanitizeModerationData(array $data): array
    {
        return [
            'isAppropriate' => (bool) ($data['isAppropriate'] ?? false),
            'reason' => trim($data['reason'] ?? 'Content analysis completed.'),
        ];
    }

    // Commented out fallback quote method - no longer used
    // private function getFallbackQuote(): array
    // {
    //     $fallbackQuotes = [
    //         [
    //             'quote' => 'Every step forward is a victory worth celebrating!',
    //             'type' => 'general',
    //             'context' => 'Keep moving forward on your journey!',
    //         ],
    //         [
    //             'quote' => 'And whoever relies upon Allah - then He is sufficient for him.',
    //             'type' => 'islamic',
    //             'context' => 'Trust in Allah\'s plan for you.',
    //         ],
    //         [
    //             'quote' => 'Progress, not perfection - you\'re doing amazing!',
    //             'type' => 'realistic',
    //             'context' => 'Focus on consistent progress.',
    //         ],
    //     ];

    //     return $fallbackQuotes[array_rand($fallbackQuotes)];
    // }
}
