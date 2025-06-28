<?php

namespace Maystro\FilamentPopupModal\Enums;

enum Colors: string
{
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Success = 'success';
    case Warning = 'warning';
    case Danger = 'danger';
    case Info = 'info';
    case Gray = 'gray';

    /**
     * Get all available color values
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get default icon for this color
     */
    public function getDefaultIcon(): string
    {
        return match($this) {
            self::Primary => 'heroicon-o-sparkles',
            self::Secondary => 'heroicon-o-squares-2x2',
            self::Success => 'heroicon-o-check-circle',
            self::Warning => 'heroicon-o-exclamation-triangle',
            self::Danger => 'heroicon-o-x-circle',
            self::Info => 'heroicon-o-information-circle',
            self::Gray => 'heroicon-o-ellipsis-horizontal-circle',
        };
    }

    /**
     * Get icon background class for Filament theme
     */
    public function getIconBackgroundClass(): string
    {
        return match($this) {
            self::Primary => 'bg-primary-50 dark:bg-primary-500/10',
            self::Secondary => 'bg-gray-50 dark:bg-gray-500/10',
            self::Success => 'bg-success-50 dark:bg-success-500/10',
            self::Warning => 'bg-warning-50 dark:bg-warning-500/10',
            self::Danger => 'bg-danger-50 dark:bg-danger-500/10',
            self::Info => 'bg-info-50 dark:bg-info-500/10',
            self::Gray => 'bg-gray-50 dark:bg-gray-500/10',
        };
    }

    /**
     * Get icon text color class for Filament theme
     */
    public function getIconTextClass(): string
    {
        return match($this) {
            self::Primary => 'text-primary-600 dark:text-primary-400',
            self::Secondary => 'text-gray-600 dark:text-gray-400',
            self::Success => 'text-success-600 dark:text-success-400',
            self::Warning => 'text-warning-600 dark:text-warning-400',
            self::Danger => 'text-danger-600 dark:text-danger-400',
            self::Info => 'text-info-600 dark:text-info-400',
            self::Gray => 'text-gray-600 dark:text-gray-400',
        };
    }

    /**
     * Get progress bar color class for Filament theme
     */
    public function getProgressBarClass(): string
    {
        return match($this) {
            self::Primary => 'bg-primary-600',
            self::Secondary => 'bg-gray-600',
            self::Success => 'bg-success-600',
            self::Warning => 'bg-warning-600',
            self::Danger => 'bg-danger-600',
            self::Info => 'bg-info-600',
            self::Gray => 'bg-gray-600',
        };
    }
}
