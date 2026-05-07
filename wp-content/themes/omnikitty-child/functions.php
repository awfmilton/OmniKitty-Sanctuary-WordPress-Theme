<?php
/**
 * OmniKitty Child Theme Functions
 *
 * Enqueues parent + child stylesheets, loads the child JS, and handles a
 * one-time install routine that creates the sanctuary's default pages,
 * sample cats, blog posts, and taxonomy terms.
 *
 * @package OmniKittyChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'OMNIKITTY_CHILD_VERSION' ) ) {
    define( 'OMNIKITTY_CHILD_VERSION', '1.0.0' );
}

/* ==========================================================================
 * Enqueue parent + child styles, plus child JS
 * ========================================================================== */

function omnikitty_child_enqueue_assets() {
    wp_enqueue_style(
        'omnikitty-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        OMNIKITTY_CHILD_VERSION
    );

    wp_enqueue_style(
        'omnikitty-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'omnikitty-parent-style' ),
        OMNIKITTY_CHILD_VERSION
    );

    wp_enqueue_script(
        'omnikitty-child-script',
        get_stylesheet_directory_uri() . '/js/child-theme.js',
        array(),
        OMNIKITTY_CHILD_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'omnikitty_child_enqueue_assets', 20 );

/* ==========================================================================
 * One-time install: pages, taxonomies, sample cats and posts
 * ========================================================================== */

function omnikitty_child_after_switch_theme() {
    omnikitty_child_create_default_pages();
    omnikitty_child_seed_taxonomies();
    omnikitty_child_create_sample_cats();
    omnikitty_child_create_sample_posts();
    omnikitty_child_create_primary_menu();

    // Make sure CPT rewrite rules are registered.
    if ( function_exists( 'omnikitty_register_cat_post_type' ) ) {
        omnikitty_register_cat_post_type();
    }
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'omnikitty_child_after_switch_theme' );

function omnikitty_child_create_default_pages() {
    $pages = array(
        'home' => array(
            'title'   => __( 'Home', 'omnikitty-child' ),
            'slug'    => 'home',
            'content' => __( 'Welcome to OmniKitty Cat Sanctuary.', 'omnikitty-child' ),
            'template'=> '',
        ),
        'about' => array(
            'title'   => __( 'About Us', 'omnikitty-child' ),
            'slug'    => 'about',
            'content' => "<h2>Our Mission</h2>\n<p>We rescue, rehabilitate and rehome cats in need — and use AI bioacoustics research to better understand feline communication.</p>\n<h2>Our Story</h2>\n<p>Founded in 2018, OmniKitty has grown from a small foster network into a full sanctuary serving hundreds of cats each year.</p>\n<h2>Our Values</h2>\n<ul><li>Compassion</li><li>Transparency</li><li>Science-led care</li><li>Lifelong commitment</li></ul>",
        ),
        'research' => array(
            'title'   => __( 'Research', 'omnikitty-child' ),
            'slug'    => 'research',
            'content' => "<h2>AI Bioacoustics for Cats</h2>\n<p>Our research team builds machine-learning models that interpret feline vocalisations — turning meows, purrs, trills and chirps into actionable welfare insights.</p>\n<p>Findings from the sanctuary feed directly into our care protocols, helping us identify pain, stress and contentment earlier and more accurately than human observation alone.</p>",
        ),
        'products' => array(
            'title'   => __( 'Products', 'omnikitty-child' ),
            'slug'    => 'products',
            'content' => "<h2>Sustainably Made, Cat-Approved</h2>\n<p>Every purchase from our shop directly funds sanctuary operations. We work with small manufacturers committed to sustainable materials and fair labor.</p>",
        ),
        'donate' => array(
            'title'   => __( 'Donate', 'omnikitty-child' ),
            'slug'    => 'donate',
            'content' => "<h2>Your gift saves lives</h2>\n<p>Every dollar goes directly to food, medical care and shelter for cats in need.</p>\n[omnikitty_impact]",
        ),
        'volunteer' => array(
            'title'   => __( 'Volunteer', 'omnikitty-child' ),
            'slug'    => 'volunteer',
            'content' => "<h2>Join our team</h2>\n<p>From socialising shy kittens to running adoption events, there's a role for everyone.</p>\n[omnikitty_volunteer_form]",
        ),
        'contact' => array(
            'title'   => __( 'Contact', 'omnikitty-child' ),
            'slug'    => 'contact',
            'content' => "<h2>Get in touch</h2>\n<p>We'd love to hear from you. Use the form below or email us at hello@omnikitty.example.</p>\n[omnikitty_contact_form]",
        ),
    );

    foreach ( $pages as $key => $data ) {
        $existing = get_page_by_path( $data['slug'] );
        if ( $existing ) {
            continue;
        }

        $page_id = wp_insert_post( array(
            'post_title'   => $data['title'],
            'post_name'    => $data['slug'],
            'post_content' => $data['content'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => get_current_user_id() ? get_current_user_id() : 1,
        ) );

        if ( ! is_wp_error( $page_id ) && 'home' === $key ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $page_id );
        }
    }
}

function omnikitty_child_seed_taxonomies() {
    $statuses = array(
        'Available for Adoption',
        'Adoption Pending',
        'Happily Homed',
        'Not Available',
    );
    foreach ( $statuses as $name ) {
        if ( ! term_exists( $name, 'adoption_status' ) ) {
            wp_insert_term( $name, 'adoption_status' );
        }
    }

    $breeds = array(
        'Domestic Shorthair',
        'Domestic Longhair',
        'Siamese',
        'Persian',
        'Maine Coon',
        'Tabby',
        'Tuxedo',
        'Calico',
    );
    foreach ( $breeds as $name ) {
        if ( ! term_exists( $name, 'cat_breed' ) ) {
            wp_insert_term( $name, 'cat_breed' );
        }
    }
}

function omnikitty_child_create_sample_cats() {
    $cats = array(
        array(
            'title'    => 'Luna',
            'slug'     => 'luna',
            'content'  => "<p>Luna is a gentle, affectionate three-year-old who adores window naps and slow chin scratches. After being rescued from a hoarding situation, she has blossomed into a confident lap cat who would do best in a calm home.</p>",
            'age'      => '3 years',
            'gender'   => 'Female',
            'special'  => 'Prefers a quiet, adult-only home.',
            'status'   => 'Available for Adoption',
            'breed'    => 'Domestic Shorthair',
        ),
        array(
            'title'    => 'Jasper',
            'slug'     => 'jasper',
            'content'  => "<p>Jasper is a playful tuxedo gentleman with boundless energy. He loves wand toys, treat puzzles, and any cardboard box he can fit into. Jasper would thrive in a household with another cat companion.</p>",
            'age'      => '2 years',
            'gender'   => 'Male',
            'special'  => 'Bonded with another resident — adoption with a feline friend preferred.',
            'status'   => 'Available for Adoption',
            'breed'    => 'Tuxedo',
        ),
        array(
            'title'    => 'Mittens',
            'slug'     => 'mittens',
            'content'  => "<p>Mittens is our sweetest senior, an eight-year-old longhair who has spent her life loving people. She's looking for a sunny windowsill and a forever lap to call home.</p>",
            'age'      => '8 years',
            'gender'   => 'Female',
            'special'  => 'Mild dental issues — managed with daily soft food.',
            'status'   => 'Adoption Pending',
            'breed'    => 'Domestic Longhair',
        ),
    );

    foreach ( $cats as $cat ) {
        if ( get_page_by_path( $cat['slug'], OBJECT, 'cat' ) ) {
            continue;
        }

        $post_id = wp_insert_post( array(
            'post_type'    => 'cat',
            'post_title'   => $cat['title'],
            'post_name'    => $cat['slug'],
            'post_content' => $cat['content'],
            'post_status'  => 'publish',
            'post_author'  => 1,
        ) );

        if ( is_wp_error( $post_id ) || ! $post_id ) {
            continue;
        }

        update_post_meta( $post_id, '_omnikitty_age',           $cat['age'] );
        update_post_meta( $post_id, '_omnikitty_gender',        $cat['gender'] );
        update_post_meta( $post_id, '_omnikitty_special_needs', $cat['special'] );

        wp_set_object_terms( $post_id, $cat['status'], 'adoption_status' );
        wp_set_object_terms( $post_id, $cat['breed'],  'cat_breed' );
    }
}

function omnikitty_child_create_sample_posts() {
    $posts = array(
        array(
            'title'   => 'Success Story: How Pickles Found His Forever Home',
            'slug'    => 'success-story-pickles',
            'content' => "<p>When Pickles arrived at the sanctuary, he was shy, underweight and wary of every passing footstep. Three months and a thousand slow blinks later, he walked confidently into his new family's home — and straight onto the couch.</p><p>Stories like Pickles' are why we exist.</p>",
        ),
        array(
            'title'   => 'What Our AI Learned by Listening to 10,000 Meows',
            'slug'    => 'ai-bioacoustics-meows',
            'content' => "<p>Our latest research model identifies six distinct vocal categories with 92% accuracy — including a previously undocumented \"requesting attention\" chirp. Here's what that means for sanctuary welfare.</p>",
        ),
        array(
            'title'   => 'Caring for Senior Cats: A Practical Guide',
            'slug'    => 'caring-for-senior-cats',
            'content' => "<p>Senior cats need a little extra — softer food, warmer beds, and gentler play. Here's everything we've learned about giving older felines a wonderful golden chapter.</p>",
        ),
    );

    foreach ( $posts as $p ) {
        if ( get_page_by_path( $p['slug'], OBJECT, 'post' ) ) {
            continue;
        }
        wp_insert_post( array(
            'post_type'    => 'post',
            'post_title'   => $p['title'],
            'post_name'    => $p['slug'],
            'post_content' => $p['content'],
            'post_status'  => 'publish',
            'post_author'  => 1,
        ) );
    }
}

function omnikitty_child_create_primary_menu() {
    $menu_name = 'Primary Menu';
    $menu      = wp_get_nav_menu_object( $menu_name );

    if ( ! $menu ) {
        $menu_id = wp_create_nav_menu( $menu_name );
        if ( is_wp_error( $menu_id ) ) {
            return;
        }

        $items = array(
            array( 'title' => __( 'Home', 'omnikitty-child' ),       'url' => home_url( '/' ) ),
            array( 'title' => __( 'About', 'omnikitty-child' ),      'url' => home_url( '/about/' ) ),
            array( 'title' => __( 'Adopt', 'omnikitty-child' ),      'url' => home_url( '/adopt/' ) ),
            array( 'title' => __( 'Research', 'omnikitty-child' ),   'url' => home_url( '/research/' ) ),
            array( 'title' => __( 'Volunteer', 'omnikitty-child' ),  'url' => home_url( '/volunteer/' ) ),
            array( 'title' => __( 'Donate', 'omnikitty-child' ),     'url' => home_url( '/donate/' ) ),
            array( 'title' => __( 'Contact', 'omnikitty-child' ),    'url' => home_url( '/contact/' ) ),
        );
        foreach ( $items as $item ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'   => $item['title'],
                'menu-item-url'     => $item['url'],
                'menu-item-status'  => 'publish',
                'menu-item-type'    => 'custom',
            ) );
        }

        $locations              = get_theme_mod( 'nav_menu_locations', array() );
        $locations['primary']   = $menu_id;
        $locations['footer']    = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}

/* ==========================================================================
 * Shortcodes for the auto-generated pages
 * ========================================================================== */

function omnikitty_child_contact_form_shortcode() {
    ob_start();
    ?>
    <div class="omnikitty-form-wrap">
        <?php omnikitty_render_form_message( 'contact' ); ?>
        <form method="post" action="" class="omnikitty-form" novalidate>
            <?php wp_nonce_field( 'omnikitty_form_contact', 'omnikitty_nonce' ); ?>
            <input type="hidden" name="omnikitty_form" value="contact" />
            <input type="text" name="omnikitty_website" value="" tabindex="-1" autocomplete="off" style="display:none" aria-hidden="true" />

            <div class="form-row required">
                <label for="contact-name"><?php esc_html_e( 'Your name', 'omnikitty-child' ); ?></label>
                <input type="text" id="contact-name" name="name" required />
            </div>
            <div class="form-row required">
                <label for="contact-email"><?php esc_html_e( 'Email', 'omnikitty-child' ); ?></label>
                <input type="email" id="contact-email" name="email" required />
            </div>
            <div class="form-row">
                <label for="contact-subject"><?php esc_html_e( 'Subject', 'omnikitty-child' ); ?></label>
                <input type="text" id="contact-subject" name="subject" />
            </div>
            <div class="form-row required">
                <label for="contact-message"><?php esc_html_e( 'Message', 'omnikitty-child' ); ?></label>
                <textarea id="contact-message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="button"><?php esc_html_e( 'Send Message', 'omnikitty-child' ); ?></button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'omnikitty_contact_form', 'omnikitty_child_contact_form_shortcode' );

function omnikitty_child_volunteer_form_shortcode() {
    ob_start();
    ?>
    <div class="omnikitty-form-wrap">
        <?php omnikitty_render_form_message( 'volunteer' ); ?>
        <form method="post" action="" class="omnikitty-form" novalidate>
            <?php wp_nonce_field( 'omnikitty_form_volunteer', 'omnikitty_nonce' ); ?>
            <input type="hidden" name="omnikitty_form" value="volunteer" />
            <input type="text" name="omnikitty_website" value="" tabindex="-1" autocomplete="off" style="display:none" aria-hidden="true" />

            <div class="form-row required">
                <label for="vol-name"><?php esc_html_e( 'Your name', 'omnikitty-child' ); ?></label>
                <input type="text" id="vol-name" name="name" required />
            </div>
            <div class="form-row required">
                <label for="vol-email"><?php esc_html_e( 'Email', 'omnikitty-child' ); ?></label>
                <input type="email" id="vol-email" name="email" required />
            </div>
            <div class="form-row">
                <label for="vol-phone"><?php esc_html_e( 'Phone', 'omnikitty-child' ); ?></label>
                <input type="tel" id="vol-phone" name="phone" />
            </div>
            <div class="form-row">
                <label><?php esc_html_e( 'Availability', 'omnikitty-child' ); ?></label>
                <?php
                $days = array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' );
                foreach ( $days as $d ) {
                    printf(
                        '<label style="display:inline-flex;gap:.3rem;margin-right:.6rem;font-weight:400;"><input type="checkbox" name="availability[]" value="%1$s" /> %1$s</label>',
                        esc_attr( $d )
                    );
                }
                ?>
            </div>
            <div class="form-row">
                <label for="vol-interests"><?php esc_html_e( 'Areas of interest', 'omnikitty-child' ); ?></label>
                <textarea id="vol-interests" name="interests" rows="4" placeholder="<?php esc_attr_e( 'e.g. socialising kittens, transport, events, photography', 'omnikitty-child' ); ?>"></textarea>
            </div>
            <button type="submit" class="button"><?php esc_html_e( 'Submit Application', 'omnikitty-child' ); ?></button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'omnikitty_volunteer_form', 'omnikitty_child_volunteer_form_shortcode' );

function omnikitty_child_impact_shortcode() {
    $tiers = array(
        array( 'amount' => '$25',  'label' => __( 'Feeds a cat for a month',          'omnikitty-child' ) ),
        array( 'amount' => '$75',  'label' => __( 'Provides vaccinations & vet care', 'omnikitty-child' ) ),
        array( 'amount' => '$150', 'label' => __( 'Funds a full spay or neuter',      'omnikitty-child' ) ),
        array( 'amount' => '$500', 'label' => __( 'Sponsors a sanctuary suite',       'omnikitty-child' ) ),
    );

    ob_start();
    echo '<div class="impact-grid">';
    foreach ( $tiers as $t ) {
        printf(
            '<div class="impact-card"><span class="impact-amount">%1$s</span><p>%2$s</p></div>',
            esc_html( $t['amount'] ),
            esc_html( $t['label'] )
        );
    }
    echo '</div>';
    echo '<p class="text-center"><a class="button button-success" href="' . esc_url( home_url( '/donate/#give' ) ) . '">' . esc_html__( 'Donate Now', 'omnikitty-child' ) . '</a></p>';
    return ob_get_clean();
}
add_shortcode( 'omnikitty_impact', 'omnikitty_child_impact_shortcode' );

/* ==========================================================================
 * Cat archive: filter query by status / breed
 * ========================================================================== */

function omnikitty_child_filter_cat_archive( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( ! $query->is_post_type_archive( 'cat' ) ) {
        return;
    }

    $tax_query = array();

    if ( ! empty( $_GET['adoption_status'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'adoption_status',
            'field'    => 'slug',
            'terms'    => sanitize_title( wp_unslash( $_GET['adoption_status'] ) ),
        );
    }
    if ( ! empty( $_GET['breed'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'cat_breed',
            'field'    => 'slug',
            'terms'    => sanitize_title( wp_unslash( $_GET['breed'] ) ),
        );
    }

    if ( ! empty( $tax_query ) ) {
        if ( count( $tax_query ) > 1 ) {
            $tax_query['relation'] = 'AND';
        }
        $query->set( 'tax_query', $tax_query );
    }

    $query->set( 'posts_per_page', 12 );
}
add_action( 'pre_get_posts', 'omnikitty_child_filter_cat_archive' );

/* ==========================================================================
 * Search: filter by post type via ?type=
 * ========================================================================== */

function omnikitty_child_filter_search( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
        return;
    }
    if ( empty( $_GET['type'] ) ) {
        $query->set( 'post_type', array( 'post', 'page', 'cat' ) );
        return;
    }

    $type = sanitize_key( wp_unslash( $_GET['type'] ) );
    $allowed = array( 'post', 'page', 'cat' );
    if ( in_array( $type, $allowed, true ) ) {
        $query->set( 'post_type', $type );
    }
}
add_action( 'pre_get_posts', 'omnikitty_child_filter_search' );
