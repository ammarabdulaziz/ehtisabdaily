<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DuaCacheService;
use Illuminate\Console\Command;

class WarmUpDuasCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duas:cache:warm {--user= : Specific user ID to warm cache for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up the duas cache for all users or a specific user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cacheService = app(DuaCacheService::class);
        $userId = $this->option('user');

        if ($userId) {
            // Warm cache for specific user
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }

            $this->info("Warming up duas cache for user: {$user->name} (ID: {$userId})");
            $cacheService->warmUpUserCache($userId);
            $this->info("Cache warmed up successfully for user {$user->name}.");
        } else {
            // Warm cache for all users
            $this->info('Warming up duas cache for all users...');
            
            $users = User::all();
            $progressBar = $this->output->createProgressBar($users->count());
            $progressBar->start();

            foreach ($users as $user) {
                $cacheService->warmUpUserCache($user->id);
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();
            $this->info("Cache warmed up successfully for {$users->count()} users.");
        }

        return 0;
    }
}
