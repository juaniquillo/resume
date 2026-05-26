# TODO List

1. ✅ Work on a Presenter Theme class with themes for all resume presenter's section. An instance of that class can be created and passed to the presenter instance. This way we can have presenters with multiple themes.
2. ✅ Incorporate dark mode to the presenter theme.
3. ✅ Create layout blade component for the resume and the landing page.
4. ✅ Create a laravel cache strategy for the resume presenter. Not sure if for the whole presenter or maybe a little more granular. 
5. ✅ In the InvalidatesResumeCache trait add resolveResumeUserId() methods directly to models cases it's not direct user_id (ex. in relations like highlights, courses)
6. ✅ Add the missing resume CRUDs to the present method (awards, publications, etc.)
7. ✅ Add courses to the education section.
8. ✅ Create resume options CRUD decides what sections are shown and the order of the sections. Maybe more options later.
9. ✅ Make a reusable JavaScript component of the Light/Dark mode toggle from the landing page to reuse in the resume page.
10. ✅ Decouple the model query (ex. $this->user->basics()->first()) to a dedicated class that returns all sections to avoid query repetitions.
11. ✅ Google fonts / external font links should be managed either by the theme presenter of a font manager.
12. ✅ I already worked on a select dropdown and added a `type` column to the resume_exports table for choosing another type of export for the CRUD. Work on a ProcessPdfExport job using the package `spatie/laravel-pdf`. Then rename the existing one to ProcessJsonExport.
13. ✅ Add cache headers to the image's route.
14. ✅ There are still some missing resume elements in the resume presenter.
15. ✅ Add a flux switch field to options->general called "draft". since we're still in development, you can add it directly to 2026_05_21_162425_create_general_options_table and run `php artisan migrate:fresh` and `php artisan db:seed` to reset the database. This options will function as a switch between Published and Draft resume. If it's in draft, the resume page must show a "this resume is unavailable" kind of message.
16. ✅ Work on a preview button that opens the biggest flux modal available and shows how the resume looks like. You will be able to see how the resume looks like regardless of the "draft" option. The button should be noticeable and should be placed on the top right corner on every page of the dashboard.
17. ✅ Work on a new Tool->EmptyResumeCache CRUD. It simply deletes the cache just in case it needs to be forced.
18. ✅ Work on sidebar actions. starting with resume preview.
19. ✅ Add an "allow download" field in export crud. It should be a switch and when a record is marked for download there should be a link for downloading that file in the new "downloads" section of the resume. Only PDF exports can be marked for download. Only one can be marked at a time. That new section should not be available for the PDF export job.
20. ✅ Add an optional resume theme dropdown only when PDF export is chosen. This way we can have exports with multiple themes without changing the existing resume theme. 
21. ◻️ Work on an public API that serves either the whole resume as JSON or sections of it. This APU should be reate limited.
22. ✅ Add Sensitive fields to the General Options CRUD. It's a list of switches for choosing what fields to hide from the online version and API versions. There will be an option to apply them whenever an export is created. The fields are: phone and email for now.
23. ✅ Simplify resume cache. Remove version and keep only one cache per user.
24. ✅ Create a resume sections enum and use it instead of the constant on SectionVisibilityCrud.
25. ✅ Make sure all models have DocBlocks with the @property-read statement.
26. ◻️ Add a link with an icon for the status of the resume (published or draft) to the sidebar-actions component next to the preview button. The link should take you general options.
27. ◻️ Separate tests groups into the fast ones and the slow ones (mostly the ones that do blade rending). That will make easier to run only when the related code changed.
28. ◻️ Show a message in the dashboard that lets the user know when the basics has not been filled. The resume will not be available it that's the case.
29. ◻️ Refactor the section visibility crud into a Livewire component.
30. ◻️ Add default svg icon for the Networks that are not in the Enum.