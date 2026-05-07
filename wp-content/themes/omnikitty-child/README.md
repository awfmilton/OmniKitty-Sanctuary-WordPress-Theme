# OmniKitty Cat Sanctuary — Child Theme

The child theme that brings the OmniKitty Cat Sanctuary site to life.

## What this child theme provides

- A stunning **homepage** (`front-page.php`) with a gradient hero, statistics,
  three featured cats, three "How We Help" pillars, and the latest blog posts.
- A filterable **cat archive** (`archive-cat.php`) at `/adopt/` with adoption
  status & breed filters and pagination.
- Detailed **cat profiles** (`single-cat.php`) showing age, gender, breed,
  special-needs notes, and an adoption inquiry form scoped to that cat.
- An enhanced **page template** (`page.php`) used by all auto-generated pages.
- An **advanced search** (`search.php`) with type filters (All, Cats, Stories,
  Pages) backed by `pre_get_posts`.
- A custom **search form** (`searchform.php`) and **sidebar** (`sidebar.php`)
  with sensible defaults when no widgets are configured.
- **Sample content**: 3 cats (Luna, Jasper, Mittens), 3 blog posts, all
  taxonomy terms, and the seven default pages — all created on activation.
- Three **shortcodes** that power the auto-generated pages:
  - `[omnikitty_contact_form]`
  - `[omnikitty_volunteer_form]`
  - `[omnikitty_impact]` (the donation impact tiers)
- A small **JavaScript bundle** (`js/child-theme.js`) for the mobile menu,
  IntersectionObserver scroll animations, smooth-scroll anchors, and
  client-side form validation.

## File overview

| File | Purpose |
| ---- | ------- |
| `style.css` | Child styles (hero, sections, cat profile, animations) |
| `functions.php` | Asset enqueue, install routine, shortcodes, query filters |
| `front-page.php` | Homepage hero & featured content |
| `archive-cat.php` | `/adopt/` listing with filters |
| `single-cat.php` | Individual cat profile + adoption form |
| `page.php` | Generic page template |
| `search.php` | Advanced search results |
| `searchform.php` | Custom search form |
| `sidebar.php` | Primary sidebar with default widgets |
| `js/child-theme.js` | Interactive features |

## Customization tips

- **Update the hero copy** — edit `front-page.php`, the `.hero` section.
- **Change the colour palette** — edit the `:root` CSS variables in the parent
  theme's `style.css`. The child overrides only what it needs.
- **Add more sample content** — extend the arrays in
  `omnikitty_child_create_sample_cats()` / `omnikitty_child_create_sample_posts()`
  inside `functions.php`. The functions are idempotent and skip items that
  already exist.
- **Donate impact tiers** — edit the `$tiers` array inside
  `omnikitty_child_impact_shortcode()`.

## Browser support

Modern evergreen browsers (Chrome, Firefox, Safari, Edge). Graceful degradation
is in place for older browsers — scroll animations fall back to a simple
"already visible" state, and reduced-motion preferences are respected.

## Updates

This is a *child theme*. All customizations live here, so you can safely update
the parent theme without losing your changes.
