import { useState, useEffect, useRef, useCallback } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Skeleton } from '@/components/ui/skeleton';
import { Loader2, Search } from 'lucide-react';
import VideoCard from '@/components/youtube/VideoCard';
import VideoPlayerModal from '@/components/youtube/VideoPlayerModal';
import { YouTubeVideo, YouTubeApiResponse, SearchOrder } from '@/types/youtube';

const EHTISAB_PLAYLIST_ID = 'PLX_ZsXanRYBMVv-4LxApTB2jEW3hsieEy';

export default function SearchTab() {
    const [searchQuery, setSearchQuery] = useState('');
    const [videos, setVideos] = useState<YouTubeVideo[]>([]);
    const [loading, setLoading] = useState(false);
    const [loadingMore, setLoadingMore] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [nextPageToken, setNextPageToken] = useState<string | null>(null);
    const [selectedVideo, setSelectedVideo] = useState<YouTubeVideo | null>(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [order, setOrder] = useState<SearchOrder>('relevance');
    const [hasSearched, setHasSearched] = useState(false);
    const [addingToPlaylist, setAddingToPlaylist] = useState<Set<string>>(new Set());
    const [addedToPlaylist, setAddedToPlaylist] = useState<Set<string>>(new Set());
    const [validatingQuery, setValidatingQuery] = useState(false);

    const observerRef = useRef<IntersectionObserver | null>(null);
    const loadMoreRef = useRef<HTMLDivElement | null>(null);

    const validateSearchQuery = useCallback(async (query: string): Promise<boolean> => {
        setValidatingQuery(true);
        setError(null);

        try {
            const response = await fetch('/api/youtube/validate-search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
                },
                body: JSON.stringify({ query }),
            });

            const data = await response.json();

            if (data.success && data.isAppropriate) {
                return true;
            } else {
                setError(data.message || data.reason || 'This search query appears to contain inappropriate content. Please try a different search term focused on beneficial knowledge.');
                return false;
            }
        } catch {
            setError('Content validation failed. Please try again.');
            return false;
        } finally {
            setValidatingQuery(false);
        }
    }, []);

    const searchVideos = useCallback(async (query: string, pageToken?: string, append = false) => {
        if (!query.trim()) return;

        const loadingState = append ? setLoadingMore : setLoading;
        loadingState(true);
        setError(null);

        try {
            const params = new URLSearchParams({
                q: query,
                maxResults: '25',
                order: order,
            });
            if (pageToken) {
                params.append('pageToken', pageToken);
            }

            const response = await fetch(`/api/youtube/search?${params}`);
            const data: YouTubeApiResponse = await response.json();

            if (data.success && data.videos) {
                if (append) {
                    setVideos(prev => [...prev, ...data.videos!]);
                } else {
                    setVideos(data.videos);
                }
                setNextPageToken(data.nextPageToken || null);
                setHasSearched(true);
            } else {
                setError(data.error || 'Failed to search videos');
            }
        } catch {
            setError('Network error occurred');
        } finally {
            loadingState(false);
        }
    }, [order]);

    const loadMoreVideos = useCallback(() => {
        if (nextPageToken && !loadingMore && searchQuery.trim()) {
            searchVideos(searchQuery, nextPageToken, true);
        }
    }, [nextPageToken, loadingMore, searchQuery, searchVideos]);

    const handleSearch = useCallback(async () => {
        if (!searchQuery.trim()) return;

        // First validate the search query
        const isValid = await validateSearchQuery(searchQuery);
        if (!isValid) {
            return; // Error message is already set by validateSearchQuery
        }

        // If validation passes, proceed with search
        setVideos([]);
        setNextPageToken(null);
        searchVideos(searchQuery);
    }, [searchQuery, validateSearchQuery, searchVideos]);

    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setSearchQuery(e.target.value);
    };

    const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'Enter') {
            handleSearch();
        }
    };

    const handlePlayVideo = (video: YouTubeVideo) => {
        setSelectedVideo(video);
        setIsModalOpen(true);
    };

    const handleAddToPlaylist = async (video: YouTubeVideo) => {
        if (addingToPlaylist.has(video.id) || addedToPlaylist.has(video.id)) {
            return;
        }

        setAddingToPlaylist(prev => new Set(prev).add(video.id));
        setError(null);

        try {
            const response = await fetch(`/api/youtube/playlist/${EHTISAB_PLAYLIST_ID}/add`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
                },
                body: JSON.stringify({
                    videoId: video.id,
                }),
            });

            const data = await response.json();

            if (data.success) {
                setAddedToPlaylist(prev => new Set(prev).add(video.id));
            } else {
                setError(data.error || 'Failed to add video to playlist');
            }
        } catch {
            setError('Network error occurred while adding video to playlist');
        } finally {
            setAddingToPlaylist(prev => {
                const newSet = new Set(prev);
                newSet.delete(video.id);
                return newSet;
            });
        }
    };

    const handleOrderChange = (newOrder: SearchOrder) => {
        setOrder(newOrder);
        if (searchQuery.trim()) {
            setVideos([]);
            setNextPageToken(null);
            searchVideos(searchQuery);
        }
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


    return (
        <>
            <Card>
                <CardHeader>
                    <CardTitle className="flex items-center gap-2">
                        <Search className="h-5 w-5" />
                        Search YouTube Videos
                    </CardTitle>
                    <CardDescription>
                        Search for videos on YouTube with filters and sorting options
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div className="space-y-3">
                        {/* Search Input and Filters */}
                        <div className="flex flex-col sm:flex-row gap-3">
                            <div className="flex-1">
                                <Input
                                    type="text"
                                    placeholder="Search for videos..."
                                    value={searchQuery}
                                    onChange={handleInputChange}
                                    onKeyDown={handleKeyDown}
                                    className="w-full"
                                />
                            </div>
                            <div className="flex gap-2">
                                <Select value={order} onValueChange={handleOrderChange}>
                                    <SelectTrigger className="w-40">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="relevance">Relevance</SelectItem>
                                        <SelectItem value="date">Date</SelectItem>
                                        <SelectItem value="viewCount">View Count</SelectItem>
                                        <SelectItem value="rating">Rating</SelectItem>
                                    </SelectContent>
                                </Select>
                                <Button 
                                    onClick={handleSearch} 
                                    disabled={loading || validatingQuery || !searchQuery.trim()}
                                    className="flex items-center gap-2"
                                >
                                    {loading || validatingQuery ? (
                                        <Loader2 className="h-4 w-4 animate-spin" />
                                    ) : (
                                        <Search className="h-4 w-4" />
                                    )}
                                    {validatingQuery ? 'Validating...' : 'Search'}
                                </Button>
                            </div>
                        </div>

                        {error && (
                            <Alert>
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
                                            onAddToPlaylist={handleAddToPlaylist}
                                            showAddToPlaylistButton={true}
                                            isAddingToPlaylist={addingToPlaylist.has(video.id)}
                                            isAddedToPlaylist={addedToPlaylist.has(video.id)}
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
                        ) : hasSearched && !loading ? (
                            <div className="text-center py-8">
                                <div className="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3">
                                    <Search className="h-12 w-12" />
                                </div>
                                <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                    No videos found
                                </h3>
                                <p className="text-gray-500 dark:text-gray-400">
                                    Try adjusting your search terms or filters.
                                </p>
                            </div>
                        ) : (
                            <div className="text-center py-8">
                                <div className="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3">
                                    <Search className="h-12 w-12" />
                                </div>
                                <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                    Search YouTube Videos
                                </h3>
                                <p className="text-gray-500 dark:text-gray-400">
                                    Enter a search term above to find videos on YouTube.
                                </p>
                            </div>
                        )}
                    </div>
                </CardContent>
            </Card>

            <VideoPlayerModal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                video={selectedVideo}
            />
        </>
    );
}
