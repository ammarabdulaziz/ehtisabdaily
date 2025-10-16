import { useState, useEffect, useRef, useCallback } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Skeleton } from '@/components/ui/skeleton';
import { Loader2, Play, RefreshCw } from 'lucide-react';
import VideoCard from '@/components/youtube/VideoCard';
import VideoPlayerModal from '@/components/youtube/VideoPlayerModal';
import ConfirmationDialog from '@/components/ui/confirmation-dialog';
import { YouTubeVideo, YouTubeApiResponse } from '@/types/youtube';

const EHTISAB_PLAYLIST_ID = 'PLX_ZsXanRYBMVv-4LxApTB2jEW3hsieEy';

export default function EhtisabPlay() {
    const [videos, setVideos] = useState<YouTubeVideo[]>([]);
    const [loading, setLoading] = useState(false);
    const [loadingMore, setLoadingMore] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [nextPageToken, setNextPageToken] = useState<string | null>(null);
    const [selectedVideo, setSelectedVideo] = useState<YouTubeVideo | null>(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [removingVideoId, setRemovingVideoId] = useState<string | null>(null);
    const [videoToRemove, setVideoToRemove] = useState<YouTubeVideo | null>(null);
    const [isConfirmationOpen, setIsConfirmationOpen] = useState(false);

    const observerRef = useRef<IntersectionObserver | null>(null);
    const loadMoreRef = useRef<HTMLDivElement | null>(null);

    const loadVideos = useCallback(async (pageToken?: string, append = false) => {
        const loadingState = append ? setLoadingMore : setLoading;
        loadingState(true);
        setError(null);

        try {
            const params = new URLSearchParams({
                maxResults: '25',
            });
            if (pageToken) {
                params.append('pageToken', pageToken);
            }

            const response = await fetch(`/api/youtube/playlist/${EHTISAB_PLAYLIST_ID}?${params}`);
            const data: YouTubeApiResponse = await response.json();

            if (data.success && data.videos) {
                if (append) {
                    setVideos(prev => [...prev, ...data.videos!]);
                } else {
                    setVideos(data.videos);
                }
                setNextPageToken(data.nextPageToken || null);
            } else {
                setError(data.error || 'Failed to load playlist videos');
            }
        } catch {
            setError('Network error occurred');
        } finally {
            loadingState(false);
        }
    }, []);

    const loadMoreVideos = useCallback(() => {
        if (nextPageToken && !loadingMore) {
            loadVideos(nextPageToken, true);
        }
    }, [nextPageToken, loadingMore, loadVideos]);

    const handlePlayVideo = (video: YouTubeVideo) => {
        setSelectedVideo(video);
        setIsModalOpen(true);
    };

    const handleRemoveVideo = (video: YouTubeVideo) => {
        if (!video.playlistItemId) return;
        
        setVideoToRemove(video);
        setIsConfirmationOpen(true);
    };

    const confirmRemoveVideo = async () => {
        if (!videoToRemove || !videoToRemove.playlistItemId) return;

        setRemovingVideoId(videoToRemove.id);
        setIsConfirmationOpen(false);
        
        try {
            const response = await fetch(`/api/youtube/playlist/${EHTISAB_PLAYLIST_ID}/item/${videoToRemove.playlistItemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
                },
            });
            const data = await response.json();

            if (data.success) {
                setVideos(prev => prev.filter(v => v.id !== videoToRemove.id));
            } else {
                setError(data.error || 'Failed to remove video');
            }
        } catch {
            setError('Network error occurred while removing video');
        } finally {
            setRemovingVideoId(null);
            setVideoToRemove(null);
        }
    };

    const cancelRemoveVideo = () => {
        setIsConfirmationOpen(false);
        setVideoToRemove(null);
    };

    const handleRefresh = () => {
        setVideos([]);
        setNextPageToken(null);
        loadVideos();
    };

    // Set up intersection observer for infinite scroll
    useEffect(() => {
        if (observerRef.current) {
            observerRef.current.disconnect();
        }

        observerRef.current = new IntersectionObserver(
            (entries) => {
                if (entries[0].isIntersecting && nextPageToken && !loadingMore) {
                    loadMoreVideos();
                }
            },
            { threshold: 0.1 }
        );

        if (loadMoreRef.current) {
            observerRef.current.observe(loadMoreRef.current);
        }

        return () => {
            if (observerRef.current) {
                observerRef.current.disconnect();
            }
        };
    }, [nextPageToken, loadingMore, loadMoreVideos]);

    // Load initial videos
    useEffect(() => {
        loadVideos();
    }, [loadVideos]);

    return (
        <>
            <Card>
                <CardHeader>
                    <div className="flex items-center justify-between">
                        <div>
                            <CardTitle className="flex items-center gap-2">
                                <Play className="h-5 w-5" />
                                Ehtisab Play
                            </CardTitle>
                            <CardDescription>
                                Videos from your Ehtisab Play playlist
                            </CardDescription>
                        </div>
                        <Button
                            variant="outline"
                            onClick={handleRefresh}
                            disabled={loading}
                            className="flex items-center gap-2"
                        >
                            <RefreshCw className={`h-4 w-4 ${loading ? 'animate-spin' : ''}`} />
                            Refresh
                        </Button>
                    </div>
                </CardHeader>
                <CardContent>
                    {error && (
                        <Alert className="mb-4">
                            <AlertDescription>{error}</AlertDescription>
                        </Alert>
                    )}

                    {loading ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {Array.from({ length: 6 }).map((_, i) => (
                                <div key={i} className="space-y-3">
                                    <Skeleton className="h-48 w-full rounded-lg" />
                                    <Skeleton className="h-4 w-3/4" />
                                    <Skeleton className="h-4 w-1/2" />
                                </div>
                            ))}
                        </div>
                    ) : videos.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {videos.map((video) => (
                                    <VideoCard
                                        key={video.id}
                                        video={video}
                                        onPlay={handlePlayVideo}
                                        onRemove={handleRemoveVideo}
                                        showRemoveButton={true}
                                    />
                                ))}
                            </div>

                            {/* Infinite scroll trigger */}
                            {nextPageToken && (
                                <div ref={loadMoreRef} className="flex justify-center py-8">
                                    {loadingMore ? (
                                        <div className="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                            <Loader2 className="h-4 w-4 animate-spin" />
                                            <span>Loading more videos...</span>
                                        </div>
                                    ) : (
                                        <div className="text-gray-500 dark:text-gray-400 text-sm">
                                            Scroll to load more
                                        </div>
                                    )}
                                </div>
                            )}
                        </>
                    ) : (
                        <div className="text-center py-8">
                            <div className="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3">
                                <Play className="h-12 w-12" />
                            </div>
                            <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                No videos in Ehtisab Play
                            </h3>
                            <p className="text-gray-500 dark:text-gray-400 mb-4 max-w-md mx-auto">
                                Your EhtisabDaily playlist appears to be empty. Add videos to your playlist on YouTube to see them here.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-3 justify-center">
                                <Button 
                                    variant="outline" 
                                    onClick={() => window.open('https://www.youtube.com/playlist?list=PLX_ZsXanRYBMVv-4LxApTB2jEW3hsieEy', '_blank')}
                                    className="flex items-center gap-2"
                                >
                                    <svg className="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                    View on YouTube
                                </Button>
                                <Button 
                                    variant="outline" 
                                    onClick={handleRefresh}
                                    className="flex items-center gap-2"
                                >
                                    <RefreshCw className="w-4 h-4" />
                                    Refresh
                                </Button>
                            </div>
                        </div>
                    )}
                </CardContent>
            </Card>

            <VideoPlayerModal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                video={selectedVideo}
            />

            <ConfirmationDialog
                isOpen={isConfirmationOpen}
                onClose={cancelRemoveVideo}
                onConfirm={confirmRemoveVideo}
                title="Remove Video from Playlist"
                description={`Are you sure you want to remove "${videoToRemove?.title}" from your EhtisabDaily playlist? This action cannot be undone.`}
                confirmText="Remove"
                cancelText="Cancel"
                variant="destructive"
                loading={removingVideoId === videoToRemove?.id}
            />
        </>
    );
}
