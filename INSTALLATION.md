# 🚀 Filament PopupModal - Composer Package Installation Guide

This guide shows you how to install and use the PopupModal package in any Laravel/Filament project.

## 📦 Package Installation

### Method 1: From Packagist (Recommended)

Once published to Packagist, you can install via:

```bash
composer require maystro/filament-popup-modal
```

### Method 2: From Local Directory

For development or testing:

```bash
# Add the local repository to your project's composer.json
composer config repositories.popup-modal path /path/to/popup-modal-package

# Install the package
composer require maystro/filament-popup-modal:dev-main
```

### Method 3: From Git Repository

```bash
# Add git repository to composer.json
composer config repositories.popup-modal vcs https://github.com/maystro/filament-popup-modal

# Install the package
composer require maystro/filament-popup-modal:dev-main
```

## 🔧 Configuration

The package automatically registers itself via Laravel's package discovery. No manual registration needed!

### Optional: Publish Configuration

```bash
php artisan vendor:publish --tag=popup-modal-config
```

This creates `config/popup-modal.php`:

```php
<?php

return [
    'default_confirm_label' => 'Confirm',
    'default_close_label' => 'Close',
    'default_width' => 'lg',
    'default_color' => 'primary',
];
```

### Optional: Publish Views

```bash
php artisan vendor:publish --tag=popup-modal-views
```

This allows you to customize the modal appearance in `resources/views/vendor/popup-modal/`.

## 🎯 Basic Usage

### Quick Start

```php
use Maystro\FilamentPopupModal\Facades\PopupModal;

// Simple modal
PopupModal::make()
    ->title('Success!')
    ->body('Your action was completed successfully.')
    ->color('success')
    ->show();

// Using helper function
popup_modal()
    ->title('Warning')
    ->body('Are you sure you want to continue?')
    ->color('warning')
    ->confirm()
    ->show();
```

### In Filament Resources

```php
<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Maystro\FilamentPopupModal\Facades\PopupModal;

class UserResource extends Resource
{
    // In your action
    Action::make('approve')
        ->action(function () {
            // Your logic here
            
            PopupModal::make()
                ->title('User Approved!')
                ->body('The user has been successfully approved.')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->timeout(3000)
                ->show();
        })
}
```

### Progress Modal Example

```php
// Create progress modal
$progressModal = PopupModal::progressModal(
    'Processing Data',
    'Please wait while we process your request...',
    'info'
)->show();

// Update progress (in a job or long-running process)
for ($i = 0; $i <= 100; $i += 10) {
    update_popup_progress($progressModal->getData()['id'], $i);
    sleep(1); // Simulate work
}
```

### With Callbacks

```php
PopupModal::make()
    ->title('Delete User')
    ->body('This action cannot be undone. Are you sure?')
    ->color('danger')
    ->confirm()
    ->onConfirm(function () {
        // Delete logic here
        User::find(1)->delete();
        
        // Show success modal
        popup_modal()
            ->title('Deleted!')
            ->body('User has been deleted successfully.')
            ->color('success')
            ->show();
    })
    ->onClose(function () {
        // Cancelled action
        popup_modal()
            ->title('Cancelled')
            ->body('Delete operation was cancelled.')
            ->color('info')
            ->show();
    })
    ->show();
```

## 🎨 Full API Reference

### Modal Configuration

```php
PopupModal::make()
    ->title('Modal Title')                    // Set the title
    ->body('Modal content')                   // Set the body text
    ->color('primary|secondary|success|warning|danger|info') // Set color theme
    ->icon('heroicon-o-information-circle')   // Set custom icon
    ->iconSize('sm|md|lg')                    // Set icon size
    ->width('xs|sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl') // Set modal width
    ->confirm(true)                           // Show confirm button
    ->confirmLabel('Yes, Delete')             // Custom confirm button text
    ->closeLabel('Cancel')                    // Custom close button text
    ->timeout(5000)                           // Auto-close after milliseconds
    ->progress(50)                            // Show progress bar at 50%
    ->showProgress(0)                         // Show progress bar starting at 0%
    ->hideProgress()                          // Hide progress bar
    ->onConfirm(fn() => /* logic */)          // Confirm callback
    ->onClose(fn() => /* logic */)            // Close callback
    ->show();                                 // Display the modal
```

### Helper Functions

```php
// Create modal instance
popup_modal()->title('Hello')->show();

// Update progress by modal ID
update_popup_progress('modal-id', 75);

// Create progress modal
$modal = popup_progress_modal('Loading...', 'Please wait...');
```

### Facade Methods

```php
use Maystro\FilamentPopupModal\Facades\PopupModal;

// Create instance
PopupModal::make();

// Create progress modal
PopupModal::progressModal('Title', 'Body', 'color');

// Update progress by ID
PopupModal::updateProgressById('modal-id', 80);
```

## 🧪 Testing

The package includes comprehensive tests. Run them with:

```bash
cd vendor/maystro/filament-popup-modal
composer test
```

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## 🆘 Support

If you discover any security vulnerabilities, please send an e-mail to security@example.com.

For bugs and feature requests, please use the [GitHub issues page](https://github.com/maystro/filament-popup-modal/issues).

---

**Happy coding! 🎉**
