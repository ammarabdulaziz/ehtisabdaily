<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->infolist }}

        @foreach ($this->getRelationManagers() as $relationManager)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                {{-- <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $relationManager::getTitle($this->record, static::class) }}
                    </h3>
                </div> --}}
                <div class="p-6 mt-6">
                    @livewire($relationManager, [
                        'ownerRecord' => $this->record,
                        'pageClass' => static::class,
                    ])
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
