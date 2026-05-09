# Contributing to Resume Manager

We welcome contributions to this project! Whether it's a bug report, feature request, or a code contribution, your help is appreciated.

## How to Contribute

### 1. Reporting Bugs

If you find a bug, please check if it's already reported. If not, open a new issue on GitHub with a clear description of the bug, steps to reproduce it, and your environment details (OS, PHP version, Laravel version, etc.).

### 2. Suggesting Features

Have an idea for a new feature? Please open an issue to discuss it before submitting a pull request. This helps ensure your idea aligns with the project's goals.

### 3. Submitting Code Changes

#### Development Setup
For detailed setup instructions, please refer to the [Installation section in the README](README.md#installation).

#### Coding Standards
We adhere to strict coding standards to maintain code quality and consistency:
- **PHP**: Follows PSR standards and is enforced by [Laravel Pint](https://github.com/laravel/pint).
- **Static Analysis**: [PHPStan](https://phpstan.org/) is used at Level 9 to catch potential errors.
- **Testing**: All new code should be accompanied by Pest tests. Refer to the [Development Workflow section in the README](README.md#development-workflow) for testing commands.

#### Pull Request Process
1.  **Fork the repository** and create a new branch for your feature or bug fix.
    ```bash
    git checkout -b feat/your-feature-name
    ```
    or
    ```bash
    git checkout -b fix/your-bug-fix
    ```
2.  Make your changes and ensure they adhere to the coding standards.
3.  Add tests for your changes.
4.  Ensure all tests pass by running `composer qa`.
5.  Commit your changes with clear and concise messages.
6.  Push your branch to your fork.
7.  Open a Pull Request on the main repository.

We will review your PR and provide feedback.

---
Thank you for contributing!
