<?php

if (!function_exists('popup_modal')) {
    /**
     * Create a new PopupModal instance
     *
     * @param string|null $id
     * @return \Maystro\FilamentPopupModal\PopupModal
     */
    function popup_modal(?string $id = null): \Maystro\FilamentPopupModal\PopupModal
    {
        return \Maystro\FilamentPopupModal\PopupModal::make($id);
    }
}

if (!function_exists('show_popup')) {
    /**
     * Quickly show a simple popup modal
     *
     * @param string $title
     * @param string $body
     * @param string|\Maystro\FilamentPopupModal\Enums\Colors $color
     * @param string|null $icon
     * @return void
     */
    function show_popup(
        string $title,
        string $body,
        string|\Maystro\FilamentPopupModal\Enums\Colors $color = 'info',
        ?string $icon = null
    ): void {
        $popupModal = \Maystro\FilamentPopupModal\PopupModal::make()
            ->title($title)
            ->body($body)
            ->color($color);

        // Set icon if not provided and color is enum
        if ($icon) {
            $popupModal->icon($icon);
        } elseif ($color instanceof \Maystro\FilamentPopupModal\Enums\Colors) {
            $popupModal->icon($color->getDefaultIcon());
        } else {
            // Fallback icon mapping for string colors
            $popupModal->icon(match($color) {
                'success' => 'heroicon-m-check-circle',
                'warning' => 'heroicon-m-exclamation-triangle',
                'danger' => 'heroicon-m-x-circle',
                'primary' => 'heroicon-m-sparkles',
                'secondary' => 'heroicon-m-squares-2x2',
                'gray' => 'heroicon-m-ellipsis-horizontal-circle',
                default => 'heroicon-m-information-circle',
            });
        }

        $popupModal->show();
    }
}

if (!function_exists('update_popup_progress')) {
    /**
     * Update progress for a specific modal instance
     *
     * @param string $modalId
     * @param int $percent
     * @return void
     */
    function update_popup_progress(string $modalId, int $percent): void
    {
        \Maystro\FilamentPopupModal\PopupModal::updateProgressById($modalId, $percent);
    }
}

if (!function_exists('show_progress_popup')) {
    /**
     * Quickly show a progress modal
     *
     * @param string $title
     * @param string $body
     * @param string|\Maystro\FilamentPopupModal\Enums\Colors $color
     * @return \Maystro\FilamentPopupModal\PopupModal
     */
    function show_progress_popup(
        string $title,
        string $body = '',
        string|\Maystro\FilamentPopupModal\Enums\Colors $color = 'primary'
    ): \Maystro\FilamentPopupModal\PopupModal {
        $modal = \Maystro\FilamentPopupModal\PopupModal::progressModal($title, $body, $color);
        $modal->show();
        return $modal;
    }
}
