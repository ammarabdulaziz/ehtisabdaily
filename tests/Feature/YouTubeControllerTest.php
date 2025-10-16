<?php

use App\Models\User;
use App\Services\YouTubeService;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated user can access YouTube test page', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/test-youtube');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => 
        $page->component('youtube/test')
            ->has('needsGoogleAuth')
    );
});

test('unauthenticated user is redirected to login', function () {
    $response = $this->get('/test-youtube');
    
    $response->assertRedirect('/login');
});

test('user without Google auth sees authentication prompt', function () {
    $user = User::factory()->create(); // User without Google tokens
    
    $response = $this->actingAs($user)->get('/test-youtube');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => 
        $page->component('youtube/test')
            ->where('needsGoogleAuth', true)
            ->where('error', 'Please authenticate with Google first to access YouTube features.')
    );
});

test('user with Google auth can access test page', function () {
    $user = User::factory()->google()->create();
    
    $response = $this->actingAs($user)->get('/test-youtube');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => 
        $page->component('youtube/test')
            ->where('needsGoogleAuth', false)
    );
});

test('watch later API endpoint requires authentication', function () {
    $response = $this->get('/api/youtube/watch-later');
    
    $response->assertRedirect('/login');
});

test('watch later API endpoint returns error for user without Google auth', function () {
    $user = User::factory()->create(); // User without Google tokens
    
    $response = $this->actingAs($user)->get('/api/youtube/watch-later');
    
    $response->assertStatus(401);
    $response->assertJson([
        'error' => 'Google authentication required',
        'needsGoogleAuth' => true,
    ]);
});

test('watch later API endpoint returns videos for authenticated Google user', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('getWatchLaterVideos')->with(25)->andReturn([
        [
            'id' => 'test_video_id',
            'title' => 'Test Video',
            'description' => 'Test Description',
            'thumbnail' => 'https://example.com/thumb.jpg',
            'channelTitle' => 'Test Channel',
            'publishedAt' => '2023-01-01T00:00:00Z',
            'url' => 'https://www.youtube.com/watch?v=test_video_id',
        ]
    ]);
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->get('/api/youtube/watch-later');
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'videos' => [
            [
                'id' => 'test_video_id',
                'title' => 'Test Video',
                'description' => 'Test Description',
                'thumbnail' => 'https://example.com/thumb.jpg',
                'channelTitle' => 'Test Channel',
                'publishedAt' => '2023-01-01T00:00:00Z',
                'url' => 'https://www.youtube.com/watch?v=test_video_id',
            ]
        ],
        'count' => 1,
    ]);
});

test('search API endpoint requires authentication', function () {
    $response = $this->get('/api/youtube/search');
    
    $response->assertRedirect('/login');
});

test('search API endpoint requires query parameter', function () {
    $user = User::factory()->google()->create();
    
    $response = $this->actingAs($user)->get('/api/youtube/search');
    
    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'error' => 'Search query is required',
    ]);
});

test('search API endpoint returns videos for valid query', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('searchVideos')->with('test query', 25, 'relevance', null)->andReturn([
        'videos' => [
            [
                'id' => 'search_video_id',
                'title' => 'Search Video',
                'description' => 'Search Description',
                'thumbnail' => 'https://example.com/search_thumb.jpg',
                'channelTitle' => 'Search Channel',
                'publishedAt' => '2023-01-01T00:00:00Z',
                'url' => 'https://www.youtube.com/watch?v=search_video_id',
            ]
        ],
        'nextPageToken' => null
    ]);
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->get('/api/youtube/search?q=test%20query');
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'videos' => [
            [
                'id' => 'search_video_id',
                'title' => 'Search Video',
                'description' => 'Search Description',
                'thumbnail' => 'https://example.com/search_thumb.jpg',
                'channelTitle' => 'Search Channel',
                'publishedAt' => '2023-01-01T00:00:00Z',
                'url' => 'https://www.youtube.com/watch?v=search_video_id',
            ]
        ],
        'count' => 1,
        'query' => 'test query',
    ]);
});

test('playlists API endpoint requires authentication', function () {
    $response = $this->get('/api/youtube/playlists');
    
    $response->assertRedirect('/login');
});

test('playlists API endpoint returns playlists for authenticated Google user', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('getUserPlaylists')->with(25)->andReturn([
        [
            'id' => 'playlist_id',
            'title' => 'Test Playlist',
            'description' => 'Test Playlist Description',
            'thumbnail' => 'https://example.com/playlist_thumb.jpg',
            'itemCount' => 10,
        ]
    ]);
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->get('/api/youtube/playlists');
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'playlists' => [
            [
                'id' => 'playlist_id',
                'title' => 'Test Playlist',
                'description' => 'Test Playlist Description',
                'thumbnail' => 'https://example.com/playlist_thumb.jpg',
                'itemCount' => 10,
            ]
        ],
        'count' => 1,
    ]);
});

test('API endpoints handle service exceptions gracefully', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service to throw an exception
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('getWatchLaterVideos')->andThrow(new Exception('API Error'));
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->get('/api/youtube/watch-later');
    
    $response->assertStatus(500);
    $response->assertJson([
        'success' => false,
        'error' => 'API Error',
        'needsGoogleAuth' => false,
    ]);
});

test('API endpoints handle token refresh errors', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service to throw a token refresh exception
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('getWatchLaterVideos')
        ->andThrow(new Exception('Token refresh failed. Please re-authenticate with Google.'));
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->get('/api/youtube/watch-later');
    
    $response->assertStatus(500);
    $response->assertJson([
        'success' => false,
        'error' => 'Token refresh failed. Please re-authenticate with Google.',
        'needsGoogleAuth' => true,
    ]);
});

test('API endpoints accept maxResults parameter', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('getWatchLaterVideos')->with(10)->andReturn([]);
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->get('/api/youtube/watch-later?maxResults=10');
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'videos' => [],
        'count' => 0,
    ]);
});

test('authenticated user can access YouTube main page', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/my-youtube');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => 
        $page->component('youtube/Index')
            ->has('needsGoogleAuth')
    );
});

test('user without Google auth sees authentication prompt on main page', function () {
    $user = User::factory()->create(); // User without Google tokens
    
    $response = $this->actingAs($user)->get('/my-youtube');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => 
        $page->component('youtube/Index')
            ->where('needsGoogleAuth', true)
            ->where('error', 'Please authenticate with Google first to access YouTube features.')
    );
});

test('user with Google auth can access main page', function () {
    $user = User::factory()->google()->create();
    
    $response = $this->actingAs($user)->get('/my-youtube');
    
    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => 
        $page->component('youtube/Index')
            ->where('needsGoogleAuth', false)
    );
});

test('playlist videos API endpoint requires authentication', function () {
    $response = $this->get('/api/youtube/playlist/test_playlist_id');
    
    $response->assertRedirect('/login');
});

test('playlist videos API endpoint returns videos for authenticated Google user', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('getPlaylistVideos')->with('test_playlist_id', 25, null)->andReturn([
        'videos' => [
            [
                'id' => 'playlist_video_id',
                'playlistItemId' => 'playlist_item_id',
                'title' => 'Playlist Video',
                'description' => 'Playlist Description',
                'thumbnail' => 'https://example.com/playlist_thumb.jpg',
                'channelTitle' => 'Playlist Channel',
                'publishedAt' => '2023-01-01T00:00:00Z',
                'url' => 'https://www.youtube.com/watch?v=playlist_video_id',
            ]
        ],
        'nextPageToken' => 'next_page_token'
    ]);
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->get('/api/youtube/playlist/test_playlist_id');
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'videos' => [
            [
                'id' => 'playlist_video_id',
                'playlistItemId' => 'playlist_item_id',
                'title' => 'Playlist Video',
                'description' => 'Playlist Description',
                'thumbnail' => 'https://example.com/playlist_thumb.jpg',
                'channelTitle' => 'Playlist Channel',
                'publishedAt' => '2023-01-01T00:00:00Z',
                'url' => 'https://www.youtube.com/watch?v=playlist_video_id',
            ]
        ],
        'nextPageToken' => 'next_page_token',
        'count' => 1,
    ]);
});

test('remove from playlist API endpoint requires authentication', function () {
    $response = $this->delete('/api/youtube/playlist/test_playlist_id/item/test_item_id');
    
    $response->assertRedirect('/login');
});

test('add to playlist API endpoint requires authentication', function () {
    $response = $this->post('/api/youtube/playlist/test_playlist_id/add');
    
    $response->assertRedirect('/login');
});

test('add to playlist API endpoint requires videoId parameter', function () {
    $user = User::factory()->google()->create();
    
    $response = $this->actingAs($user)->post('/api/youtube/playlist/test_playlist_id/add');
    
    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'error' => 'Video ID is required',
    ]);
});

test('add to playlist API endpoint adds video for authenticated Google user', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('addVideoToPlaylist')->with('test_playlist_id', 'test_video_id')->andReturn([
        'id' => 'playlist_item_id',
        'videoId' => 'test_video_id',
        'playlistId' => 'test_playlist_id',
    ]);
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->post('/api/youtube/playlist/test_playlist_id/add', [
        'videoId' => 'test_video_id',
    ]);
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Video added to playlist successfully',
        'playlistItem' => [
            'id' => 'playlist_item_id',
            'videoId' => 'test_video_id',
            'playlistId' => 'test_playlist_id',
        ],
    ]);
});

test('add to playlist API endpoint returns error for user without Google auth', function () {
    $user = User::factory()->create(); // User without Google tokens
    
    $response = $this->actingAs($user)->post('/api/youtube/playlist/test_playlist_id/add', [
        'videoId' => 'test_video_id',
    ]);
    
    $response->assertStatus(401);
    $response->assertJson([
        'error' => 'Google authentication required',
        'needsGoogleAuth' => true,
    ]);
});

test('remove from playlist API endpoint removes video for authenticated Google user', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('removeVideoFromPlaylist')->with('test_playlist_id', 'test_item_id')->andReturn(true);
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->delete('/api/youtube/playlist/test_playlist_id/item/test_item_id');
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Video removed from playlist successfully',
    ]);
});

test('search API endpoint supports filters and pagination', function () {
    $user = User::factory()->google()->create();
    
    // Mock the YouTube service
    $mockService = Mockery::mock(YouTubeService::class);
    $mockService->shouldReceive('setUserTokens')->with($user)->andReturnSelf();
    $mockService->shouldReceive('searchVideos')->with('test query', 25, 'date', 'test_token')->andReturn([
        'videos' => [
            [
                'id' => 'filtered_video_id',
                'title' => 'Filtered Video',
                'description' => 'Filtered Description',
                'thumbnail' => 'https://example.com/filtered_thumb.jpg',
                'channelTitle' => 'Filtered Channel',
                'publishedAt' => '2023-01-01T00:00:00Z',
                'url' => 'https://www.youtube.com/watch?v=filtered_video_id',
            ]
        ],
        'nextPageToken' => 'search_next_token'
    ]);
    
    $this->app->instance(YouTubeService::class, $mockService);
    
    $response = $this->actingAs($user)->get('/api/youtube/search?q=test%20query&order=date&pageToken=test_token');
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'videos' => [
            [
                'id' => 'filtered_video_id',
                'title' => 'Filtered Video',
                'description' => 'Filtered Description',
                'thumbnail' => 'https://example.com/filtered_thumb.jpg',
                'channelTitle' => 'Filtered Channel',
                'publishedAt' => '2023-01-01T00:00:00Z',
                'url' => 'https://www.youtube.com/watch?v=filtered_video_id',
            ]
        ],
        'nextPageToken' => 'search_next_token',
        'count' => 1,
        'query' => 'test query',
    ]);
});

test('validate search query API endpoint requires authentication', function () {
    $response = $this->post('/api/youtube/validate-search');
    
    $response->assertRedirect('/login');
});

test('validate search query API endpoint requires query parameter', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post('/api/youtube/validate-search');
    
    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'error' => 'Search query is required',
    ]);
});

test('validate search query API endpoint accepts empty query', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post('/api/youtube/validate-search', [
        'query' => '',
    ]);
    
    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'error' => 'Search query is required',
    ]);
});

test('validate search query API endpoint accepts whitespace-only query', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post('/api/youtube/validate-search', [
        'query' => '   ',
    ]);
    
    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'error' => 'Search query is required',
    ]);
});

test('validate search query API endpoint returns appropriate for clean query', function () {
    $user = User::factory()->create();
    
    // Mock the Gemini service
    $mockGeminiService = Mockery::mock(\App\Services\GeminiService::class);
    $mockGeminiService->shouldReceive('moderateSearchQuery')
        ->with('out of focus media one')
        ->andReturn([
            'isAppropriate' => true,
            'reason' => 'Channel name appears to be educational content',
        ]);
    
    $this->app->instance(\App\Services\GeminiService::class, $mockGeminiService);
    
    $response = $this->actingAs($user)->post('/api/youtube/validate-search', [
        'query' => 'out of focus media one',
    ]);
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'isAppropriate' => true,
        'message' => 'Search query is appropriate',
    ]);
});

test('validate search query API endpoint allows educational content', function () {
    $user = User::factory()->create();
    
    // Mock the Gemini service
    $mockGeminiService = Mockery::mock(\App\Services\GeminiService::class);
    $mockGeminiService->shouldReceive('moderateSearchQuery')
        ->with('islamic education')
        ->andReturn([
            'isAppropriate' => true,
            'reason' => 'Educational content is appropriate',
        ]);
    
    $this->app->instance(\App\Services\GeminiService::class, $mockGeminiService);
    
    $response = $this->actingAs($user)->post('/api/youtube/validate-search', [
        'query' => 'islamic education',
    ]);
    
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'isAppropriate' => true,
        'message' => 'Search query is appropriate',
    ]);
});

test('validate search query API endpoint blocks inappropriate content', function () {
    $user = User::factory()->create();
    
    // Mock the Gemini service
    $mockGeminiService = Mockery::mock(\App\Services\GeminiService::class);
    $mockGeminiService->shouldReceive('moderateSearchQuery')
        ->with('inappropriate content')
        ->andReturn([
            'isAppropriate' => false,
            'reason' => 'Content contains inappropriate material',
        ]);
    
    $this->app->instance(\App\Services\GeminiService::class, $mockGeminiService);
    
    $response = $this->actingAs($user)->post('/api/youtube/validate-search', [
        'query' => 'inappropriate content',
    ]);
    
    $response->assertStatus(400);
    $response->assertJson([
        'success' => false,
        'isAppropriate' => false,
        'reason' => 'Content contains inappropriate material',
        'message' => 'This search query appears to contain inappropriate content. Please try a different search term focused on beneficial knowledge.',
    ]);
});

test('validate search query API endpoint handles service exceptions gracefully', function () {
    $user = User::factory()->create();
    
    // Mock the Gemini service to throw an exception
    $mockGeminiService = Mockery::mock(\App\Services\GeminiService::class);
    $mockGeminiService->shouldReceive('moderateSearchQuery')
        ->andThrow(new Exception('Service error'));
    
    $this->app->instance(\App\Services\GeminiService::class, $mockGeminiService);
    
    $response = $this->actingAs($user)->post('/api/youtube/validate-search', [
        'query' => 'test query',
    ]);
    
    $response->assertStatus(500);
    $response->assertJson([
        'success' => false,
        'error' => 'Content validation failed. Please try again.',
        'isAppropriate' => false,
    ]);
});
