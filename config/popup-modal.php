<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Labels
    |--------------------------------------------------------------------------
    |
    | These are the default labels used for modal buttons. You can change
    | these to match your application's language or preferences.
    |
    */

    'default_confirm_label' => 'Confirm',
    'default_close_label' => 'Close',

    /*
    |--------------------------------------------------------------------------
    | Progress Bar Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for progress bar behavior and appearance.
    |
    */

    'progress' => [
        'default_color' => 'primary',
        'update_interval' => 500, // milliseconds
        'completion_delay' => 1500, // milliseconds to show 100% before auto-close
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for modal appearance and behavior.
    |
    */

    'modal' => [
        'default_width' => 'lg',
        'default_color' => 'info',
        'default_icon_size' => 'md',
        'auto_register' => true, // Auto-register in Filament panels
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for default icons and icon behavior.
    |
    */

    'icons' => [
        'auto_assign' => true, // Auto-assign icons based on color
        'fallback_icon' => 'heroicon-o-information-circle',
    ],
];
