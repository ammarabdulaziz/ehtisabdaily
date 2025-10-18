import { Dialog, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { X } from 'lucide-react';
import * as DialogPrimitive from "@radix-ui/react-dialog";
import { cn } from "@/lib/utils";
import { YouTubeVideo } from '@/types/youtube';

interface VideoPlayerModalProps {
    isOpen: boolean;
    onClose: () => void;
    video: YouTubeVideo | null;
}

export default function VideoPlayerModal({ isOpen, onClose, video }: VideoPlayerModalProps) {
    if (!video) return null;

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={() => {}}>
            <DialogPrimitive.Portal>
                <DialogPrimitive.Overlay className="fixed inset-0 z-50 bg-black/80" />
                <DialogPrimitive.Content
                    className={cn(
                        "fixed top-[50%] left-[50%] z-50 grid w-full max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] gap-4 rounded-lg border bg-background p-6 shadow-lg duration-200 sm:max-w-lg",
                        "max-w-4xl w-full max-h-[90vh] p-0"
                    )}
                >
                <DialogHeader className="p-6 pb-4">
                    <div className="flex items-center justify-between">
                        <DialogTitle className="text-xl font-semibold line-clamp-2">
                            {video.title}
                        </DialogTitle>
                        <Button
                            variant="ghost"
                            size="icon"
                            onClick={onClose}
                            className="h-8 w-8"
                        >
                            <X className="h-4 w-4" />
                        </Button>
                    </div>
                </DialogHeader>
                
                <div className="px-6 pb-6">
                    {/* Video Player */}
                    <div className="relative w-full h-0 pb-[56.25%] mb-4">
                        <iframe
                            src={`https://www.youtube.com/embed/${video.id}?autoplay=1&rel=0`}
                            title={video.title}
                            className="absolute top-0 left-0 w-full h-full rounded-lg"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowFullScreen
                        />
                    </div>
                    
                    {/* Video Info */}
                    <div className="space-y-2">
                        <div className="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                            <span className="font-medium">{video.channelTitle}</span>
                            <span>{formatDate(video.publishedAt)}</span>
                        </div>
                        
                        {video.duration && (
                            <div className="flex items-center gap-2">
                                <span className="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-2 py-1 rounded">
                                    {video.duration}
                                </span>
                            </div>
                        )}
                    </div>
                    
                    {/* Play Full Video Button */}
                    <div className="mt-4">
                        <Button
                            onClick={() => {
                                // Store current URL for return navigation
                                sessionStorage.setItem('returnUrl', window.location.href);
                                
                                // Build query parameters
                                const params = new URLSearchParams({
                                    id: video.id,
                                    title: video.title,
                                    description: video.description,
                                    thumbnail: video.thumbnail,
                                    channelTitle: video.channelTitle,
                                    publishedAt: video.publishedAt,
                                    duration: video.duration || '',
                                    url: video.url
                                });
                                
                                // Redirect to external video player
                                // You can set this in your .env file or config
                                const externalPlayerUrl = import.meta.env.VITE_EXTERNAL_PLAYER_URL || 'https://ammarabdulaziz.github.io/youtube-viewer';
                                window.location.href = `${externalPlayerUrl}?${params.toString()}`;
                            }}
                            className="w-full"
                        >
                            Play Full Video
                        </Button>
                    </div>
                </div>
                </DialogPrimitive.Content>
            </DialogPrimitive.Portal>
        </Dialog>
    );
}
