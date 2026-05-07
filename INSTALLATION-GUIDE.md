# OmniKitty Cat Sanctuary — Installation Guide

A step-by-step walkthrough for installing the OmniKitty parent + child themes.

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Permalinks set to anything other than "Plain" (Settings → Permalinks)

## 1. Upload the Theme Files

Choose one of the following methods.

### Option A — FTP / SFTP

1. Connect to your server with an FTP/SFTP client.
2. Navigate to your WordPress installation's `wp-content/themes/` directory.
3. Upload both folders from this repository:
    - `omnikitty-theme/`  (parent)
    - `omnikitty-child/`  (child)

### Option B — WordPress Admin Upload

1. Zip each folder separately (`omnikitty-theme.zip`, `omnikitty-child.zip`).
2. In WP Admin, go to **Appearance → Themes → Add New → Upload Theme**.
3. Upload `omnikitty-theme.zip` first, then `omnikitty-child.zip`.

## 2. Activate the Themes

1. Go to **Appearance → Themes**.
2. Hover over **OmniKitty Cat Sanctuary** and click **Activate** (parent only).
3. Then activate **OmniKitty Cat Sanctuary Child** — this is the theme you'll be using.

> Activating the child theme runs a one-time install routine that creates the
> default pages, taxonomy terms, sample cats, and primary navigation menu.

## 3. Refresh Permalinks

Go to **Settings → Permalinks** and click **Save Changes**.
This refreshes rewrite rules so URLs like `/adopt/` and `/adopt/luna/` work.

## 4. Configuration Checklist

After activation, verify the following in WP Admin:

- [ ] **Settings → Reading** — front page is set to display the *Home* page.
- [ ] **Appearance → Menus** — the *Primary Menu* exists and is assigned to the
      *Primary Menu* and *Footer Menu* locations.
- [ ] **Cats** menu item appears in the admin sidebar.
- [ ] Navigate to `/adopt/` — you should see Luna, Jasper and Mittens.
- [ ] Navigate to `/adopt/luna/` — you should see Luna's profile and an
      adoption inquiry form.
- [ ] Visit the homepage to confirm the hero section, stats, and featured cats
      render correctly.

## 5. Customization

### Site identity
- **Appearance → Customize → Site Identity** — set your site title, tagline,
  and upload a logo / site icon (favicon).

### Menus
- **Appearance → Menus** — adjust the auto-created Primary Menu, or create new
  menus and assign them to the *Primary* and *Footer* locations.

### Widgets
- **Appearance → Widgets** — populate the *Primary Sidebar* and four *Footer*
  widget areas. Sensible defaults are shown when these are empty.

### Cats
Add a new cat in **Cats → Add New**. Required fields:
- Title (the cat's name)
- Editor content (their story)
- Featured image (the cat's photo)
- Sidebar **Cat Details** box: Age, Gender, Special Needs
- Sidebar taxonomies: assign **Adoption Status** and **Breed**

### Forms
The contact, adoption, volunteer and newsletter forms work out of the box and
deliver to your **General → Email Address**. No third-party plugin required.

## 6. Troubleshooting

**`/adopt/` returns 404 (page not found)**
Go to **Settings → Permalinks** and click **Save Changes** to flush rewrite rules.

**Sample content is missing**
Re-activate the child theme: switch away to another theme briefly, then
re-activate **OmniKitty Cat Sanctuary Child**. The install routine runs on
activation and is idempotent — it will not duplicate existing content.

**Forms are submitted but no email arrives**
WordPress' `wp_mail()` uses your server's PHP mail by default, which many
hosts block or filter to spam. Install an SMTP plugin (e.g. WP Mail SMTP) and
configure it with a transactional email provider.

**Featured images don't appear on cat profiles**
Make sure each cat has a *Featured image* set. Without one, a placeholder image
is shown.

**Mobile menu doesn't open**
Check the browser console for JavaScript errors. The child theme's
`js/child-theme.js` is responsible for the mobile menu toggle and must be
enqueued (it is, automatically, by the child theme's `functions.php`).

## 7. Going Live

Before launching publicly:

1. Replace the placeholder kitten images on cat profiles with real photos.
2. Edit **About**, **Research**, **Products**, **Donate** content to your
   organization's voice.
3. Update the social-media links in the footer.
4. Set up SSL (HTTPS) for your domain.
5. Install a caching plugin (WP Super Cache, W3 Total Cache, or LiteSpeed
   Cache) for performance.
6. Install a security plugin (Wordfence, Sucuri, or iThemes Security).
7. Configure regular backups.

You're ready to start saving lives, one cat at a time. 🐾
