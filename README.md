# Resume Manager

[![Lint](https://github.com/juaniquillo/resume/actions/workflows/lint.yml/badge.svg)](https://github.com/juaniquillo/resume/actions/workflows/lint.yml) 
[![PHPStan](https://github.com/juaniquillo/resume/actions/workflows/phpstan.yml/badge.svg)](https://github.com/juaniquillo/resume/actions/workflows/phpstan.yml) 
[![Tests](https://github.com/juaniquillo/resume/actions/workflows/tests.yml/badge.svg?event=pull_request)](https://github.com/juaniquillo/resume/actions/workflows/tests.yml)

A professional resume management system designed to help you take full control of your career narrative. Built with care for those who want to showcase their professional journey on their own terms.

## ✨ Core Features

*   **Comprehensive Career Tracking**: Manage every aspect of your professional profile, including work history, education, volunteer work, and certifications.
*   **Detailed Accomplishments**: Add "Highlights" to every experience—because your achievements matter more than just your titles.
*   **Flexible Skills & Languages**: Easily track your skill levels and multilingual capabilities with a streamlined, intuitive interface.
* **Modular Customization**: Organize your resume sections with drag-and-drop ordering and tailor the visibility of specific details to fit each application.
* **Open Graph Management**: Effortlessly manage and customize the social preview images for your professional profile to ensure you make a great impression when sharing your link.
* **JSON Resume Compatibility**: Your data is structured to align with the [JSON Resume](https://jsonresume.org/) standard, ensuring maximum portability and compatibility.

## 💡 About this Project

This is **not a commercial product**. It's a personal tool I built to manage and showcase my own professional journey after recently losing my job. I decided to open-source it to share the architectural patterns (CrudAssistant, Backend Components) and to help anyone else in a similar situation.

If you'd like to try it out or have any suggestions, I'd love to hear from you!
🐦 **[@juaniquillo](https://x.com/juaniquillo)**

---

## 🛠️ For Developers

Interested in the tech behind the scenes? This project is built using modern PHP and the latest Laravel ecosystem tools.

### 🚀 Tech Stack
- **PHP 8.4**
- **Laravel 13**
- **Livewire 4**
- **Flux UI**
- **Tailwind CSS 4**
- **Laravel Fortify** (authentication backend)
- **Pest 4** (testing framework)

### ⚙️ Installation
1. **Clone the repository:**
   ```bash
   git clone https://github.com/juaniquillo/resume.git
   cd resume
   ```
2. **Run the setup command:**
   ```bash
   composer run setup
   ```
   *(Installs dependencies, generates keys, runs migrations, and builds assets)*.
3. **Configure the environment:**
   Ensure your `.env` file is set up for your local database.

### 🧪 Development Workflow
We maintain high code quality through a strict QA workflow.

- **QA Suite**: `composer qa` (runs Lint, PHPStan, and Pest).
- **Linting**: `composer lint`
- **Analysis**: `composer phpstan`
- **Testing**: `php artisan test --compact`

## 🤝 Contributing
We love contributions! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.
