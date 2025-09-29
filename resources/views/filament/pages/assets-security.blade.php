<x-filament-panels::page>
    <div class="flex items-center justify-center min-h-[400px]">
        <div class="max-w-md w-full space-y-6">
            <div class="text-center">
                <div class="mx-auto mb-4 p-3 rounded-full bg-red-100 dark:bg-red-900/20 w-fit">
                    <x-heroicon-o-shield-check class="h-8 w-8 text-red-600 dark:text-red-400" />
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Security Required
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Enter the 5-digit security code to access assets
                </p>
            </div>

            <form wire:submit="verifyCode" class="space-y-4">
                {{ $this->form }}
                
                <div class="flex items-center justify-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Valid for 1 hour
                    </span>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
