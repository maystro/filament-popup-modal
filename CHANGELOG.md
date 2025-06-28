# Changelog

All notable changes to `filament-popup-modal` will be documented in this file.

## [1.0.0] - 2025-06-28

### Added
- Initial release of FilamentPHP PopupModal package
- Fluent API for creating modal dialogs with title, body, color, and icon
- Real-time progress bar functionality with smooth animations
- Support for callback functions on confirm and close actions
- Icon size customization (sm, md, lg)
- Auto-timeout functionality
- Full Filament theme integration with dark mode support
- Responsive design for all screen sizes
- Livewire-based implementation for optimal performance
- Comprehensive documentation and examples
- PHPUnit test suite
- Laravel package auto-discovery support

### Features
- **Color Support**: Primary, secondary, success, warning, danger, info colors
- **Icon Integration**: Auto-icons based on color selection
- **Progress Tracking**: Real-time progress updates with `updateProgressById()`
- **Responsive Widths**: xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
- **Helper Functions**: Global `popup_modal()` and `update_popup_progress()` functions
- **Facade Support**: `PopupModal::make()` facade for easy access
- **Customizable Labels**: Configurable confirm/close button labels
- **Session Callbacks**: Persistent callback execution across requests

### Requirements
- PHP ^8.1
- Laravel ^10.0|^11.0
- FilamentPHP ^3.0
- Livewire ^3.0
