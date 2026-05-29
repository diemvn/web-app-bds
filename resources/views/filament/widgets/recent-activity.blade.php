<x-filament-widgets::widget>
    <x-filament::section heading="Hoạt động gần đây">
        <div class="space-y-3">
            @forelse ($this->getActivities() as $activity)
                <div class="flex gap-3 border-l-2 border-primary-500 pl-3">
                    <div>
                        <p class="font-medium text-sm">{{ $activity->title }}</p>
                        @if ($activity->description)
                            <p class="text-xs text-gray-500">{{ $activity->description }}</p>
                        @endif
                        <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Chưa có hoạt động.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
