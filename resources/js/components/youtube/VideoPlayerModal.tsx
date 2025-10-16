import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
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
        <Dialog open={isOpen} onOpenChange={onClose}>
            <DialogContent className="max-w-4xl w-full max-h-[90vh] p-0">
                <DialogHeader className="p-6 pb-4">
                    <DialogTitle className="text-xl font-semibold line-clamp-2">
                        {video.title}
                    </DialogTitle>
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
                </div>
            </DialogContent>
        </Dialog>
    );
}
