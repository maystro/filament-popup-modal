بسم الله والحمد لله والصلاة والصلام على رسول الله وعلى آله وصحبه
# Filament PopupModal

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maystro/filament-popup-modal.svg?style=flat-square)](https://packagist.org/packages/maystro/filament-popup-modal)
[![Total Downloads](https://img.shields.io/packagist/dt/maystro/filament-popup-modal.svg?style=flat-square)](https://packagist.org/packages/maystro/filament-popup-modal)

A comprehensive modal dialog system for FilamentPHP with real-time progress bars, callbacks, and full theme integration.

## Features

- 🎨 **Full Filament Theme Integration** - Uses native Filament components and colors
- 📊 **Real-time Progress Bars** - Smooth animations with theme-aware styling
- 🔔 **Callback Support** - Execute functions on confirm/close actions
- 🖼️ **Icon Customization** - Adjustable icon sizes (sm, md, lg) with auto-icons
- 🌙 **Dark Mode Support** - Automatically adapts to Filament's theme
- 📱 **Responsive Design** - Works perfectly on all screen sizes
- ⚡ **Performance Optimized** - Lightweight Livewire-based implementation
- 🎯 **Fluent API** - Intuitive method chaining for easy usage

## Installation

You can install the package via composer:

```bash
composer require maystro/filament-popup-modal
```

The package will automatically register itself via Laravel's package discovery.

Optionally, you can publish the config file:

```bash
php artisan vendor:publish --tag="popup-modal-config"
```

Optionally, you can publish the views:

```bash
php artisan vendor:publish --tag="popup-modal-views"
```

## Usage

### Basic Modal

```php
use Maystro\FilamentPopupModal\PopupModal;
use Maystro\FilamentPopupModal\Enums\Colors;

PopupModal::make()
    ->title('Success!')
    ->body('Operation completed successfully.')
    ->color(Colors::Success)
    ->show();
```

### Progress Modal

```php
// Create progress modal
$modal = PopupModal::progressModal(
    'Processing Data',
    'Please wait while we process your data...',
    Colors::Primary
);

$modal->show();

// Update progress
$modal->updateProgress(50);
```

### Modal with Callbacks

```php
PopupModal::make()
    ->title('Confirm Action')
    ->body('Are you sure you want to proceed?')
    ->color(Colors::Warning)
    ->confirm(true)
    ->onConfirm(function () {
        // Your action here
        Notification::make()->title('Confirmed!')->success()->send();
    })
    ->show();
```

### Icon Sizing

```php
PopupModal::make()
    ->title('Important Notice')
    ->body('This is an important message.')
    ->icon('heroicon-o-exclamation-triangle')
    ->iconSize('lg')  // sm, md, lg
    ->color(Colors::Warning)
    ->show();
```

### Using Helper Functions

```php
// Quick popup
show_popup('Hello', 'This is a quick popup!', Colors::Info);

// Progress popup
$modal = show_progress_popup('Processing...', 'Please wait...');
update_popup_progress($modal->getData()['id'], 75);
```

### Using Facade

Add to your `config/app.php`:

```php
'aliases' => [
    // ... other aliases
    'PopupModal' => Maystro\FilamentPopupModal\Facades\PopupModal::class,
],
```

Then use:

```php
PopupModal::make()->title('Hello')->show();
```

## API Reference

### Methods

| Method | Description | Parameters |
|--------|-------------|------------|
| `make(string $id = null)` | Create new modal instance | Optional unique ID |
| `title(string $title)` | Set modal title | Title text |
| `body(string $body)` | Set modal body content | Body HTML/text |
| `color(Colors\|string $color)` | Set theme color | Color enum or string |
| `icon(string $icon)` | Set custom icon | Heroicon name |
| `iconSize(string $size)` | Set icon size | 'sm', 'md', 'lg' |
| `width(string $width)` | Set modal width | 'xs' to '7xl' |
| `confirm(bool $hasConfirm)` | Show confirm button | Boolean |
| `confirmLabel(string $label)` | Customize confirm button | Button text |
| `closeLabel(string $label)` | Customize close button | Button text |
| `onConfirm(callable $callback)` | Set confirm callback | Closure function |
| `onClose(callable $callback)` | Set close callback | Closure function |
| `progress(int $percent)` | Set progress percentage | 0-100 |
| `showProgress(int $percent)` | Show progress bar | Initial percentage |
| `hideProgress()` | Hide progress bar | - |
| `updateProgress(int $percent)` | Update progress real-time | New percentage |
| `timeout(int $ms)` | Auto-close timeout | Milliseconds |
| `show()` | Display the modal | - |
| `close()` | Close the modal | - |

### Static Methods

```php
PopupModal::progressModal($title, $body, $color)  // Create progress modal
PopupModal::updateProgressById($id, $percent)     // Update any modal's progress
```

### Helper Functions

```php
popup_modal($id)                                  // Create instance
show_popup($title, $body, $color, $icon)        // Quick popup
show_progress_popup($title, $body, $color)      // Progress popup
update_popup_progress($modalId, $percent)        // Update progress
```

## Color System

The package uses Filament's standardized color palette:

```php
Colors::Primary    // Main brand color
Colors::Secondary  // Secondary accent
Colors::Success    // Green success states
Colors::Warning    // Orange warning states  
Colors::Danger     // Red error states
Colors::Info       // Blue informational
Colors::Gray       // Neutral gray
```

## Configuration

The config file allows you to customize default values:

```php
return [
    'default_confirm_label' => 'Confirm',
    'default_close_label' => 'Close',
    
    'progress' => [
        'default_color' => 'primary',
        'update_interval' => 500,
        'completion_delay' => 1500,
    ],
    
    'modal' => [
        'default_width' => 'lg',
        'default_color' => 'info',
        'default_icon_size' => 'md',
        'auto_register' => true,
    ],
    
    'icons' => [
        'auto_assign' => true,
        'fallback_icon' => 'heroicon-o-information-circle',
    ],
];
```

## Requirements

- Laravel 12.x
- FilamentPHP 3.x
- Livewire 3.x
- PHP 8.1+

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Maystro](https://github.com/maystro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
