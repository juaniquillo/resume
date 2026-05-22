# TODO List

- ✅ Work on a Presenter Theme class with themes for all resume presenter's section. An instance of that class can be created and passed to the presenter instance. This way we can have presenters with multiple themes.
- ✅ Incorporate dark mode to the presenter theme.
- ✅ Create layout blade component for the resume and the landing page.
- ✅ Create a laravel cache strategy for the resume presenter. Not sure if for the whole presenter or maybe a little more granular. 
- ✅ In the InvalidatesResumeCache trait add resolveResumeUserId() methods directly to models cases it's not direct user_id (ex. in relations like highlights, courses)
- ✅ Add the missing resume CRUDs to the present method (awards, publications, etc.)
- ✅ Add courses to the education section.
- ✅ Create resume options CRUD decides what sections are shown and the order of the sections. Maybe more options later.
- ✅ Make a reusable JavaScript component of the Light/Dark mode toggle from the landing page to reuse in the resume page.
- ✅ Decouple the model query (ex. $this->user->basics()->first()) to a dedicated class that returns all sections to avoid query repetitions.
- ✅ Google fonts / external font links should be managed either by the theme presenter of a font manager.