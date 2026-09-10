<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Quick actions</x-slot>
        <x-slot name="description">Jump straight to the things you do most.</x-slot>

        <div class="fi-quick-actions">
            @foreach ($this->getActions() as $action)
                <a href="{{ $action['url'] }}"
                   @if ($action['label'] === 'Visit site') target="_blank" rel="noopener" @endif
                   class="fi-quick-actions__item fi-quick-actions__item--{{ $action['kind'] }}">
                    <span class="fi-quick-actions__icon">
                        <x-filament::icon :icon="$action['icon']" class="h-5 w-5" />
                    </span>

                    <span class="fi-quick-actions__label">{{ $action['label'] }}</span>

                    @if (! empty($action['badge']))
                        <span class="fi-quick-actions__badge"
                              title="{{ $action['badge'] }} new in the last 7 days">
                            {{ $action['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
