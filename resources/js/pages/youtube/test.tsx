import { Head, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Loader2, Search, Play, List, ExternalLink } from 'lucide-react';
import VideoCard from '@/components/youtube/VideoCard';
import PlaylistCard from '@/components/youtube/PlaylistCard';
import { YouTubeVideo, YouTubePlaylist, YouTubeApiResponse } from '@/types/youtube';

interface TestPageProps {
  needsGoogleAuth: boolean;
  error?: string;
}

export default function TestPage({ needsGoogleAuth, error }: TestPageProps) {
  const [activeTab, setActiveTab] = useState('watch-later');
  const [watchLaterVideos, setWatchLaterVideos] = useState<YouTubeVideo[]>([]);
  const [searchResults, setSearchResults] = useState<YouTubeVideo[]>([]);
  const [playlists, setPlaylists] = useState<YouTubePlaylist[]>([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [loading, setLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  useEffect(() => {
    if (!needsGoogleAuth && activeTab === 'watch-later') {
      loadWatchLaterVideos();
    }
  }, [needsGoogleAuth, activeTab]);

  const loadWatchLaterVideos = async () => {
    setLoading(true);
    setErrorMessage(null);
    
    try {
      const response = await fetch('/api/youtube/watch-later');
      const data: YouTubeApiResponse = await response.json();
      
      if (data.success && data.videos) {
        setWatchLaterVideos(data.videos);
      } else {
        setErrorMessage(data.error || 'Failed to load Watch Later videos');
      }
    } catch (err) {
      setErrorMessage('Network error occurred');
    } finally {
      setLoading(false);
    }
  };

  const searchVideos = async () => {
    if (!searchQuery.trim()) return;
    
    setLoading(true);
    setErrorMessage(null);
    
    try {
      const response = await fetch(`/api/youtube/search?q=${encodeURIComponent(searchQuery)}`);
      const data: YouTubeApiResponse = await response.json();
      
      if (data.success && data.videos) {
        setSearchResults(data.videos);
      } else {
        setErrorMessage(data.error || 'Failed to search videos');
      }
    } catch (err) {
      setErrorMessage('Network error occurred');
    } finally {
      setLoading(false);
    }
  };

  const loadPlaylists = async () => {
    setLoading(true);
    setErrorMessage(null);
    
    try {
      const response = await fetch('/api/youtube/playlists');
      const data: YouTubeApiResponse = await response.json();
      
      if (data.success && data.playlists) {
        setPlaylists(data.playlists);
      } else {
        setErrorMessage(data.error || 'Failed to load playlists');
      }
    } catch (err) {
      setErrorMessage('Network error occurred');
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (value: string) => {
    setActiveTab(value);
    setErrorMessage(null);
    
    if (value === 'playlists' && playlists.length === 0) {
      loadPlaylists();
    }
  };

  const handleGoogleAuth = () => {
    router.visit('/auth/google');
  };

  if (needsGoogleAuth) {
    return (
      <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <Head title="YouTube Test - Authentication Required" />
          
          <div className="text-center">
            <div className="mx-auto h-12 w-12 text-red-500 mb-4">
              <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
              </svg>
            </div>
            
            <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
              YouTube Integration Test
            </h1>
            
            <p className="text-lg text-gray-600 dark:text-gray-300 mb-8">
              {error || 'Please authenticate with Google to access YouTube features.'}
            </p>
            
            <Button onClick={handleGoogleAuth} className="bg-red-600 hover:bg-red-700 text-white">
              <svg className="mr-2 h-4 w-4" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              </svg>
              Continue with Google
            </Button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <Head title="YouTube Test" />
        
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            YouTube Integration Test
          </h1>
          <p className="text-gray-600 dark:text-gray-300">
            Test YouTube API functionality including Watch Later playlist, video search, and playlists.
          </p>
        </div>

        <Tabs value={activeTab} onValueChange={handleTabChange} className="w-full">
          <TabsList className="grid w-full grid-cols-3">
            <TabsTrigger value="watch-later" className="flex items-center gap-2">
              <Play className="h-4 w-4" />
              Watch Later
            </TabsTrigger>
            <TabsTrigger value="search" className="flex items-center gap-2">
              <Search className="h-4 w-4" />
              Search Videos
            </TabsTrigger>
            <TabsTrigger value="playlists" className="flex items-center gap-2">
              <List className="h-4 w-4" />
              My Playlists
            </TabsTrigger>
          </TabsList>

          <TabsContent value="watch-later" className="mt-6">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Play className="h-5 w-5" />
                  Watch Later Playlist
                </CardTitle>
                <CardDescription>
                  Videos from your YouTube Watch Later playlist
                </CardDescription>
              </CardHeader>
              <CardContent>
                {errorMessage && (
                  <Alert className="mb-4">
                    <AlertDescription>{errorMessage}</AlertDescription>
                  </Alert>
                )}
                
                {loading ? (
                  <div className="flex items-center justify-center py-8">
                    <Loader2 className="h-8 w-8 animate-spin" />
                    <span className="ml-2">Loading Watch Later videos...</span>
                  </div>
                ) : (
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {watchLaterVideos.map((video) => (
                      <VideoCard key={video.id} video={video} />
                    ))}
                  </div>
                )}
                
                {!loading && watchLaterVideos.length === 0 && !errorMessage && (
                  <div className="text-center py-12">
                    <div className="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4">
                      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
                      Your Watch Later playlist is empty
                    </h3>
                    <p className="text-gray-500 dark:text-gray-400 mb-4 max-w-md mx-auto">
                      Add videos to your Watch Later playlist on YouTube to see them here. 
                      You can save videos by clicking the "Save" button on any YouTube video.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-3 justify-center">
                      <Button 
                        variant="outline" 
                        onClick={() => window.open('https://www.youtube.com', '_blank')}
                        className="flex items-center gap-2"
                      >
                        <svg className="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        Go to YouTube
                      </Button>
                      <Button 
                        variant="outline" 
                        onClick={loadWatchLaterVideos}
                        className="flex items-center gap-2"
                      >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                      </Button>
                    </div>
                  </div>
                )}
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="search" className="mt-6">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Search className="h-5 w-5" />
                  Search YouTube Videos
                </CardTitle>
                <CardDescription>
                  Search for videos on YouTube
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="flex gap-2 mb-6">
                  <Input
                    type="text"
                    placeholder="Search for videos..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    onKeyPress={(e) => e.key === 'Enter' && searchVideos()}
                    className="flex-1"
                  />
                  <Button onClick={searchVideos} disabled={loading || !searchQuery.trim()}>
                    {loading ? (
                      <Loader2 className="h-4 w-4 animate-spin" />
                    ) : (
                      <Search className="h-4 w-4" />
                    )}
                  </Button>
                </div>

                {errorMessage && (
                  <Alert className="mb-4">
                    <AlertDescription>{errorMessage}</AlertDescription>
                  </Alert>
                )}
                
                {loading ? (
                  <div className="flex items-center justify-center py-8">
                    <Loader2 className="h-8 w-8 animate-spin" />
                    <span className="ml-2">Searching videos...</span>
                  </div>
                ) : (
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {searchResults.map((video) => (
                      <VideoCard key={video.id} video={video} />
                    ))}
                  </div>
                )}
                
                {!loading && searchResults.length === 0 && !errorMessage && searchQuery && (
                  <div className="text-center py-8 text-gray-500 dark:text-gray-400">
                    No videos found for "{searchQuery}".
                  </div>
                )}
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="playlists" className="mt-6">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <List className="h-5 w-5" />
                  My Playlists
                </CardTitle>
                <CardDescription>
                  Your YouTube playlists
                </CardDescription>
              </CardHeader>
              <CardContent>
                {errorMessage && (
                  <Alert className="mb-4">
                    <AlertDescription>{errorMessage}</AlertDescription>
                  </Alert>
                )}
                
                {loading ? (
                  <div className="flex items-center justify-center py-8">
                    <Loader2 className="h-8 w-8 animate-spin" />
                    <span className="ml-2">Loading playlists...</span>
                  </div>
                ) : (
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {playlists.map((playlist) => (
                      <PlaylistCard key={playlist.id} playlist={playlist} />
                    ))}
                  </div>
                )}
                
                {!loading && playlists.length === 0 && !errorMessage && (
                  <div className="text-center py-8 text-gray-500 dark:text-gray-400">
                    No playlists found.
                  </div>
                )}
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  );
}
