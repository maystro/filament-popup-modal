<?php

namespace Maystro\FilamentPopupModal\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class PopupModalHandler extends Component
{
    public $show = false;
    public $id = 'popup-modal';
    public $heading = '';
    public $description = '';
    public $width = 'lg';
    public $color = 'info';
    public $icon = 'heroicon-o-information-circle';
    public $iconSize = 'md';
    public $hasConfirm = false;
    public $timeout = 0;
    public $progress = 0;
    public $showProgress = false;
    public $confirmLabel = 'Confirm';
    public $closeLabel = 'Close';

    protected $listeners = [
        'show-popup-modal' => 'showModal',
        'close-popup-modal' => 'closeModal',
        'update-popup-modal-progress' => 'updateProgress',
    ];

    public function mount()
    {
        // Check for session flash data on mount
        if (session()->has('popup_modal_event')) {
            $eventData = session('popup_modal_event');
            if ($eventData['event'] === 'show-popup-modal') {
                $this->showModal($eventData['data']);
            }
        }
    }

    #[On('show-popup-modal')]
    public function showModal($data)
    {
        $this->id = $data['id'] ?? 'popup-modal';
        $this->heading = $data['heading'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->width = $data['width'] ?? 'lg';
        $this->color = $data['color'] ?? 'info';
        $this->icon = $data['icon'] ?? 'heroicon-o-information-circle';
        $this->iconSize = $data['icon-size'] ?? 'md';
        $this->hasConfirm = $data['has-confirm'] ?? false;
        $this->timeout = $data['timeout'] ?? 0;
        $this->progress = $data['progress'] ?? 0;
        $this->showProgress = $data['show-progress'] ?? false;
        $this->confirmLabel = $data['confirm-label'] ?? config('popup-modal.default_confirm_label', 'Confirm');
        $this->closeLabel = $data['close-label'] ?? config('popup-modal.default_close_label', 'Close');
        $this->show = true;

        // Auto-close if timeout is set
        if ($this->timeout > 0) {
            $this->dispatch('auto-close-modal', timeout: $this->timeout);
        }
    }

    #[On('close-popup-modal')]
    public function closeModal($data = null)
    {
        $targetId = is_array($data) ? ($data['id'] ?? null) : $data;
        if (!$targetId || $targetId === $this->id) {
            $this->show = false;
        }
    }

    public function close()
    {
        // Execute close callback if exists
        $this->executeCallback('close');
        $this->show = false;
    }

    public function confirm()
    {
        // Execute confirm callback if exists
        $this->executeCallback('confirm');
        $this->show = false;
        $this->dispatch('popup-modal-confirmed', id: $this->id);
    }

    protected function executeCallback(string $type)
    {
        $callbackData = session("popup_modal_callbacks_{$this->id}");
        if ($callbackData && isset($callbackData[$type])) {
            try {
                $callback = unserialize($callbackData[$type]);
                if ($callback instanceof \Closure) {
                    $callback();
                }
            } catch (\Exception $e) {
                // Log error but don't break the modal functionality
                logger()->error("PopupModal callback error: " . $e->getMessage());
            }
        }

        // Clean up callback data
        session()->forget("popup_modal_callbacks_{$this->id}");
    }

    #[On('update-popup-modal-progress')]
    public function updateProgress($data)
    {
        // Only update if this is the target modal
        if (isset($data['id']) && $data['id'] === $this->id) {
            $this->progress = max(0, min(100, $data['progress'] ?? 0));
        }
    }

    public function render()
    {
        return view('popup-modal::popup-modal-handler');
    }
}
