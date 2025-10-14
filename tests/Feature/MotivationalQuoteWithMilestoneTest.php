<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\GeminiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MotivationalQuoteWithMilestoneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create and authenticate a user for all tests
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        // Mock the GeminiService to avoid actual API calls during testing
        $this->mock(GeminiService::class, function ($mock) {
            $mock->shouldReceive('generateMotivationalQuote')
                ->andReturnUsing(function ($daysCompleted, $daysRemaining, $percentage, $nearMilestone) {
                    $baseResponse = [
                        'quote' => 'Test motivational quote',
                        'type' => 'general',
                        'context' => 'Test context',
                        'quranic_verse' => [
                            'arabic' => 'وَمَن يَتَوَكَّلْ عَلَى اللَّهِ فَهُوَ حَسْبُهُ',
                            'translation' => 'And whoever relies upon Allah - then He is sufficient for him.',
                            'reference' => 'At-Talaq (65:3)'
                        ]
                    ];

                    // Add milestone warning if nearMilestone is provided
                    if ($nearMilestone !== null) {
                        $baseResponse['milestone_warning'] = [
                            'message' => 'Stay strong and don\'t give up now!',
                            'milestone_days' => $nearMilestone
                        ];
                    }

                    return $baseResponse;
                });
        });
    }

    public function test_generates_quote_without_milestone_warning_when_far_from_milestones(): void
    {
        // Test when days completed is far from any milestone (e.g., 15 days)
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 15,
            'days_remaining' => 100,
            'percentage' => 13.0,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        
        $this->assertArrayHasKey('quote', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('context', $data);
        $this->assertArrayHasKey('quranic_verse', $data);
        $this->assertArrayNotHasKey('milestone_warning', $data);
    }

    public function test_generates_quote_with_milestone_warning_when_approaching_milestone(): void
    {
        // Test when within 3 days before a milestone (e.g., 4 days completed, approaching 7-day milestone)
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 4,
            'days_remaining' => 120,
            'percentage' => 3.2,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        
        $this->assertArrayHasKey('quote', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('context', $data);
        $this->assertArrayHasKey('quranic_verse', $data);
        $this->assertArrayHasKey('milestone_warning', $data);
        $this->assertArrayHasKey('message', $data['milestone_warning']);
        $this->assertArrayHasKey('milestone_days', $data['milestone_warning']);
        $this->assertEquals(7, $data['milestone_warning']['milestone_days']);
    }

    public function test_generates_quote_with_milestone_warning_when_on_milestone_day(): void
    {
        // Test when exactly on a milestone day (e.g., 30 days completed)
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 30,
            'days_remaining' => 90,
            'percentage' => 25.0,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        
        $this->assertArrayHasKey('quote', $data);
        $this->assertArrayHasKey('milestone_warning', $data);
        $this->assertEquals(30, $data['milestone_warning']['milestone_days']);
    }

    public function test_generates_quote_with_milestone_warning_when_just_past_milestone(): void
    {
        // Test when within 3 days after a milestone (e.g., 32 days completed, just past 30-day milestone)
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 32,
            'days_remaining' => 88,
            'percentage' => 26.7,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        
        $this->assertArrayHasKey('quote', $data);
        $this->assertArrayHasKey('milestone_warning', $data);
        $this->assertEquals(30, $data['milestone_warning']['milestone_days']);
    }

    public function test_generates_quote_with_milestone_warning_for_60_day_milestone(): void
    {
        // Test approaching 60-day milestone
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 58,
            'days_remaining' => 62,
            'percentage' => 48.3,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        
        $this->assertArrayHasKey('milestone_warning', $data);
        $this->assertEquals(60, $data['milestone_warning']['milestone_days']);
    }

    public function test_generates_quote_with_milestone_warning_for_90_day_milestone(): void
    {
        // Test on 90-day milestone
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 90,
            'days_remaining' => 30,
            'percentage' => 75.0,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        
        $this->assertArrayHasKey('milestone_warning', $data);
        $this->assertEquals(90, $data['milestone_warning']['milestone_days']);
    }

    public function test_generates_quote_with_milestone_warning_for_120_day_milestone(): void
    {
        // Test just past 120-day milestone
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 122,
            'days_remaining' => 0,
            'percentage' => 100.0,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        
        $this->assertArrayHasKey('milestone_warning', $data);
        $this->assertEquals(120, $data['milestone_warning']['milestone_days']);
    }

    public function test_handles_multiple_milestones_returns_closest_one(): void
    {
        // Test when near multiple milestones, should return the closest one
        // 28 days is 2 days before 30-day milestone and 2 days after 7-day milestone
        // Should detect 30-day milestone as it's closer
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 28,
            'days_remaining' => 92,
            'percentage' => 23.3,
        ]);

        $response->assertSuccessful();
        $data = $response->json();
        
        $this->assertArrayHasKey('milestone_warning', $data);
        $this->assertEquals(30, $data['milestone_warning']['milestone_days']);
    }

    public function test_validation_errors_for_invalid_input(): void
    {
        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => -1,
            'days_remaining' => 100,
            'percentage' => 50.0,
        ]);

        $response->assertStatus(422);
    }

    public function test_handles_gemini_service_failure_gracefully(): void
    {
        // Mock the GeminiService to return empty array (simulating failure)
        $this->mock(GeminiService::class, function ($mock) {
            $mock->shouldReceive('generateMotivationalQuote')
                ->andReturn([]);
        });

        $response = $this->postJson('/api/motivational-quote', [
            'days_completed' => 15,
            'days_remaining' => 100,
            'percentage' => 13.0,
        ]);

        $response->assertStatus(503);
        $data = $response->json();
        $this->assertArrayHasKey('error', $data);
        $this->assertArrayHasKey('message', $data);
    }
}
