<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DuaCacheService;
use Illuminate\Console\Command;

class ClearDuasCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duas:cache:clear {--user= : Specific user ID to clear cache for} {--all : Clear cache for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the duas cache for all users or a specific user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cacheService = app(DuaCacheService::class);
        $userId = $this->option('user');
        $clearAll = $this->option('all');

        if ($userId) {
            // Clear cache for specific user
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }

            $this->info("Clearing duas cache for user: {$user->name} (ID: {$userId})");
            $cacheService->clearUserCache($userId);
            $this->info("Cache cleared successfully for user {$user->name}.");
        } elseif ($clearAll) {
            // Clear cache for all users
            $this->info('Clearing duas cache for all users...');
            $cacheService->clearAllCache();
            $this->info('Cache cleared successfully for all users.');
        } else {
            $this->error('Please specify either --user=ID or --all option.');
            $this->line('Usage:');
            $this->line('  php artisan duas:cache:clear --user=1');
            $this->line('  php artisan duas:cache:clear --all');
            return 1;
        }

        return 0;
    }
}
