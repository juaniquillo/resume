# Themes & Styling Guide

Resume Manager uses a flexible presenter and Tailwind theme system (`juaniquillo/laravel-backend-component`) to render dynamic component trees.

## Available Themes (`App\Enums\ResumeTheme`)
- **Retro-Modern (`default`)**: Clean modern design utilizing Space Mono typography and balanced spacing.
- **Elegant Serif (`elegant`)**: Sophisticated serif styling for a refined traditional resume look.
- **Blank Theme (`blank`)**: Minimalist unstyled template for custom styling.
- **Modern & Bold (`bold`)**: High-contrast, striking layout with prominent headers and visual separation.
- **PDF Optimized (`pdf`)**: Specially structured for clean page breaks and PDF rendering output.
- **Terminal Console (`terminal`)**: Developer-focused monospaced dark terminal aesthetic.
- **Professional Layout (`professional`)**: Structured corporate layout suitable for standard applications.
- **GitHub Markdown (`github`)**: Developer-centric markdown aesthetic with clean borders, cards, and badges.

## Customization
Themes are located in `resources/views/_themes/tailwind/resume/`. Each theme implements the `PresenterTheme` contract and defines structured container themes and layouts.
