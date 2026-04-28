# Resume Manager

A professional resume management system built with the latest Laravel ecosystem. This application allows users to manage every aspect of their professional profile—from basic contact information to complex work histories and skill sets—following the JSON Resume schema patterns.

## 🚀 Tech Stack

- **PHP 8.4** (utilizing the latest language features)
- **Laravel 13**
- **Livewire 4** (for reactive, high-performance UI)
- **Flux UI (Free Edition)** (for a sleek, modern component system)
- **Tailwind CSS 4**
- **Laravel Fortify** (robust authentication backend)
- **Pest 4** (expressive testing framework)

## 🏗️ Architecture & Features

### CrudAssistant Framework
The application leverages the `CrudAssistant` package to provide a highly granular and type-safe approach to CRUD operations. Each resume section (Works, Education, Skills, etc.) is defined by a **Schema** that separates concerns:
- **Input Factories**: Define validation, form attributes (Flux components), and factory recipes for each field.
- **Value Managers**: Handle complex data transformations (e.g., JSON fields to comma-separated strings).
- **Table Presenters**: Customize how data is rendered in dashboard tables using Flux components.

### Core Sections
- **Basics**: Name, label, image, and contact details.
- **Location**: Single-record management for residential details.
- **Profiles**: Management of social media and professional networks using the `Network` enum.
- **Work & Volunteers**: Detailed history including sub-CRUDs for highlights.
- **Education, Awards, & Certificates**: Full tracking of academic and professional achievements.
- **Skills & Languages**: Granular level tracking and keyword management.
- **Projects, Publications, & References**: Comprehensive portfolio and citation management.

### Key Implementation Details
- **Resume Schema**: Integration with [juststeveking/resume-php](https://github.com/JustSteveKing/resume-php) for standardized resume parsing, validating against the JSON Resume schema, frontend rendering, and export functionality.
- **Uuidable Records**: All entities use UUIDs via a central trait for secure and consistent identification.
- **Strict Typing**: Full use of PHP 8.4 type hints, return types, and static analysis (PHPStan Level 9).
- **Responsive Flux UI**: A polished dashboard experience using Flux components and Tailwind 4.

## 🛠️ Installation

This project is optimized for **Laravel Herd**.

1. **Clone the repository:**
   ```bash
   git clone https://github.com/juaniquillo/resume.git
   cd resume
   ```

2. **Run the setup command:**
   The project includes a comprehensive setup script in `composer.json`:
   ```bash
   composer run setup
   ```
   *This will install dependencies, generate keys, run migrations, and build frontend assets.*

3. **Configure the environment:**
   Ensure your `.env` file points to your local database (SQLite is the default).

## 🧪 Development Workflow

We maintain high code quality through a strict QA workflow.

### Quality Assurance (QA)
Before pushing changes, always run the full QA suite:
```bash
composer qa
```
This command clears the config cache and runs:
- **Laravel Pint**: Code style enforcement.
- **PHPStan**: Static analysis (Level 9).
- **Pest**: Parallel feature and unit testing.

### Individual Commands
- **Linting**: `composer lint`
- **Analysis**: `composer phpstan`
- **Testing**: `php artisan test --compact`

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).
