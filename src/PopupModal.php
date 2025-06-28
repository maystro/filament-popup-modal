<?php

namespace Maystro\FilamentPopupModal;

use Livewire\Livewire;
use Maystro\FilamentPopupModal\Enums\Colors;

class PopupModal
{
    protected array $data = [];
    protected ?\Closure $confirmCallback = null;
    protected ?\Closure $closeCallback = null;

    public static function make(?string $id = null): static
    {
        $instance = new static();
        $instance->data['id'] = $id ?? 'popup-modal-' . uniqid();
        return $instance;
    }

    public function title(string $title): static
    {
        $this->data['heading'] = $title;
        return $this;
    }

    public function body(string $body): static
    {
        $this->data['description'] = $body;
        return $this;
    }

    public function width(string $width): static
    {
        // Ensure we use valid Tailwind width classes
        $validWidths = ['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

        // Default to 'lg' if width is not valid
        if (!in_array($width, $validWidths)) {
            $width = 'lg';
        }

        $this->data['width'] = $width;
        return $this;
    }

    public function color(string|Colors $color): static
    {
        // Handle enum input
        if ($color instanceof Colors) {
            $this->data['color'] = $color->value;

            // Auto-set icon if not already set
            if (!isset($this->data['icon'])) {
                $this->data['icon'] = $color->getDefaultIcon();
            }

            return $this;
        }

        // Handle string input with validation
        $validColors = Colors::all();

        // Map common color aliases to Filament colors
        $colorMap = [
            'error' => 'danger',
            'red' => 'danger',
            'green' => 'success',
            'blue' => 'info',
            'yellow' => 'warning',
            'orange' => 'warning',
        ];

        $color = $colorMap[$color] ?? $color;

        // Default to 'info' if color is not valid
        if (!in_array($color, $validColors)) {
            $color = 'info';
        }

        $this->data['color'] = $color;
        return $this;
    }

    public function icon(string $icon): static
    {
        $this->data['icon'] = $icon;
        return $this;
    }

    public function iconSize(string $size): static
    {
        // Validate icon size
        $validSizes = ['sm', 'md', 'lg'];

        if (!in_array($size, $validSizes)) {
            $size = 'md'; // Default to medium if invalid
        }

        $this->data['icon-size'] = $size;
        return $this;
    }

    public function confirm(bool $hasConfirm = true): static
    {
        $this->data['has-confirm'] = $hasConfirm;
        return $this;
    }

    public function confirmLabel(string $label): static
    {
        $this->data['confirm-label'] = $label;
        return $this;
    }

    public function closeLabel(string $label): static
    {
        $this->data['close-label'] = $label;
        return $this;
    }

    public function confirmCallback(callable $callback): static
    {
        $this->confirmCallback = $callback instanceof \Closure ? $callback : \Closure::fromCallable($callback);
        return $this;
    }

    public function closeCallback(callable $callback): static
    {
        $this->closeCallback = $callback instanceof \Closure ? $callback : \Closure::fromCallable($callback);
        return $this;
    }

    public function onConfirm(callable $callback): static
    {
        return $this->confirmCallback($callback);
    }

    public function onClose(callable $callback): static
    {
        return $this->closeCallback($callback);
    }

    public function timeout(int $milliseconds): static
    {
        $this->data['timeout'] = $milliseconds;
        return $this;
    }

    public function progress(int $percent): static
    {
        // Validate progress percentage
        $percent = max(0, min(100, $percent));
        $this->data['progress'] = $percent;

        // Automatically show progress bar when setting progress
        $this->data['show-progress'] = true;

        return $this;
    }

    public function updateProgress(int $percent): void
    {
        // Validate progress percentage
        $percent = max(0, min(100, $percent));

        // Update progress in real-time
        $this->dispatchBrowserEvent('update-popup-modal-progress', [
            'id' => $this->data['id'],
            'progress' => $percent
        ]);
    }

    /**
     * Update progress for a specific modal instance
     */
    public static function updateProgressById(string $modalId, int $percent): void
    {
        // Validate progress percentage
        $percent = max(0, min(100, $percent));

        // Create a temporary instance to dispatch the event
        $instance = new static();
        $instance->data['id'] = $modalId;
        $instance->dispatchBrowserEvent('update-popup-modal-progress', [
            'id' => $modalId,
            'progress' => $percent
        ]);
    }

    public function hideProgress(): static
    {
        $this->data['show-progress'] = false;
        $this->data['progress'] = 0;
        return $this;
    }

    public function showProgress(int $percent = 0): static
    {
        $this->data['show-progress'] = true;
        $this->progress($percent);
        return $this;
    }

    /**
     * Create a modal specifically for showing progress
     */
    public static function progressModal(string $title, string $body = '', string|Colors $color = 'primary'): static
    {
        return static::make()
            ->title($title)
            ->body($body)
            ->color($color)
            ->showProgress(0)
            ->width('lg');
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function show(): void
    {
        // Store callbacks in session if they exist
        if ($this->confirmCallback || $this->closeCallback) {
            $callbackData = [];
            if ($this->confirmCallback) {
                $callbackData['confirm'] = serialize($this->confirmCallback);
            }
            if ($this->closeCallback) {
                $callbackData['close'] = serialize($this->closeCallback);
            }
            session()->put("popup_modal_callbacks_{$this->data['id']}", $callbackData);
        }

        // Dispatch browser event to show the modal
        $this->dispatchBrowserEvent('show-popup-modal', $this->data);
    }

    public function close(): void
    {
        $this->dispatchBrowserEvent('close-popup-modal', ['id' => $this->data['id'] ?? 'popup-modal']);
    }

    protected function dispatchBrowserEvent(string $event, array $data): void
    {
        // Check if we're in a Livewire component context
        if (app()->bound('livewire') && Livewire::isLivewireRequest()) {
            // We're in a Livewire request, use the current component
            $component = Livewire::current();
            if ($component) {
                $component->dispatch($event, $data);
                return;
            }
        }

        // Fallback: Use session flash to pass data to the frontend
        session()->flash('popup_modal_event', [
            'event' => $event,
            'data' => $data
        ]);
    }
}
