export interface YouTubeVideo {
  id: string;
  playlistItemId?: string;
  title: string;
  description: string;
  thumbnail: string;
  channelTitle: string;
  publishedAt: string;
  duration?: string;
  url: string;
  viewCount?: number;
}

export interface YouTubePlaylist {
  id: string;
  title: string;
  description: string;
  thumbnail: string;
  itemCount: number;
}

export interface YouTubeApiResponse {
  success: boolean;
  videos?: YouTubeVideo[];
  playlists?: YouTubePlaylist[];
  count?: number;
  query?: string;
  nextPageToken?: string;
  error?: string;
  needsGoogleAuth?: boolean;
}

export type SearchOrder = 'relevance' | 'date' | 'viewCount' | 'rating';

export interface SearchFilters {
  order: SearchOrder;
  pageToken?: string;
}
