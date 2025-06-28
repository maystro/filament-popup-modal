<div
    x-data="{
        show: @entangle('show'),
        timeout: @entangle('timeout'),
        progress: @entangle('progress'),
        showProgress: @entangle('showProgress')
    }"
    x-init="
        // Listen for auto-close event
        $wire.on('auto-close-modal', (event) => {
            if (event.timeout > 0) {
                setTimeout(() => {
                    show = false;
                    $wire.close();
                }, event.timeout);
            }
        });

        // Listen for progress simulation
        $wire.on('simulate-progress', (event) => {
            if (event.modalId === $wire.id) {
                let currentProgress = 0;
                const progressInterval = setInterval(() => {
                    currentProgress += 10;
                    $wire.dispatch('update-popup-modal-progress', {
                        id: event.modalId,
                        progress: currentProgress
                    });
                    
                    if (currentProgress >= 100) {
                        clearInterval(progressInterval);
                        setTimeout(() => {
                            $wire.close();
                        }, {{ config('popup-modal.progress.completion_delay', 1500) }}); // Close after showing 100%
                    }
                }, {{ config('popup-modal.progress.update_interval', 500) }}); // Update interval
            }
        });

        // Listen for data processing progress
        $wire.on('process-data-progress', (event) => {
            if (event.modalId === $wire.id) {
                let currentProgress = 0;
                const steps = [
                    { progress: 20, message: 'Preparing data...' },
                    { progress: 40, message: 'Validating data...' },
                    { progress: 60, message: 'Processing data...' },
                    { progress: 80, message: 'Saving results...' },
                    { progress: 100, message: 'Completed successfully!' }
                ];
                
                let stepIndex = 0;
                const processInterval = setInterval(() => {
                    if (stepIndex < steps.length) {
                        const step = steps[stepIndex];
                        $wire.dispatch('update-popup-modal-progress', {
                            id: event.modalId,
                            progress: step.progress
                        });
                        stepIndex++;
                    }
                    
                    if (stepIndex >= steps.length) {
                        clearInterval(processInterval);
                        setTimeout(() => {
                            $wire.close();
                        }, 2000); // Close after showing completion for 2 seconds
                    }
                }, 1000); // Update every 1 second
            }
        });
    "
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm"
    style="display: none;"
>
    <div
        class="fi-modal-window mx-auto w-full rounded-xl bg-white shadow-xl ring-1 ring-gray-950/5 max-w-{{ $width }} dark:bg-gray-900 dark:ring-white/10"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
    >
        <div class="fi-modal-content">
            <!-- Header -->
            <div class="fi-modal-header flex items-center gap-x-3 px-6 py-4 border-b border-gray-200 dark:border-white/10">
                @if($icon)
                    <div class="fi-modal-icon-wrapper flex h-10 w-10 items-center justify-center rounded-full {{ \Maystro\FilamentPopupModal\Enums\Colors::tryFrom($color)?->getIconBackgroundClass() ?? 'bg-gray-50 dark:bg-gray-500/10' }}">
                        <x-dynamic-component
                            :component="$icon"
                            class="fi-modal-icon {{ $iconSize === 'sm' ? 'h-4 w-4' : ($iconSize === 'lg' ? 'h-6 w-6' : 'h-5 w-5') }} {{ \Maystro\FilamentPopupModal\Enums\Colors::tryFrom($color)?->getIconTextClass() ?? 'text-gray-600 dark:text-gray-400' }}"
                        />
                    </div>
                @endif
                <div class="grid flex-1 gap-y-1">
                    <h2 class="fi-modal-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        {{ $heading }}
                    </h2>
                </div>
                <x-filament::icon-button
                    icon="heroicon-m-x-mark"
                    icon-alias="modal.close-button"
                    icon-size="lg"
                    :label="$closeLabel"
                    size="sm"
                    color="gray"
                    x-on:click="show = false; $wire.close()"
                />
            </div>

            <!-- Body -->
            <div class="fi-modal-body px-6 py-4">
                <div class="fi-modal-description text-sm text-gray-500 dark:text-gray-400">
                    {!! $description !!}
                </div>

                <!-- Progress Bar -->
                <div 
                    class="mt-4"
                    x-show="showProgress"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                >
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Progress</span>
                        <span class="text-gray-500 dark:text-gray-400 font-mono">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700 shadow-inner">
                        <div
                            class="h-3 rounded-full transition-all duration-500 ease-out {{ \Maystro\FilamentPopupModal\Enums\Colors::tryFrom($color)?->getProgressBarClass() ?? 'bg-gray-600' }} shadow-sm"
                            style="width: {{ $progress }}%"
                            x-bind:style="`width: ${progress}%`"
                        >
                            <!-- Progress bar shine effect -->
                            <div class="h-full w-full rounded-full bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
                        </div>
                    </div>
                    <!-- Progress description -->
                    @if($progress > 0 && $progress < 100)
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">
                            Processing...
                        </div>
                    @elseif($progress >= 100)
                        <div class="text-xs text-green-600 dark:text-green-400 mt-1 text-center font-medium">
                            Completed!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="fi-modal-footer px-6 py-4 border-t border-gray-200 dark:border-white/10">
                <div class="fi-modal-footer-actions flex flex-wrap items-center gap-3 justify-end">
                    @if($hasConfirm)
                        <x-filament::button
                            :color="$color"
                            x-on:click="$wire.confirm()"
                        >
                            {{ $confirmLabel }}
                        </x-filament::button>
                    @endif
                    <x-filament::button
                        color="gray"
                        x-on:click="show = false; $wire.close()"
                    >
                        {{ $closeLabel }}
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</div>
