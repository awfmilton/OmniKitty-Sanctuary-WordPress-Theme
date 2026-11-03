# OmniKitty Theme Implementation Plan

This document outlines the phased development plan for the OmniKitty WordPress theme. The goal is to implement all features described in the `README.md` file. Each phase includes a checklist to track progress.

---

## Phase 1: Core Theme Foundation

**Objective:** Establish the foundational elements of the theme, including basic styles, scripts, and core WordPress features. This phase is critical for all subsequent development.

**Checklist:**
- [x] **Parent Theme:** In `omnikitty-theme/functions.php`, add theme support for essential features:
    - [x] `title-tag`
    - [x] `post-thumbnails`
    - [x] `automatic-feed-links`
    - [x] `html5` (for searchform, comment-form, comment-list, gallery, caption)
- [x] **Parent Theme:** Register navigation menus in `omnikitty-theme/functions.php`.
    - [x] Register a 'Primary Menu' for the header.
    - [x] Register a 'Footer Menu' for social links.
- [x] **Parent Theme:** In `omnikitty-theme/style.css`, define the basic theme information and license.
- [x] **Child Theme:** In `omnikitty-child/functions.php`, ensure the parent and child stylesheets are enqueued correctly.
- [x] **Child Theme:** In `omnikitty-child/js/child-theme.js`, ensure the file exists and is ready for future interactive features.
- [x] **Child Theme:** Enqueue `child-theme.js` from `omnikitty-child/functions.php`.

---

## Phase 2: Cat Management System (CPT & Taxonomies)

**Objective:** Create the "Cat" Custom Post Type (CPT) and its associated taxonomies. This is the core functionality for managing the sanctuary's cats.

**Checklist:**
- [x] **Parent Theme:** In `omnikitty-theme/functions.php`, create the "Cat" Custom Post Type.
    - [x] Set the post type key to `cat`.
    - [x] Define public-facing labels (e.g., "Cats", "Add New Cat").
    - [x] Configure settings: `public`, `has_archive`, `supports` (title, editor, thumbnail), `rewrite` (slug: `adopt`).
- [x] **Parent Theme:** In `omnikitty-theme/functions.php`, create the "Adoption Status" custom taxonomy.
    - [x] Associate it with the `cat` post type.
    - [x] Make it hierarchical (like categories).
    - [x] Define labels (e.g., "Adoption Status", "Add New Status").
- [x] **Parent Theme:** In `omnikitty-theme/functions.php`, create the "Cat Breeds" custom taxonomy.
    - [x] Associate it with the `cat` post type.
    - [x] Make it non-hierarchical (like tags).
    - [x] Define labels (e.g., "Breeds", "Add New Breed").

---

## Phase 3: Cat Management System (Custom Fields & Admin)

**Objective:** Add custom fields to the "Cat" CPT to store detailed information about each cat.

**Checklist:**
- [x] **Parent Theme:** In `omnikitty-theme/functions.php`, add a meta box to the "Cat" CPT edit screen.
- [x] **Parent Theme:** Inside the meta box, create the following custom fields:
    - [x] **Age:** Text field.
    - [x] **Gender:** Dropdown (Male/Female).
    - [x] **Special Needs:** Textarea for detailed notes.
- [x] **Parent Theme:** Implement the logic to save the custom field data when a cat post is saved.
- [x] **Parent Theme:** Ensure all data is sanitized before saving to the database.

---

## Phase 4: Basic Page Templates (Header, Footer, Page)

**Objective:** Create the global header and footer and a basic template for standard WordPress pages.

**Checklist:**
- [x] **Parent Theme:** Implement a professional `header.php`.
    - [x] Include the site title/logo.
    - [x] Display the 'Primary Menu'.
    - [x] Add a placeholder for a mobile menu button.
- [x] **Parent Theme:** Implement a feature-rich `footer.php`.
    - [x] Include a widget area.
    - [x] Display the 'Footer Menu' for social links.
    - [x] Include a newsletter signup form placeholder.
- [x] **Child Theme:** Create a `page.php` template.
    - [x] Use `get_header()` and `get_footer()`.
    - [x] Implement a standard WordPress loop to display page title and content.
- [x] **Child Theme:** Create a basic `index.php` as a fallback, displaying a grid of blog posts.

---

## Phase 5: Homepage Implementation

**Objective:** Develop the `front-page.php` template to create a stunning and informative homepage.

**Checklist:**
- [x] **Child Theme:** In `front-page.php`, use `get_header()` and `get_footer()`.
- [x] **Child Theme:** Create the "Hero Section" with a mission statement.
- [x] **Child Theme:** Create a "Featured Cats" section.
    - [x] Write a custom `WP_Query` to get 3-4 cats.
    - [x] Display their photo, name, and a link to their profile.
- [x] **Child Theme:** Add placeholders for other content sections as described in `README.md` (About, Research, etc.).

---

## Phase 6: Cat Archive Page

**Objective:** Create the `archive-cat.php` template to list all cats and allow users to filter them.

**Checklist:**
- [x] **Child Theme:** Create the `archive-cat.php` template.
- [x] **Child Theme:** Display all cats in a responsive grid format.
    - [x] For each cat, show their photo, name, and adoption status.
- [x] **Child Theme:** Add filtering controls to the top of the page.
    - [x] Filter by "Adoption Status".
    - [x] Filter by "Cat Breed".
- [x] **Child Theme:** Implement the logic (likely with AJAX in `child-theme.js`) to update the cat grid based on filter selections.

---

## Phase 7: Single Cat Profile Page

**Objective:** Create the `single-cat.php` template to display a detailed profile for each cat.

**Checklist:**
- [x] **Child Theme:** Create the `single-cat.php` template.
- [x] **Child Theme:** Display all cat information:
    - [x] Name (Post Title)
    - [x] Photo (Featured Image)
    - [x] Description (Post Content)
    - [x] Age, Gender, Special Needs (Custom Fields)
    - [x] Adoption Status, Breed (Taxonomies)
- [x] **Child Theme:** Add a prominent "Adopt Me" button that links to an adoption form (to be built in a later phase).

---

## Phase 8: Advanced Search Implementation

**Objective:** Implement a powerful search system that allows users to find cats, stories, and pages.

**Checklist:**
- [x] **Child Theme:** Create a custom `searchform.php` to replace the default WordPress search form.
- [x] **Child Theme:** Create the `search.php` template to display search results.
- [x] **Child Theme:** Modify the search query to search across multiple post types (`post`, `page`, `cat`).
- [x] **Child Theme:** Display results grouped by post type.
- [x] **Child Theme:** Add breadcrumbs and pagination to the search results page.

---

## Phase 9: Secure Form System (Contact & Volunteer)

**Status:** Not Conflicting. Can be developed in parallel with Phase 10.

**Objective:** Create secure forms for general contact and volunteer applications.

**Checklist:**
- [x] **Parent Theme:** In `functions.php`, create a form handler for the contact form.
    - [x] Implement nonce verification, input sanitization, and CSRF protection.
- [x] **Parent Theme:** Create a similar form handler for the volunteer application form.
- [x] **Child Theme:** Create a "Contact" page template with the contact form.
- [x] **Child Theme:** Create a "Volunteer" page template with the volunteer application form, including fields for availability.

---

## Phase 10: Secure Form System (Adoption & Newsletter)

**Status:** Not Conflicting. Can be developed in parallel with Phase 9.

**Objective:** Create secure forms for cat adoption applications and newsletter signups.

**Checklist:**
- [x] **Parent Theme:** In `functions.php`, create a form handler for the adoption form.
    - [x] The form should automatically associate the submission with the specific cat being applied for.
    - [x] Implement nonce verification, input sanitization, and CSRF protection.
- [x] **Parent Theme:** Create a form handler for the newsletter signup form.
- [x] **Child Theme:** Integrate the adoption form into the `single-cat.php` page (perhaps in a modal).
- [x] **Child Theme:** Integrate the newsletter signup form into the `footer.php`.

---

## Phase 11: Automatic Content Generation

**Objective:** Create a function to automatically generate sample content upon theme activation, making setup easy.

**Checklist:**
- [x] **Child Theme:** In `functions.php`, hook into the `after_switch_theme` action.
- [x] **Child Theme:** Write a function to programmatically create:
    - [x] **Pages:** Homepage, About Us, Research, Products, Donate, Volunteer, Contact.
    - [x] **Sample Cats:** At least 3 cats with complete profiles (images, custom fields, taxonomies).
    - [x] **Blog Posts:** At least 3 sample blog posts.
    - [x] **Taxonomy Terms:** Pre-populate Adoption Status and Breeds.
- [x] **Child Theme:** Ensure the function only runs once on theme activation to prevent duplicate content.

---

## Phase 12: Final Review (Performance, Security, Accessibility)

**Objective:** Audit the entire theme to ensure it meets the high-quality standards outlined in the `README.md`.

**Checklist:**
- [x] **Performance:**
    - [x] Minify CSS and JS files.
    - [x] Implement lazy loading for images.
    - [x] Test compatibility with popular caching plugins.
- [x] **Security:**
    - [x] Review all form handlers for nonce verification and sanitization.
    - [x] Review all database queries to ensure they are secure.
    - [x] Ensure all output is properly escaped (XSS protection).
- [x] **Accessibility:**
    - [x] Test for full keyboard navigation.
    - [x] Verify screen reader compatibility.
    - [x] Check for high contrast and clear focus indicators.
    - [x] Ensure all images have appropriate alt text.
- [x] **Documentation:**
    - [x] Fill out the `INSTALLATION-GUIDE.md` with final instructions.
    - [x] Update the `README.md` if any features have changed.


---

## Upcoming Roadmap (66+ Hours of Planned Work — See `ROADMAP.md`)

### Phase 13: Charity Donations, Sponsor-a-Cat Recurring Micro-Donations & Supporter Tiers (~12 hours)
- [ ] Build `page-donate.php` charity donation experience with preset impact tiers and custom one-time / monthly recurring donation flows (Stripe / GiveWP / WooCommerce).
- [ ] WooCommerce Subscriptions / GiveWP recurring sponsorship widget on `single-cat.php` for special-needs sanctuary residents.
- [ ] Sponsor wall and automated monthly photo-update & tax-receipt email trigger for active sponsors.

### Phase 14: Persistent Database Storage for Adoption, Volunteer & Newsletter Forms (~10 hours)
- [ ] Register `adoption_application` and `volunteer_application` CPTs in `functions.php` so applications are persisted in WordPress before `wp_mail()` executes.
- [ ] Build newsletter subscriber storage with double opt-in confirmation and CSV export.

### Phase 15: Foster Network, Veterinary Records & Interactive Audio Visualizer (~16 hours)
- [ ] Create custom `ok_foster_caregiver` role and front-end dashboard for active foster homes.
- [ ] Add structured repeatable meta fields for FVRCP/Rabies vaccination dates, microchip IDs, and downloadable PDF medical summary sheets on `single-cat.php`.
- [ ] Wire mobile navigation menu toggle (`header.php:36`) and replace homepage placeholders (`front-page.php:60-73`) including a Web Audio API cat vocalization spectrogram viewer.

### Phase 16: Multilingual Support, Security, Infrastructure & CI/CD Testing Reviews (~28 hours)
- [ ] Complete `.pot`/`.po` translation catalogs (`es_ES`, `fr_CA`, `de_DE`) and Polylang/WPML compatibility.
- [ ] Fix DMARC/SPF `From:` header alignment (`functions.php:512, 555, 650`), per-visitor transient notices (`footer.php:28`), and form rate limiting.
- [ ] Add AJAX filter pagination/caching and GitHub Actions CI (`PHPCS`, `PHPUnit`, theme `.zip` bundler).
