# OmniKitty Sanctuary WordPress Theme — Engineering Roadmap

This document tracks the planned engineering roadmap across charity donations & sponsorships, persistent database form storage, sanctuary operations, localization, security, infrastructure, and CI/CD test automation (**~66 hours of planned work**).

---

## Phase 1: Charity Donations, Recurring Sponsorships & Supporter Tiers (~12 hours)

Enable animal sanctuaries and non-profit rescue charities using the theme to accept one-time donations, recurring monthly sponsorships, and "Sponsor-a-Cat" resident care contributions directly:

- [ ] **Direct Charity Donation & Stripe / GiveWP / WooCommerce Integration (`page-donate.php`, `single-cat.php`, `functions.php`):**
  - Build a dedicated `page-donate.php` template with preset impact tiers (e.g., `$15 Vaccination Fund`, `$35 Monthly Food & Litter`, `$100 Emergency Vet Care`, custom amount) supporting both one-time and monthly recurring charity donations via Stripe Checkout / GiveWP / WooCommerce Donations.
  - Add a **Sponsor This Cat** recurring micro-donation widget on `single-cat.php` (`wp-content/themes/omnikitty-child/single-cat.php`) tied to each cat's `_cat_woocommerce_product_id` meta field, with a public Sponsor Wall and automated tax-receipt email generation.
  - Add theme-level supporter donation links so sanctuaries and developers can sustain ongoing theme maintenance.

---

## Phase 2: Persistent Database Storage for Adoption, Volunteer & Newsletter Forms (~10 hours)

Currently, all four theme forms in `wp-content/themes/omnikitty-theme/functions.php` (`omnikitty_handle_contact_form` at line 491, `omnikitty_handle_volunteer_form` at line 534, `omnikitty_handle_newsletter_signup` at line 577, and `omnikitty_handle_adoption_form` at line 617) only call `wp_mail()` and explicitly note `// At this point, you would typically save the application to the database. For now, we will email it.` If `wp_mail()` fails or is spam-filtered, applications are permanently lost:

- [ ] **`adoption_application` & `volunteer_application` Custom Post Types:**
  - Register private CPTs for adoption and volunteer submissions linked to `_cat_id`, storing applicant name, email, phone, housing details, availability, and review status (`new`, `under_review`, `approved`, `declined`) in `wp_posts` / `wp_postmeta` BEFORE attempting `wp_mail()`.
- [ ] **Newsletter Subscriber Table & Double Opt-In:**
  - Store newsletter subscribers with double opt-in confirmation tokens, unsubscribe links, and CSV export in `wp-admin`.

---

## Phase 3: Fix Inert UI Elements & Homepage Placeholders (~6 hours)

- [ ] **Wire Mobile Navigation Menu Toggle (`header.php:36` & `child-theme.js`):**
  - `<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">` in `wp-content/themes/omnikitty-theme/header.php:36` currently has no JavaScript click handler. Add accessible toggle logic (`aria-expanded`, Escape key close, focus trap) in `child-theme.js`.
- [ ] **Replace Hardcoded Homepage Placeholders (`front-page.php:60-73`):**
  - Replace `.about-us-placeholder` and `.research-placeholder` in `wp-content/themes/omnikitty-child/front-page.php:60-73` with dynamic WordPress Customizer / Page excerpt blocks and an interactive Web Audio API cat vocalization spectrogram viewer.

---

## Phase 4: Foster Caregiver Portal & Veterinary Medical Records (~10 hours)

- [ ] **Foster Caregiver Role & Logbook:** Create the `ok_foster_caregiver` role and front-end caregiver log for recording weight, feeding, and socialization notes per cat.
- [ ] **Veterinary Vaccination & Microchip Meta + Printable PDF Sheet:** Add repeatable FVRCP/Rabies vaccination, spay/neuter, and microchip meta fields to `cat` CPT with a downloadable medical summary sheet for adopters.

---

## Phase 5: Multilingual Support & Localization (`languages/`) (~8 hours)

- [ ] **Complete WordPress i18n (`.pot`, `.po`, `.mo`) & Polylang / WPML Compatibility:**
  - Generate `languages/omnikitty.pot` and `languages/omnikitty-child.pot` covering all strings across parent and child themes.
  - Ship complete translations for Spanish (`es_ES`), French (`fr_CA` / `fr_FR`), and German (`de_DE`), and register custom post types (`cat`) and taxonomies (`adoption_status`, `cat_breed`) for Polylang/WPML translation sync.

---

## Phase 6: Security Review (~8 hours)

- [ ] **Fix Spoofed `From:` Header & DMARC Alignment (`functions.php:512, 555, 650`):**
  - Currently `omnikitty_handle_contact_form`, `omnikitty_handle_volunteer_form`, and `omnikitty_handle_adoption_form` set `From: $name <$visitor_email>`, which triggers SPF/DMARC rejection on modern mail hosts. Use the site's domain address in `From:` and keep the visitor's address in `Reply-To:` only.
- [ ] **Per-Visitor Transient Scoping & Form Rate Limiting (`footer.php:28-30`):**
  - Replace the global `omnikitty_newsletter_success` transient in `footer.php` (race condition across concurrent visitors) with session/cookie-scoped flash notices, and add IP rate-limiting + honeypot anti-spam fields to all public `admin-post.php` form handlers.

---

## Phase 7: Infrastructure & Performance Review (~5 hours)

- [ ] **AJAX Filter Caching, Pagination & Asset Pipeline:**
  - Add pagination and transient query caching to the `archive-cat.php` AJAX filter handler so sanctuaries with 500+ cats do not run unbounded `WP_Query` scans on every dropdown change.

---

## Phase 8: GitHub Actions & Automated Testing Review (~7 hours)

- [ ] **GitHub Actions CI Workflow (`.github/workflows/ci.yml`):**
  - Add automated PHP syntax linting (PHP 8.1 / 8.2 / 8.3), WordPress Coding Standards (`PHPCS` with `WordPress-Core` & `WordPress-VIP-Go` security rules), PHPUnit integration tests for form handlers and MCP abilities (`omnikitty/list-cats`, `omnikitty/get-cat`, `omnikitty/upsert-cat`), and automated `.zip` theme release packaging.
