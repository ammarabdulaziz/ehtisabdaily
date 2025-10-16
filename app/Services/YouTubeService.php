<?php

namespace App\Services;

use App\Models\User;
use Google\Client;
use Google\Service\YouTube;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    private Client $client;
    private ?User $user = null;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setScopes(config('services.google.scopes'));
    }

    public function setUserTokens(User $user): self
    {
        $this->user = $user;
        
        if (!$user->google_access_token) {
            throw new \Exception('User does not have Google authentication tokens.');
        }

        $this->client->setAccessToken([
            'access_token' => $user->google_access_token,
            'refresh_token' => $user->google_refresh_token,
        ]);

        return $this;
    }

    public function getWatchLaterVideos(int $maxResults = 25): array
    {
        try {
            $this->ensureValidToken();
            
            $youtube = new YouTube($this->client);
            
            // Use 'WL' as the special playlist ID for Watch Later
            $response = $youtube->playlistItems->listPlaylistItems('snippet', [
                'playlistId' => 'WL',
                'maxResults' => $maxResults,
            ]);

            return $this->formatPlaylistItems($response->getItems());
        } catch (\Exception $e) {
            Log::error('YouTube Watch Later API error', [
                'message' => $e->getMessage(),
                'user_id' => $this->user?->id,
            ]);
            
            throw new \Exception('Failed to fetch Watch Later videos: ' . $e->getMessage());
        }
    }

    public function searchVideos(string $query, int $maxResults = 25, string $order = 'relevance', ?string $pageToken = null): array
    {
        try {
            $this->ensureValidToken();
            
            $youtube = new YouTube($this->client);
            
            $params = [
                'q' => $query,
                'maxResults' => $maxResults,
                'type' => 'video',
                'order' => $order,
            ];

            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }
            
            $response = $youtube->search->listSearch('snippet', $params);

            return [
                'videos' => $this->formatSearchResults($response->getItems()),
                'nextPageToken' => $response->getNextPageToken(),
            ];
        } catch (\Exception $e) {
            Log::error('YouTube Search API error', [
                'message' => $e->getMessage(),
                'query' => $query,
                'user_id' => $this->user?->id,
            ]);
            
            throw new \Exception('Failed to search videos: ' . $e->getMessage());
        }
    }

    public function getUserPlaylists(int $maxResults = 25): array
    {
        try {
            $this->ensureValidToken();
            
            $youtube = new YouTube($this->client);
            
            $response = $youtube->playlists->listPlaylists('snippet,contentDetails', [
                'mine' => true,
                'maxResults' => $maxResults,
            ]);

            return $this->formatPlaylists($response->getItems());
        } catch (\Exception $e) {
            Log::error('YouTube Playlists API error', [
                'message' => $e->getMessage(),
                'user_id' => $this->user?->id,
            ]);
            
            throw new \Exception('Failed to fetch playlists: ' . $e->getMessage());
        }
    }

    public function getPlaylistVideos(string $playlistId, int $maxResults = 25, ?string $pageToken = null): array
    {
        try {
            $this->ensureValidToken();
            
            $youtube = new YouTube($this->client);
            
            $params = [
                'playlistId' => $playlistId,
                'maxResults' => $maxResults,
            ];

            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }
            
            $response = $youtube->playlistItems->listPlaylistItems('snippet,contentDetails', $params);

            return [
                'videos' => $this->formatPlaylistItems($response->getItems()),
                'nextPageToken' => $response->getNextPageToken(),
            ];
        } catch (\Exception $e) {
            Log::error('YouTube Playlist Videos API error', [
                'message' => $e->getMessage(),
                'playlist_id' => $playlistId,
                'user_id' => $this->user?->id,
            ]);
            
            throw new \Exception('Failed to fetch playlist videos: ' . $e->getMessage());
        }
    }

    public function addVideoToPlaylist(string $playlistId, string $videoId): array
    {
        try {
            $this->ensureValidToken();
            
            $youtube = new YouTube($this->client);
            
            $playlistItem = new \Google\Service\YouTube\PlaylistItem();
            $playlistItem->setSnippet(new \Google\Service\YouTube\PlaylistItemSnippet());
            
            $playlistItem->getSnippet()->setPlaylistId($playlistId);
            $playlistItem->getSnippet()->setResourceId(new \Google\Service\YouTube\ResourceId());
            $playlistItem->getSnippet()->getResourceId()->setKind('youtube#video');
            $playlistItem->getSnippet()->getResourceId()->setVideoId($videoId);
            
            $response = $youtube->playlistItems->insert('snippet', $playlistItem);

            Log::info('Video added to playlist', [
                'playlist_id' => $playlistId,
                'video_id' => $videoId,
                'playlist_item_id' => $response->getId(),
                'user_id' => $this->user?->id,
            ]);

            return [
                'id' => $response->getId(),
                'videoId' => $videoId,
                'playlistId' => $playlistId,
            ];
        } catch (\Exception $e) {
            Log::error('YouTube Add Video API error', [
                'message' => $e->getMessage(),
                'playlist_id' => $playlistId,
                'video_id' => $videoId,
                'user_id' => $this->user?->id,
            ]);
            
            throw new \Exception('Failed to add video to playlist: ' . $e->getMessage());
        }
    }

    public function removeVideoFromPlaylist(string $playlistId, string $playlistItemId): bool
    {
        try {
            $this->ensureValidToken();
            
            $youtube = new YouTube($this->client);
            
            $youtube->playlistItems->delete($playlistItemId);

            Log::info('Video removed from playlist', [
                'playlist_id' => $playlistId,
                'playlist_item_id' => $playlistItemId,
                'user_id' => $this->user?->id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('YouTube Remove Video API error', [
                'message' => $e->getMessage(),
                'playlist_id' => $playlistId,
                'playlist_item_id' => $playlistItemId,
                'user_id' => $this->user?->id,
            ]);
            
            throw new \Exception('Failed to remove video: ' . $e->getMessage());
        }
    }

    private function ensureValidToken(): void
    {
        if (!$this->user) {
            throw new \Exception('User not set. Call setUserTokens() first.');
        }

        if ($this->client->isAccessTokenExpired()) {
            if (!$this->user->google_refresh_token) {
                throw new \Exception('Refresh token not available. User needs to re-authenticate with Google.');
            }

            try {
                $this->client->fetchAccessTokenWithRefreshToken($this->user->google_refresh_token);
                $token = $this->client->getAccessToken();
                
                // Update user record with new token
                $this->user->update([
                    'google_access_token' => $token['access_token'],
                ]);

                Log::info('YouTube access token refreshed', [
                    'user_id' => $this->user->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to refresh YouTube access token', [
                    'message' => $e->getMessage(),
                    'user_id' => $this->user->id,
                ]);
                
                throw new \Exception('Token refresh failed. Please re-authenticate with Google.');
            }
        }
    }

    private function formatPlaylistItems(array $items): array
    {
        return array_map(function ($item) {
            $snippet = $item->getSnippet();
            $videoId = $snippet->getResourceId()->getVideoId();
            
            return [
                'id' => $videoId,
                'playlistItemId' => $item->getId(),
                'title' => $snippet->getTitle(),
                'description' => $snippet->getDescription(),
                'thumbnail' => $snippet->getThumbnails()->getDefault()->getUrl(),
                'channelTitle' => $snippet->getChannelTitle(),
                'publishedAt' => $snippet->getPublishedAt(),
                'url' => "https://www.youtube.com/watch?v={$videoId}",
            ];
        }, $items);
    }

    private function formatSearchResults(array $items): array
    {
        return array_map(function ($item) {
            $snippet = $item->getSnippet();
            $videoId = $item->getId()->getVideoId();
            
            return [
                'id' => $videoId,
                'title' => $snippet->getTitle(),
                'description' => $snippet->getDescription(),
                'thumbnail' => $snippet->getThumbnails()->getDefault()->getUrl(),
                'channelTitle' => $snippet->getChannelTitle(),
                'publishedAt' => $snippet->getPublishedAt(),
                'url' => "https://www.youtube.com/watch?v={$videoId}",
            ];
        }, $items);
    }

    private function formatPlaylists(array $items): array
    {
        return array_map(function ($item) {
            $snippet = $item->getSnippet();
            $contentDetails = $item->getContentDetails();
            
            return [
                'id' => $item->getId(),
                'title' => $snippet->getTitle(),
                'description' => $snippet->getDescription(),
                'thumbnail' => $snippet->getThumbnails()->getDefault()->getUrl(),
                'itemCount' => $contentDetails->getItemCount(),
            ];
        }, $items);
    }
}
