<?php

namespace App\Http\Controllers;

use App\Services\YouTubeService;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class YouTubeController extends Controller
{
    public function __construct(
        private YouTubeService $youtubeService,
        private GeminiService $geminiService
    ) {}

    public function index(): Response
    {
        $user = Auth::user();
        
        if (!$user->google_access_token) {
            return Inertia::render('youtube/Index', [
                'error' => 'Please authenticate with Google first to access YouTube features.',
                'needsGoogleAuth' => true,
            ]);
        }

        return Inertia::render('youtube/Index', [
            'needsGoogleAuth' => false,
        ]);
    }

    public function testPage(): Response
    {
        $user = Auth::user();
        
        if (!$user->google_access_token) {
            return Inertia::render('youtube/test', [
                'error' => 'Please authenticate with Google first to access YouTube features.',
                'needsGoogleAuth' => true,
            ]);
        }

        return Inertia::render('youtube/test', [
            'needsGoogleAuth' => false,
        ]);
    }

    public function getWatchLater(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user->google_access_token) {
                return response()->json([
                    'error' => 'Google authentication required',
                    'needsGoogleAuth' => true,
                ], 401);
            }

            $maxResults = $request->get('maxResults', 25);
            
            $videos = $this->youtubeService->setUserTokens($user)->getWatchLaterVideos($maxResults);

            // Get video details including view count
            $videoIds = array_column($videos, 'id');
            $videoDetails = $this->youtubeService->getVideoDetails($videoIds);
            
            // Merge view count and duration data
            $videosWithDetails = array_map(function ($video) use ($videoDetails) {
                $details = collect($videoDetails)->firstWhere('id', $video['id']);
                if ($details) {
                    $video['viewCount'] = $details['viewCount'];
                    $video['duration'] = $details['duration'];
                }
                return $video;
            }, $videos);

            return response()->json([
                'success' => true,
                'videos' => $videosWithDetails,
                'count' => count($videosWithDetails),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'needsGoogleAuth' => str_contains($e->getMessage(), 're-authenticate'),
            ], 500);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user->google_access_token) {
                return response()->json([
                    'error' => 'Google authentication required',
                    'needsGoogleAuth' => true,
                ], 401);
            }

            $query = $request->get('q');
            if (!$query) {
                return response()->json([
                    'success' => false,
                    'error' => 'Search query is required',
                ], 400);
            }

            $maxResults = $request->get('maxResults', 25);
            $order = $request->get('order', 'relevance');
            $pageToken = $request->get('pageToken');
            
            $result = $this->youtubeService->setUserTokens($user)->searchVideos($query, $maxResults, $order, $pageToken);

            // Get video details including view count
            $videoIds = array_column($result['videos'], 'id');
            $videoDetails = $this->youtubeService->getVideoDetails($videoIds);
            
            // Merge view count and duration data
            $videosWithDetails = array_map(function ($video) use ($videoDetails) {
                $details = collect($videoDetails)->firstWhere('id', $video['id']);
                if ($details) {
                    $video['viewCount'] = $details['viewCount'];
                    $video['duration'] = $details['duration'];
                }
                return $video;
            }, $result['videos']);

            return response()->json([
                'success' => true,
                'videos' => $videosWithDetails,
                'nextPageToken' => $result['nextPageToken'],
                'count' => count($videosWithDetails),
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'needsGoogleAuth' => str_contains($e->getMessage(), 're-authenticate'),
            ], 500);
        }
    }

    public function getPlaylists(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user->google_access_token) {
                return response()->json([
                    'error' => 'Google authentication required',
                    'needsGoogleAuth' => true,
                ], 401);
            }

            $maxResults = $request->get('maxResults', 25);
            
            $playlists = $this->youtubeService->setUserTokens($user)->getUserPlaylists($maxResults);

            return response()->json([
                'success' => true,
                'playlists' => $playlists,
                'count' => count($playlists),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'needsGoogleAuth' => str_contains($e->getMessage(), 're-authenticate'),
            ], 500);
        }
    }

    public function getPlaylistVideos(Request $request, string $playlistId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user->google_access_token) {
                return response()->json([
                    'error' => 'Google authentication required',
                    'needsGoogleAuth' => true,
                ], 401);
            }

            $maxResults = $request->get('maxResults', 25);
            $pageToken = $request->get('pageToken');
            
            $result = $this->youtubeService->setUserTokens($user)->getPlaylistVideos($playlistId, $maxResults, $pageToken);

            // Get video details including view count
            $videoIds = array_column($result['videos'], 'id');
            $videoDetails = $this->youtubeService->getVideoDetails($videoIds);
            
            // Merge view count and duration data
            $videosWithDetails = array_map(function ($video) use ($videoDetails) {
                $details = collect($videoDetails)->firstWhere('id', $video['id']);
                if ($details) {
                    $video['viewCount'] = $details['viewCount'];
                    $video['duration'] = $details['duration'];
                }
                return $video;
            }, $result['videos']);

            return response()->json([
                'success' => true,
                'videos' => $videosWithDetails,
                'nextPageToken' => $result['nextPageToken'],
                'count' => count($videosWithDetails),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'needsGoogleAuth' => str_contains($e->getMessage(), 're-authenticate'),
            ], 500);
        }
    }

    public function addToPlaylist(Request $request, string $playlistId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user->google_access_token) {
                return response()->json([
                    'error' => 'Google authentication required',
                    'needsGoogleAuth' => true,
                ], 401);
            }

            $videoId = $request->get('videoId');
            if (!$videoId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Video ID is required',
                ], 400);
            }

            $result = $this->youtubeService->setUserTokens($user)->addVideoToPlaylist($playlistId, $videoId);

            return response()->json([
                'success' => true,
                'message' => 'Video added to playlist successfully',
                'playlistItem' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'needsGoogleAuth' => str_contains($e->getMessage(), 're-authenticate'),
            ], 500);
        }
    }

    public function removeFromPlaylist(Request $request, string $playlistId, string $playlistItemId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user->google_access_token) {
                return response()->json([
                    'error' => 'Google authentication required',
                    'needsGoogleAuth' => true,
                ], 401);
            }

            $this->youtubeService->setUserTokens($user)->removeVideoFromPlaylist($playlistId, $playlistItemId);

            return response()->json([
                'success' => true,
                'message' => 'Video removed from playlist successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'needsGoogleAuth' => str_contains($e->getMessage(), 're-authenticate'),
            ], 500);
        }
    }

    public function validateSearchQuery(Request $request): JsonResponse
    {
        try {
            $query = $request->get('query');
            if (!$query || !trim($query)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Search query is required',
                ], 400);
            }

            $moderationResult = $this->geminiService->moderateSearchQuery(trim($query));

            if (!$moderationResult['isAppropriate']) {
                return response()->json([
                    'success' => false,
                    'isAppropriate' => false,
                    'reason' => $moderationResult['reason'],
                    'message' => 'This search query appears to contain inappropriate content. Please try a different search term focused on beneficial knowledge.',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'isAppropriate' => true,
                'message' => 'Search query is appropriate',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Content validation failed. Please try again.',
                'isAppropriate' => false,
            ], 500);
        }
    }
}
