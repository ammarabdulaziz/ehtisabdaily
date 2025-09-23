<?php

namespace App\Services;

use App\Models\Dua;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DuaCacheService
{
    private const CACHE_PREFIX = 'duas:';
    private const CACHE_TTL = null; // Forever cache

    /**
     * Get all duas for the current user with caching
     */
    public function getAllDuasForUser(?int $userId = null): Collection
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return new Collection();
        }

        $cacheKey = $this->getUserCacheKey($userId);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            Log::info("Cache miss for duas for user {$userId}, fetching from database");
            
            return Dua::where('user_id', $userId)
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get();
        });
    }

    /**
     * Get duas by category for the current user with caching
     */
    public function getDuasByCategory(string $category, ?int $userId = null): Collection
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return new Collection();
        }

        $cacheKey = $this->getUserCategoryCacheKey($userId, $category);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId, $category) {
            Log::info("Cache miss for duas category '{$category}' for user {$userId}, fetching from database");
            
            return Dua::where('user_id', $userId)
                ->whereJsonContains('categories', $category)
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get();
        });
    }

    /**
     * Get duas by source for the current user with caching
     */
    public function getDuasBySource(string $source, ?int $userId = null): Collection
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return new Collection();
        }

        $cacheKey = $this->getUserSourceCacheKey($userId, $source);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId, $source) {
            Log::info("Cache miss for duas source '{$source}' for user {$userId}, fetching from database");
            
            return Dua::where('user_id', $userId)
                ->where('source', $source)
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get();
        });
    }

    /**
     * Get a single dua by ID with caching
     */
    public function getDuaById(int $duaId, ?int $userId = null): ?Dua
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return null;
        }

        $cacheKey = $this->getDuaCacheKey($duaId, $userId);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($duaId, $userId) {
            Log::info("Cache miss for dua {$duaId} for user {$userId}, fetching from database");
            
            return Dua::where('user_id', $userId)
                ->where('id', $duaId)
                ->first();
        });
    }

    /**
     * Get duas count for the current user with caching
     */
    public function getDuasCount(?int $userId = null): int
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return 0;
        }

        $cacheKey = $this->getUserCountCacheKey($userId);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            Log::info("Cache miss for duas count for user {$userId}, fetching from database");
            
            return Dua::where('user_id', $userId)->count();
        });
    }

    /**
     * Get categories for the current user with caching
     */
    public function getCategoriesForUser(?int $userId = null): array
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return [];
        }

        $cacheKey = $this->getUserCategoriesCacheKey($userId);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            Log::info("Cache miss for categories for user {$userId}, fetching from database");
            
            $duas = Dua::where('user_id', $userId)
                ->whereNotNull('categories')
                ->get();

            $categories = collect();
            foreach ($duas as $dua) {
                if (is_array($dua->categories)) {
                    $categories = $categories->merge($dua->categories);
                }
            }

            return $categories->unique()->sort()->values()->toArray();
        });
    }

    /**
     * Get sources for the current user with caching
     */
    public function getSourcesForUser(?int $userId = null): array
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return [];
        }

        $cacheKey = $this->getUserSourcesCacheKey($userId);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            Log::info("Cache miss for sources for user {$userId}, fetching from database");
            
            return Dua::where('user_id', $userId)
                ->whereNotNull('source')
                ->distinct()
                ->pluck('source')
                ->sort()
                ->values()
                ->toArray();
        });
    }

    /**
     * Clear all cache for a specific user
     */
    public function clearUserCache(?int $userId = null): void
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return;
        }

        Log::info("Clearing all duas cache for user {$userId}");

        // Clear all user-specific cache keys
        $cacheKeys = [
            $this->getUserCacheKey($userId),
            $this->getUserCountCacheKey($userId),
            $this->getUserCategoriesCacheKey($userId),
            $this->getUserSourcesCacheKey($userId),
        ];

        foreach ($cacheKeys as $cacheKey) {
            Cache::forget($cacheKey);
        }

        // Clear category and source specific caches
        $categories = $this->getCategoriesForUser($userId);
        foreach ($categories as $category) {
            Cache::forget($this->getUserCategoryCacheKey($userId, $category));
        }

        $sources = $this->getSourcesForUser($userId);
        foreach ($sources as $source) {
            Cache::forget($this->getUserSourceCacheKey($userId, $source));
        }
    }

    /**
     * Clear cache for a specific dua
     */
    public function clearDuaCache(int $duaId, ?int $userId = null): void
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return;
        }

        Log::info("Clearing cache for dua {$duaId} for user {$userId}");

        // Clear the specific dua cache
        Cache::forget($this->getDuaCacheKey($duaId, $userId));
        
        // Clear user-level caches as they might be affected
        $this->clearUserCache($userId);
    }

    /**
     * Clear all duas cache (for admin operations)
     */
    public function clearAllCache(): void
    {
        Log::info("Clearing all duas cache");
        
        // Since we can't use tags, we need to clear cache keys manually
        // This is a simplified approach - in production you might want to use Redis with tags
        $this->clearCacheByPattern(self::CACHE_PREFIX . '*');
    }

    /**
     * Clear cache keys by pattern (helper method)
     */
    private function clearCacheByPattern(string $pattern): void
    {
        // For database cache, we need to clear manually
        // This is a simplified implementation
        // In production with Redis, you could use SCAN with pattern matching
        
        // Get all users and clear their cache
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $this->clearUserCache($user->id);
        }
    }

    /**
     * Warm up cache for a user
     */
    public function warmUpUserCache(?int $userId = null): void
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        
        if (!$userId) {
            return;
        }

        Log::info("Warming up duas cache for user {$userId}");

        // Pre-load all user duas
        $this->getAllDuasForUser($userId);
        
        // Pre-load categories and sources
        $this->getCategoriesForUser($userId);
        $this->getSourcesForUser($userId);
        
        // Pre-load count
        $this->getDuasCount($userId);
    }

    /**
     * Get cache key for user's all duas
     */
    private function getUserCacheKey(int $userId): string
    {
        return self::CACHE_PREFIX . "user:{$userId}:all";
    }

    /**
     * Get cache key for user's dua count
     */
    private function getUserCountCacheKey(int $userId): string
    {
        return self::CACHE_PREFIX . "user:{$userId}:count";
    }

    /**
     * Get cache key for user's categories
     */
    private function getUserCategoriesCacheKey(int $userId): string
    {
        return self::CACHE_PREFIX . "user:{$userId}:categories";
    }

    /**
     * Get cache key for user's sources
     */
    private function getUserSourcesCacheKey(int $userId): string
    {
        return self::CACHE_PREFIX . "user:{$userId}:sources";
    }

    /**
     * Get cache key for user's duas by category
     */
    private function getUserCategoryCacheKey(int $userId, string $category): string
    {
        return self::CACHE_PREFIX . "user:{$userId}:category:" . md5($category);
    }

    /**
     * Get cache key for user's duas by source
     */
    private function getUserSourceCacheKey(int $userId, string $source): string
    {
        return self::CACHE_PREFIX . "user:{$userId}:source:" . md5($source);
    }

    /**
     * Get cache key for specific dua
     */
    private function getDuaCacheKey(int $duaId, int $userId): string
    {
        return self::CACHE_PREFIX . "user:{$userId}:dua:{$duaId}";
    }
}
