import { YouTubePlaylist } from '@/types/youtube';

interface PlaylistCardProps {
  playlist: YouTubePlaylist;
}

export default function PlaylistCard({ playlist }: PlaylistCardProps) {
  const handlePlaylistClick = () => {
    const playlistUrl = `https://www.youtube.com/playlist?list=${playlist.id}`;
    window.open(playlistUrl, '_blank', 'noopener,noreferrer');
  };

  return (
    <div 
      className="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer group"
      onClick={handlePlaylistClick}
    >
      <div className="relative">
        <img
          src={playlist.thumbnail}
          alt={playlist.title}
          className="w-full h-48 object-cover rounded-t-lg group-hover:opacity-90 transition-opacity duration-200"
        />
        <div className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-t-lg flex items-center justify-center">
          <div className="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            <svg className="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
            </svg>
          </div>
        </div>
        <div className="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
          {playlist.itemCount} videos
        </div>
      </div>
      
      <div className="p-4">
        <h3 className="font-semibold text-gray-900 dark:text-white line-clamp-2 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
          {playlist.title}
        </h3>
        
        <p className="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
          {playlist.description}
        </p>
        
        <div className="mt-3 flex items-center justify-between">
          <span className="text-sm text-gray-500 dark:text-gray-400">
            {playlist.itemCount} {playlist.itemCount === 1 ? 'video' : 'videos'}
          </span>
          <span className="text-xs text-blue-600 dark:text-blue-400 font-medium">
            View Playlist →
          </span>
        </div>
      </div>
    </div>
  );
}
