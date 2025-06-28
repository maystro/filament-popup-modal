<?php

namespace Maystro\FilamentPopupModal;

use Illuminate\Support\ServiceProvider;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class PopupModalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/popup-modal.php',
            'popup-modal'
        );

        // Register the main service
        $this->app->singleton(PopupModal::class);
    }

    public function boot(): void
    {
        // Register Livewire component only if Livewire is available
        if (class_exists('\Livewire\Livewire')) {
            Livewire::component('popup-modal-handler', \Maystro\FilamentPopupModal\Livewire\PopupModalHandler::class);
        }

        // Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'popup-modal');

        // Register PopupModal in Filament layout only if Filament is available
        if (class_exists('\Filament\Support\Facades\FilamentView')) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Blade::render('<livewire:popup-modal-handler />')
            );
        }

        // Publish assets
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/popup-modal.php' => config_path('popup-modal.php'),
            ], 'popup-modal-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/popup-modal'),
            ], 'popup-modal-views');
        }
    }
}
