---
name: resume-theme-development
description: Guide for creating, configuring, and managing resume themes in this application. Covers PresenterTheme interface contracts, theme classes, Tailwind theme view files, container/inner-container structure, font management, ResumeTheme enum registration, and layout constraints (like professional 2-column header vs full-width sections).
---

# Resume Theme Development

## When to Use This Skill

Use this skill when the user needs to:
- Create a new resume theme (e.g., Executive, Minimalist, Creative, Modern).
- Modify an existing theme presenter class or Tailwind theme Blade view.
- Work with resume containers, inner containers (`*InnerContainerThemes()`), and grid layouts.
- Configure typography, Google fonts, or local font assets for a theme.
- Register new themes in the `ResumeTheme` enum and `ThemeFactory`.
- Understand layout constraints (such as multi-column grid headers vs full-width stacked sections).

---

## Anatomy of a Resume Theme

Every resume theme consists of three core components:

1. **Theme Blade File**: Located in `resources/views/_themes/tailwind/resume/<theme-name>.blade.php`. It returns an associative array mapping component and container keys to Tailwind CSS classes.
2. **Theme Presenter Class**: Located in `app/Presenters/Themes/<ThemeName>PresenterTheme.php`. It implements `App\Presenters\Contracts\PresenterTheme`.
3. **Enum Case**: Registered in `App\Enums\ResumeTheme`.

---

## Step-by-Step Guide: Creating a New Theme

### Step 1: Create the Theme Blade File

Create `resources/views/_themes/tailwind/resume/my-theme.blade.php`:

```php
<?php

return [
    'cover-letter-container' => 'prose text-gray-700 max-w-none',

    'container' => 'mx-auto max-w-4xl px-6 py-12 bg-white text-gray-800 shadow-xl',
    
    'basics-container' => 'mb-8 flex flex-col items-center',
    'basics-inner-container' => '',
    'image-container' => 'mb-4',
    'image' => 'w-24 h-24 rounded-full object-cover',
    'name' => 'text-3xl font-bold text-gray-900 mb-1',
    'label' => 'text-sm font-semibold text-sky-600 mb-4',
    
    'contact-container' => 'flex flex-wrap justify-center gap-4 text-sm text-gray-600',
    'contact-inner-container' => '',
    'contact-item' => 'flex items-center gap-2',
    'links' => 'text-gray-700 hover:text-sky-600 underline',
    'icon' => 'size-4 text-sky-600',
    
    'section' => 'mb-8 last:mb-0',
    'section-title' => 'text-lg font-bold text-gray-900 mb-4 uppercase tracking-wider border-b pb-1',
    'section-inner' => '',

    'summary-container' => '',
    'summary-inner-container' => 'whitespace-pre-wrap',
    'summary' => 'text-base leading-relaxed text-gray-700',
    
    // Section containers (work, education, skills, etc.)
    'work-container' => '',
    'work-inner-container' => '',
    'volunteers-container' => '',
    'volunteers-inner-container' => '',
    'education-container' => '',
    'education-inner-container' => '',
    'awards-container' => '',
    'awards-inner-container' => '',
    'certificates-container' => '',
    'certificates-inner-container' => '',
    'publications-container' => '',
    'publications-inner-container' => '',
    'skills-container' => '',
    'skills-inner-container' => '',
    'languages-container' => '',
    'languages-inner-container' => '',
    'interests-container' => '',
    'interests-inner-container' => '',
    'references-container' => '',
    'references-inner-container' => '',
    'projects-container' => '',
    'projects-inner-container' => '',
    'downloads-container' => '',
    'downloads-inner-container' => '',

    'item-container' => 'mb-6 last:mb-0',
    'item-title' => 'text-base font-bold text-gray-900',
    'item-details' => 'text-xs text-gray-500 mb-2 flex flex-wrap gap-4',
    
    'list' => 'list-disc list-inside space-y-1 text-sm text-gray-700 mt-2',
    'list-item' => '',
    
    'badge-container' => 'flex flex-wrap gap-1.5',
    'badge' => 'inline-block px-2 py-0.5 text-xs rounded bg-sky-100 text-sky-700',
    'keyword-badge' => 'inline-block px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-700',
    'social-badge' => 'inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded bg-gray-100 text-gray-700 hover:bg-sky-600 hover:text-white',
    'date' => 'text-gray-500 font-medium',
    'subtitle' => 'text-sm font-semibold text-gray-800',
];
```

### Step 2: Create the Theme Presenter Class

Create `app/Presenters/Themes/MyThemePresenter.php` implementing `PresenterTheme`:

```php
<?php

namespace App\Presenters\Themes;

use App\Presenters\Contracts\PresenterTheme;

final class MyThemePresenter implements PresenterTheme
{
    public function containerThemes(): array
    {
        return ['my-theme' => 'container'];
    }

    public function basicsContainerThemes(): array
    {
        return ['my-theme' => 'basics-container'];
    }

    public function summaryContainerThemes(): array
    {
        return ['my-theme' => 'summary-container'];
    }

    public function workContainerThemes(): array
    {
        return ['my-theme' => 'work-container'];
    }

    public function volunteersContainerThemes(): array
    {
        return ['my-theme' => 'volunteers-container'];
    }

    public function educationContainerThemes(): array
    {
        return ['my-theme' => 'education-container'];
    }

    public function awardsContainerThemes(): array
    {
        return ['my-theme' => 'awards-container'];
    }

    public function certificatesContainerThemes(): array
    {
        return ['my-theme' => 'certificates-container'];
    }

    public function publicationsContainerThemes(): array
    {
        return ['my-theme' => 'publications-container'];
    }

    public function skillsContainerThemes(): array
    {
        return ['my-theme' => 'skills-container'];
    }

    public function languagesContainerThemes(): array
    {
        return ['my-theme' => 'languages-container'];
    }

    public function interestsContainerThemes(): array
    {
        return ['my-theme' => 'interests-container'];
    }

    public function referencesContainerThemes(): array
    {
        return ['my-theme' => 'references-container'];
    }

    public function projectsContainerThemes(): array
    {
        return ['my-theme' => 'projects-container'];
    }

    public function downloadsContainerThemes(): array
    {
        return ['my-theme' => 'downloads-container'];
    }

    public function basicsInnerContainerThemes(): array
    {
        return ['my-theme' => 'basics-inner-container'];
    }

    public function summaryInnerContainerThemes(): array
    {
        return ['my-theme' => 'summary-inner-container'];
    }

    public function workInnerContainerThemes(): array
    {
        return ['my-theme' => 'work-inner-container'];
    }

    public function volunteersInnerContainerThemes(): array
    {
        return ['my-theme' => 'volunteers-inner-container'];
    }

    public function educationInnerContainerThemes(): array
    {
        return ['my-theme' => 'education-inner-container'];
    }

    public function awardsInnerContainerThemes(): array
    {
        return ['my-theme' => 'awards-inner-container'];
    }

    public function certificatesInnerContainerThemes(): array
    {
        return ['my-theme' => 'certificates-inner-container'];
    }

    public function publicationsInnerContainerThemes(): array
    {
        return ['my-theme' => 'publications-inner-container'];
    }

    public function skillsInnerContainerThemes(): array
    {
        return ['my-theme' => 'skills-inner-container'];
    }

    public function languagesInnerContainerThemes(): array
    {
        return ['my-theme' => 'languages-inner-container'];
    }

    public function interestsInnerContainerThemes(): array
    {
        return ['my-theme' => 'interests-inner-container'];
    }

    public function referencesInnerContainerThemes(): array
    {
        return ['my-theme' => 'references-inner-container'];
    }

    public function projectsInnerContainerThemes(): array
    {
        return ['my-theme' => 'projects-inner-container'];
    }

    public function downloadsInnerContainerThemes(): array
    {
        return ['my-theme' => 'downloads-inner-container'];
    }

    public function nameThemes(): array
    {
        return ['my-theme' => 'name'];
    }

    public function labelThemes(): array
    {
        return ['my-theme' => 'label'];
    }

    public function sectionThemes(): array
    {
        return ['my-theme' => 'section'];
    }

    public function sectionTitleThemes(): array
    {
        return ['my-theme' => 'section-title'];
    }

    public function sectionInnerThemes(): array
    {
        return ['my-theme' => 'section-inner'];
    }

    public function itemTitleThemes(): array
    {
        return ['my-theme' => 'item-title'];
    }

    public function itemContainerThemes(): array
    {
        return ['my-theme' => 'item-container'];
    }

    public function itemDetailsThemes(): array
    {
        return ['my-theme' => 'item-details'];
    }

    public function summaryThemes(): array
    {
        return ['my-theme' => 'summary'];
    }

    public function contactContainerThemes(): array
    {
        return ['my-theme' => 'contact-container'];
    }

    public function badgeContainerThemes(): array
    {
        return ['my-theme' => 'badge-container'];
    }

    public function contactInnerContainerThemes(): array
    {
        return ['my-theme' => 'contact-inner-container'];
    }

    public function contactItemThemes(): array
    {
        return ['my-theme' => 'contact-item'];
    }

    public function listThemes(): array
    {
        return ['my-theme' => 'list'];
    }

    public function imageContainerThemes(): array
    {
        return ['my-theme' => 'image-container'];
    }

    public function imageThemes(): array
    {
        return ['my-theme' => 'image'];
    }

    public function linkThemes(): array
    {
        return ['my-theme' => 'links'];
    }

    public function iconThemes(): array
    {
        return ['my-theme' => 'icon'];
    }

    public function listItemThemes(): array
    {
        return ['my-theme' => 'list-item'];
    }

    public function badgeThemes(): array
    {
        return ['my-theme' => 'badge'];
    }

    public function keywordBadgeThemes(): array
    {
        return ['my-theme' => 'keyword-badge'];
    }

    public function socialBadgeThemes(): array
    {
        return ['my-theme' => 'social-badge'];
    }

    public function dateThemes(): array
    {
        return ['my-theme' => 'date'];
    }

    public function subTitleThemes(): array
    {
        return ['my-theme' => 'subtitle'];
    }

    public function emailThemes(): array
    {
        return ['my-theme' => 'contact-item'];
    }

    public function phoneThemes(): array
    {
        return ['my-theme' => 'contact-item'];
    }

    public function urlThemes(): array
    {
        return ['my-theme' => 'contact-item'];
    }

    public function locationThemes(): array
    {
        return ['my-theme' => 'contact-item'];
    }

    public function profileThemes(): array
    {
        return ['my-theme' => 'contact-item'];
    }

    public function coverLetterContainerThemes(): array
    {
        return ['my-theme' => 'cover-letter-container'];
    }

    public function coverLetterThemes(): array
    {
        return ['my-theme' => 'summary'];
    }

    public function fontUrls(): array
    {
        return ['https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'];
    }

    public function localFonts(): array
    {
        return [];
    }

    public function fontFamily(): string
    {
        return "'Inter', sans-serif";
    }
}
```

### Step 3: Register in `ResumeTheme` Enum

Add the case in `App\Enums\ResumeTheme`:

```php
case MY_THEME = 'my-theme';
```

---

## Layout Architectures & Constraints

1. **Stacked Layouts** (Default, Elegant, Bold, Terminal, PDF):
   - Sections stack vertically full-width.
2. **Grid / Multi-Column Layouts** (Professional):
   - Container uses grid: `'container' => '... grid grid-cols-1 md:grid-cols-3 gap-8'`.
   - Basics spans column 1: `'basics-container' => 'md:col-span-1 ...'`.
   - Summary spans columns 2 & 3: `'summary-container' => 'md:col-span-2 ...'`.
   - Other sections span full width: `'section' => 'md:col-span-3 ...'`.
   - *Requirement*: Grid-based layouts rely on specific section sorting (e.g., Summary must immediately follow Basics).

---

## Guardrails & Best Practices

- **Portability**: Themes must be fully portable and use **only** their own theme key (never reference `'default'` in custom theme presenter classes).
- **Inner Containers**: Always utilize container and inner container pairs (`basicsContainerThemes()` + `basicsInnerContainerThemes()`, etc.) for maximum styling flexibility.
- **Verification**: Run PHPStan (`composer phpstan`), Pint (`vendor/bin/pint --format agent`), and Pest tests (`php artisan test --compact`) after creating or modifying any theme.
