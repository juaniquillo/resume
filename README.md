# Resume Manager

[![Lint](https://github.com/juaniquillo/resume/actions/workflows/lint.yml/badge.svg)](https://github.com/juaniquillo/resume/actions/workflows/lint.yml) 
[![PHPStan](https://github.com/juaniquillo/resume/actions/workflows/phpstan.yml/badge.svg)](https://github.com/juaniquillo/resume/actions/workflows/phpstan.yml) 
[![Tests](https://github.com/juaniquillo/resume/actions/workflows/tests.yml/badge.svg?event=pull_request)](https://github.com/juaniquillo/resume/actions/workflows/tests.yml)

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
The application leverages the [CrudAssistant](https://github.com/juaniquillo/crud-assistant) package to provide a highly granular and type-safe approach to CRUD operations. Each resume section (Works, Education, Skills, etc.) is defined by a **Schema** that separates concerns:
- **Input Factories**: Define validation, form attributes (Flux components), and factory recipes for each field.
- **Value Managers**: Handle complex data transformations (e.g., JSON fields to comma-separated strings).
- **Table Presenters**: Customize how data is rendered in dashboard tables using Flux components.

### Core Sections
- **Basics & Contact**: Name, label, avatar, and multi-network profile management.
- **Experience & Portfolio**: Detailed work history, volunteering, and comprehensive project tracking.
- **Academic & Achievements**: Full tracking of education, courses, awards, and certifications.
- **Skills & Languages**: Granular level tracking and keyword management.
- **Customization**: **Drag-and-drop section ordering** and **Modular Themes** (Default, Bold, Elegant).

### Key Implementation Details
- **Resume Schema**: Full alignment with the JSON Resume standard for maximum compatibility.
- **Strict Typing**: Modern PHP 8.4 features and static analysis (PHPStan Level 5).
- **Responsive Dashboard**: A polished administrative experience using Livewire 4 and Flux UI.

## 💡 About this Project

This is **not a commercial product**. It's a personal tool I built to manage and showcase my own professional journey after recently losing my job. I decided to open-source it to share the architectural patterns (CrudAssistant, Backend Components) and to help anyone else in a similar situation.

If you'd like to try it out or have any suggestions, I'd love to hear from you!
🐦 **[@juaniquillo](https://x.com/juaniquillo)**

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
- **PHPStan**: Static analysis (Level 5 for now).
- **Pest**: Parallel feature and unit testing.

### Individual Commands
- **Linting**: `composer lint`
- **Analysis**: `composer phpstan`
- **Testing**: `php artisan test --compact`

## 🤝 Contributing

We love contributions! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests.
