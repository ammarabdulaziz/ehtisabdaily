<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="text-center mb-6">
            <div class="mx-auto mb-4 p-3 rounded-full bg-red-100 dark:bg-red-900/20 w-fit">
                <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Security Required</h2>
            <p class="text-gray-600 dark:text-gray-400">Enter the 5-digit security code to access assets</p>
        </div>

        <form wire:submit="verifyCode">
            <div class="mb-4">
                <label for="securityCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Security Code
                </label>
                <input 
                    type="text" 
                    id="securityCode"
                    wire:model="securityCode" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white text-center text-lg tracking-widest"
                    placeholder="00000"
                    maxlength="5"
                    autocomplete="off"
                    inputmode="numeric"
                >
                @error('securityCode')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-6">
                <button 
                    type="button" 
                    wire:click="toggleLock"
                    class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                >
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($isLocked)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        @endif
                    </svg>
                    {{ $isLocked ? 'Unlock' : 'Lock' }} Security
                </button>
                
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    Valid for 1 hour
                </span>
            </div>

            <button 
                type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
            >
                Verify Code
            </button>
        </form>
    </div>
</div>
