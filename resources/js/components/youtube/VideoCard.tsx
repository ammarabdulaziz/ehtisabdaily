import { YouTubeVideo } from '@/types/youtube';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Trash2, Play, Plus, Check, Loader2 } from 'lucide-react';

interface VideoCardProps {
  video: YouTubeVideo;
  onPlay?: (video: YouTubeVideo) => void;
  onRemove?: (video: YouTubeVideo) => void;
  onAddToPlaylist?: (video: YouTubeVideo) => void;
  showRemoveButton?: boolean;
  showAddToPlaylistButton?: boolean;
  isAddingToPlaylist?: boolean;
  isAddedToPlaylist?: boolean;
}

export default function VideoCard({ video, onPlay, onRemove, onAddToPlaylist, showRemoveButton = false, showAddToPlaylistButton = false, isAddingToPlaylist = false, isAddedToPlaylist = false }: VideoCardProps) {
  const [imageError, setImageError] = useState(false);
  const [imageLoading, setImageLoading] = useState(true);

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
  };

  const handleCardClick = () => {
    if (onPlay) {
      onPlay(video);
    }
  };

  const handlePlayClick = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (onPlay) {
      onPlay(video);
    }
  };

  const handleRemoveClick = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (onRemove) {
      onRemove(video);
    }
  };

  const handleAddToPlaylistClick = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (onAddToPlaylist) {
      onAddToPlaylist(video);
    }
  };

  const handleImageError = () => {
    setImageError(true);
    setImageLoading(false);
  };

  const handleImageLoad = () => {
    setImageLoading(false);
  };

  return (
    <div 
      className="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 group cursor-pointer"
      onClick={handleCardClick}
    >
      <div className="relative">
        {imageError ? (
          <div className="w-full h-48 bg-gray-200 dark:bg-gray-700 rounded-t-lg flex items-center justify-center">
            <div className="text-center text-gray-500 dark:text-gray-400">
              <svg className="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p className="text-sm">Thumbnail unavailable</p>
            </div>
          </div>
        ) : (
          <>
            <img
              src={video.thumbnail}
              alt={video.title}
              className="w-full h-48 object-cover rounded-t-lg group-hover:opacity-90 transition-opacity duration-200"
              onError={handleImageError}
              onLoad={handleImageLoad}
            />
            {imageLoading && (
              <div className="absolute inset-0 w-full h-48 bg-gray-200 dark:bg-gray-700 rounded-t-lg flex items-center justify-center z-10">
                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 dark:border-white"></div>
              </div>
            )}
          </>
        )}
        
        {/* Play button overlay */}
        <div className="absolute inset-0 bg-transparent group-hover:bg-black group-hover:bg-opacity-20 transition-all duration-200 rounded-t-lg flex items-center justify-center">
          <Button
            variant="ghost"
            size="icon"
            onClick={handlePlayClick}
            className="opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-black/50 hover:bg-black/70 text-white"
          >
            <Play className="w-6 h-6" />
          </Button>
        </div>

        {/* Action buttons */}
        <div className="absolute top-2 right-2 flex gap-2">
          {/* Add to playlist button */}
          {showAddToPlaylistButton && onAddToPlaylist && (
            <Button
              variant="secondary"
              size="icon"
              onClick={handleAddToPlaylistClick}
              disabled={isAddingToPlaylist || isAddedToPlaylist}
              className={`h-8 w-8 opacity-0 group-hover:opacity-100 transition-opacity duration-200 ${
                isAddedToPlaylist 
                  ? 'bg-green-600 hover:bg-green-700 text-white' 
                  : 'bg-blue-600 hover:bg-blue-700 text-white'
              }`}
              title={isAddedToPlaylist ? "Added to Ehtisab Play" : "Add to Ehtisab Play"}
            >
              {isAddingToPlaylist ? (
                <Loader2 className="w-4 h-4 animate-spin" />
              ) : isAddedToPlaylist ? (
                <Check className="w-4 h-4" />
              ) : (
                <Plus className="w-4 h-4" />
              )}
            </Button>
          )}
          
          {/* Remove button */}
          {showRemoveButton && onRemove && (
            <Button
              variant="destructive"
              size="icon"
              onClick={handleRemoveClick}
              className="h-8 w-8 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
            >
              <Trash2 className="w-4 h-4" />
            </Button>
          )}
        </div>
      </div>
      
      <div className="p-4">
        <h3 className="font-semibold text-gray-900 dark:text-white line-clamp-2 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
          {video.title}
        </h3>
        
        
        <div className="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
          <span className="font-medium">{video.channelTitle}</span>
          <span>{formatDate(video.publishedAt)}</span>
        </div>
        
        {video.duration && (
          <div className="mt-2">
            <span className="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-2 py-1 rounded">
              {video.duration}
            </span>
          </div>
        )}
      </div>
    </div>
  );
}
