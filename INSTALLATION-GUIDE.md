# OmniKitty Theme Installation Guide

Thank you for choosing the OmniKitty theme for your cat sanctuary website. This guide will walk you through the installation and setup process.

## Requirements

*   WordPress Version: 5.0 or higher
*   PHP Version: 7.4 or higher

## Installation

The OmniKitty theme is designed to be easy to install. It comes with a parent theme (`omnikitty-theme`) and a child theme (`omnikitty-child`). Using the child theme is highly recommended to ensure that you can safely update the parent theme in the future without losing your customizations.

Follow these steps to install the theme:

1.  **Download the Theme Files:** You should have two `.zip` files: `omnikitty-theme.zip` and `omnikitty-child.zip`.

2.  **Upload the Parent Theme:**
    *   Log in to your WordPress admin dashboard.
    *   Navigate to **Appearance > Themes**.
    *   Click the **Add New** button at the top of the page.
    *   Click the **Upload Theme** button.
    *   Choose the `omnikitty-theme.zip` file from your computer and click **Install Now**.
    *   **Do not activate the parent theme.**

3.  **Upload the Child Theme:**
    *   Return to the **Appearance > Themes** page.
    *   Click **Add New** and then **Upload Theme** again.
    *   Choose the `omnikitty-child.zip` file from your computer and click **Install Now**.

4.  **Activate the Child Theme:**
    *   Once the child theme is installed, click the **Activate** button.

## Automatic Content Setup

Upon activating the OmniKitty Child Theme for the first time, the theme will automatically generate the following content for you:

*   **Essential Pages:** Homepage, About Us, Research, Products, Donate, Volunteer, and Contact pages will be created.
*   **Sample Content:** Several sample blog posts and cat profiles will be created to demonstrate the theme's features.
*   **Taxonomy Terms:** The "Adoption Status" and "Cat Breeds" taxonomies will be pre-populated with common terms.

This feature is designed to run only once to prevent creating duplicate content.

## Initial Configuration

After activation, there are a few recommended steps to configure your new theme:

### 1. Configure the Menus

The theme includes two menu locations: a Primary Menu for the header and a Footer Menu for social links or other footer navigation.

*   Navigate to **Appearance > Menus**.
*   Create a new menu (e.g., "Main Navigation").
*   Add the pages you want to display in your header.
*   Under "Menu Settings" at the bottom, check the box for **Primary Menu**.
*   Click **Save Menu**.
*   (Optional) Create a second menu (e.g., "Social Links") and assign it to the **Footer Menu** location.

### 2. Set the Homepage

The automatic setup process should set your "Homepage" as the front page. If you need to change this:

*   Navigate to **Settings > Reading**.
*   Under "Your homepage displays", select **A static page**.
*   For "Homepage", select the "Homepage" page from the dropdown.
*   Click **Save Changes**.

## Customization

All customizations, such as custom CSS styles or new PHP functions, should be added to the child theme (`omnikitty-child`).

*   For CSS changes, edit the `style.css` file in the `omnikitty-child` directory.
*   For functional changes, add your code to the `functions.php` file in the `omnikitty-child` directory.

By using the child theme for your modifications, you can safely update the parent `omnikitty-theme` whenever new versions are released without losing your work.

---

Thank you for using OmniKitty! We hope it helps you build a beautiful and effective website for your sanctuary.
