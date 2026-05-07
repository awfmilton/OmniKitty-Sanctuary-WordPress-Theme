<?php
/**
 * OmniKitty Cat Sanctuary Theme - Core Functions
 *
 * Sets up theme defaults, registers menus & widgets, defines the Cat custom
 * post type and taxonomies, and provides secure handlers for the contact,
 * adoption, volunteer and newsletter forms.
 *
 * @package OmniKitty
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'OMNIKITTY_VERSION' ) ) {
    define( 'OMNIKITTY_VERSION', '1.0.0' );
}

/* ==========================================================================
 * Theme setup
 * ========================================================================== */

if ( ! function_exists( 'omnikitty_setup' ) ) {
    function omnikitty_setup() {
        load_theme_textdomain( 'omnikitty', get_template_directory() . '/languages' );

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo', array(
            'height'      => 100,
            'width'       => 300,
            'flex-height' => true,
            'flex-width'  => true,
        ) );
        add_theme_support( 'html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
        ) );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'align-wide' );
        add_theme_support( 'editor-styles' );

        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'omnikitty' ),
            'footer'  => __( 'Footer Menu', 'omnikitty' ),
        ) );

        // Image sizes for cats and posts.
        add_image_size( 'cat-thumbnail', 600, 450, true );
        add_image_size( 'cat-large', 1200, 800, true );
    }
}
add_action( 'after_setup_theme', 'omnikitty_setup' );

/* ==========================================================================
 * Enqueue scripts & styles
 * ========================================================================== */

function omnikitty_enqueue_assets() {
    wp_enqueue_style(
        'omnikitty-style',
        get_stylesheet_uri(),
        array(),
        OMNIKITTY_VERSION
    );

    if ( is_child_theme() ) {
        // Child stylesheet handled separately via its own functions.php enqueue.
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'omnikitty_enqueue_assets' );

/* ==========================================================================
 * Sidebars / widget areas
 * ========================================================================== */

function omnikitty_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Primary Sidebar', 'omnikitty' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Main sidebar appearing on posts and pages.', 'omnikitty' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( __( 'Footer %d', 'omnikitty' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => sprintf( __( 'Footer widget area %d.', 'omnikitty' ), $i ),
            'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
}
add_action( 'widgets_init', 'omnikitty_widgets_init' );

/* ==========================================================================
 * Custom post type: Cat
 * ========================================================================== */

function omnikitty_register_cat_post_type() {
    $labels = array(
        'name'                  => _x( 'Cats', 'Post type general name', 'omnikitty' ),
        'singular_name'         => _x( 'Cat', 'Post type singular name', 'omnikitty' ),
        'menu_name'             => _x( 'Cats', 'Admin Menu text', 'omnikitty' ),
        'name_admin_bar'        => _x( 'Cat', 'Add New on Toolbar', 'omnikitty' ),
        'add_new'               => __( 'Add New', 'omnikitty' ),
        'add_new_item'          => __( 'Add New Cat', 'omnikitty' ),
        'new_item'              => __( 'New Cat', 'omnikitty' ),
        'edit_item'             => __( 'Edit Cat', 'omnikitty' ),
        'view_item'             => __( 'View Cat', 'omnikitty' ),
        'all_items'             => __( 'All Cats', 'omnikitty' ),
        'search_items'          => __( 'Search Cats', 'omnikitty' ),
        'not_found'             => __( 'No cats found.', 'omnikitty' ),
        'not_found_in_trash'    => __( 'No cats found in Trash.', 'omnikitty' ),
        'featured_image'        => __( 'Cat Photo', 'omnikitty' ),
        'set_featured_image'    => __( 'Set cat photo', 'omnikitty' ),
        'remove_featured_image' => __( 'Remove cat photo', 'omnikitty' ),
        'archives'              => __( 'Cat Archives', 'omnikitty' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-pets',
        'menu_position'      => 5,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'adopt', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => 'adopt',
        'hierarchical'       => false,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
    );

    register_post_type( 'cat', $args );
}
add_action( 'init', 'omnikitty_register_cat_post_type' );

/* ==========================================================================
 * Custom taxonomies
 * ========================================================================== */

function omnikitty_register_cat_taxonomies() {
    register_taxonomy( 'adoption_status', 'cat', array(
        'labels' => array(
            'name'          => __( 'Adoption Statuses', 'omnikitty' ),
            'singular_name' => __( 'Adoption Status', 'omnikitty' ),
            'menu_name'     => __( 'Adoption Status', 'omnikitty' ),
        ),
        'public'            => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'hierarchical'      => true,
        'rewrite'           => array( 'slug' => 'adoption-status' ),
    ) );

    register_taxonomy( 'cat_breed', 'cat', array(
        'labels' => array(
            'name'          => __( 'Breeds', 'omnikitty' ),
            'singular_name' => __( 'Breed', 'omnikitty' ),
            'menu_name'     => __( 'Breeds', 'omnikitty' ),
        ),
        'public'            => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'hierarchical'      => false,
        'rewrite'           => array( 'slug' => 'breed' ),
    ) );
}
add_action( 'init', 'omnikitty_register_cat_taxonomies' );

/* ==========================================================================
 * Cat meta box: age, gender, special needs
 * ========================================================================== */

function omnikitty_add_cat_meta_box() {
    add_meta_box(
        'omnikitty_cat_details',
        __( 'Cat Details', 'omnikitty' ),
        'omnikitty_cat_meta_box_html',
        'cat',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'omnikitty_add_cat_meta_box' );

function omnikitty_cat_meta_box_html( $post ) {
    wp_nonce_field( 'omnikitty_save_cat_meta', 'omnikitty_cat_meta_nonce' );

    $age           = get_post_meta( $post->ID, '_omnikitty_age', true );
    $gender        = get_post_meta( $post->ID, '_omnikitty_gender', true );
    $special_needs = get_post_meta( $post->ID, '_omnikitty_special_needs', true );
    ?>
    <p>
        <label for="omnikitty_age"><strong><?php esc_html_e( 'Age', 'omnikitty' ); ?></strong></label>
        <input type="text" id="omnikitty_age" name="omnikitty_age"
               value="<?php echo esc_attr( $age ); ?>"
               placeholder="<?php esc_attr_e( 'e.g. 2 years', 'omnikitty' ); ?>"
               style="width:100%;" />
    </p>
    <p>
        <label for="omnikitty_gender"><strong><?php esc_html_e( 'Gender', 'omnikitty' ); ?></strong></label>
        <select id="omnikitty_gender" name="omnikitty_gender" style="width:100%;">
            <option value=""><?php esc_html_e( '— Select —', 'omnikitty' ); ?></option>
            <option value="Male"   <?php selected( $gender, 'Male' ); ?>><?php esc_html_e( 'Male', 'omnikitty' ); ?></option>
            <option value="Female" <?php selected( $gender, 'Female' ); ?>><?php esc_html_e( 'Female', 'omnikitty' ); ?></option>
        </select>
    </p>
    <p>
        <label for="omnikitty_special_needs"><strong><?php esc_html_e( 'Special Needs', 'omnikitty' ); ?></strong></label>
        <textarea id="omnikitty_special_needs" name="omnikitty_special_needs" rows="4" style="width:100%;"><?php echo esc_textarea( $special_needs ); ?></textarea>
    </p>
    <?php
}

function omnikitty_save_cat_meta( $post_id ) {
    if ( ! isset( $_POST['omnikitty_cat_meta_nonce'] ) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['omnikitty_cat_meta_nonce'] ) ), 'omnikitty_save_cat_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'cat' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['omnikitty_age'] ) ) {
        update_post_meta( $post_id, '_omnikitty_age', sanitize_text_field( wp_unslash( $_POST['omnikitty_age'] ) ) );
    }
    if ( isset( $_POST['omnikitty_gender'] ) ) {
        $gender = sanitize_text_field( wp_unslash( $_POST['omnikitty_gender'] ) );
        if ( in_array( $gender, array( '', 'Male', 'Female' ), true ) ) {
            update_post_meta( $post_id, '_omnikitty_gender', $gender );
        }
    }
    if ( isset( $_POST['omnikitty_special_needs'] ) ) {
        update_post_meta( $post_id, '_omnikitty_special_needs', sanitize_textarea_field( wp_unslash( $_POST['omnikitty_special_needs'] ) ) );
    }
}
add_action( 'save_post_cat', 'omnikitty_save_cat_meta' );

/* ==========================================================================
 * Helper: get cat field
 * ========================================================================== */

function omnikitty_get_cat_field( $field, $post_id = null ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    return get_post_meta( $post_id, '_omnikitty_' . $field, true );
}

/* ==========================================================================
 * Form handlers (contact, adoption, volunteer, newsletter)
 * ========================================================================== */

function omnikitty_handle_form_submission() {
    if ( ! isset( $_POST['omnikitty_form'] ) ) {
        return;
    }

    $form = sanitize_key( wp_unslash( $_POST['omnikitty_form'] ) );

    if ( ! isset( $_POST['omnikitty_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['omnikitty_nonce'] ) ), 'omnikitty_form_' . $form ) ) {
        omnikitty_set_form_message( $form, 'error', __( 'Security check failed. Please reload and try again.', 'omnikitty' ) );
        return;
    }

    // Honeypot — bots fill this field; humans don't see it.
    if ( ! empty( $_POST['omnikitty_website'] ) ) {
        omnikitty_set_form_message( $form, 'error', __( 'Submission blocked.', 'omnikitty' ) );
        return;
    }

    switch ( $form ) {
        case 'contact':
            omnikitty_process_contact_form();
            break;
        case 'adoption':
            omnikitty_process_adoption_form();
            break;
        case 'volunteer':
            omnikitty_process_volunteer_form();
            break;
        case 'newsletter':
            omnikitty_process_newsletter_form();
            break;
    }
}
add_action( 'init', 'omnikitty_handle_form_submission', 20 );

function omnikitty_set_form_message( $form, $type, $message ) {
    if ( ! session_id() && ! headers_sent() ) {
        @session_start();
    }
    $_SESSION['omnikitty_form_messages'][ $form ] = array( 'type' => $type, 'message' => $message );
}

function omnikitty_get_form_message( $form ) {
    if ( ! session_id() && ! headers_sent() ) {
        @session_start();
    }
    if ( ! empty( $_SESSION['omnikitty_form_messages'][ $form ] ) ) {
        $msg = $_SESSION['omnikitty_form_messages'][ $form ];
        unset( $_SESSION['omnikitty_form_messages'][ $form ] );
        return $msg;
    }
    return null;
}

function omnikitty_render_form_message( $form ) {
    $msg = omnikitty_get_form_message( $form );
    if ( ! $msg ) {
        return;
    }
    printf(
        '<div class="form-message %1$s" role="alert">%2$s</div>',
        esc_attr( $msg['type'] ),
        esc_html( $msg['message'] )
    );
}

function omnikitty_admin_email() {
    $email = get_option( 'admin_email' );
    return is_email( $email ) ? $email : 'admin@example.com';
}

function omnikitty_process_contact_form() {
    $name    = isset( $_POST['name'] )    ? sanitize_text_field( wp_unslash( $_POST['name'] ) )    : '';
    $email   = isset( $_POST['email'] )   ? sanitize_email( wp_unslash( $_POST['email'] ) )         : '';
    $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

    if ( '' === $name || ! is_email( $email ) || '' === $message ) {
        omnikitty_set_form_message( 'contact', 'error', __( 'Please complete all required fields with a valid email.', 'omnikitty' ) );
        return;
    }

    $to      = omnikitty_admin_email();
    $subj    = sprintf( '[%s] Contact: %s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $subject ? $subject : __( 'New message', 'omnikitty' ) );
    $body    = sprintf( "Name: %s\nEmail: %s\n\n%s", $name, $email, $message );
    $headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

    wp_mail( $to, $subj, $body, $headers );
    omnikitty_set_form_message( 'contact', 'success', __( 'Thank you! Your message has been sent.', 'omnikitty' ) );
}

function omnikitty_process_adoption_form() {
    $name    = isset( $_POST['name'] )    ? sanitize_text_field( wp_unslash( $_POST['name'] ) )    : '';
    $email   = isset( $_POST['email'] )   ? sanitize_email( wp_unslash( $_POST['email'] ) )         : '';
    $phone   = isset( $_POST['phone'] )   ? sanitize_text_field( wp_unslash( $_POST['phone'] ) )   : '';
    $cat_id  = isset( $_POST['cat_id'] )  ? absint( $_POST['cat_id'] ) : 0;
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

    if ( '' === $name || ! is_email( $email ) || ! $cat_id ) {
        omnikitty_set_form_message( 'adoption', 'error', __( 'Please complete the required fields.', 'omnikitty' ) );
        return;
    }

    $cat_title = get_the_title( $cat_id );
    $to        = omnikitty_admin_email();
    $subj      = sprintf( '[%s] Adoption inquiry: %s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $cat_title );
    $body      = sprintf(
        "Adoption inquiry for: %s\n\nName: %s\nEmail: %s\nPhone: %s\n\n%s",
        $cat_title, $name, $email, $phone, $message
    );
    $headers   = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

    wp_mail( $to, $subj, $body, $headers );
    omnikitty_set_form_message( 'adoption', 'success', __( 'Thank you! We have received your adoption inquiry and will be in touch shortly.', 'omnikitty' ) );
}

function omnikitty_process_volunteer_form() {
    $name         = isset( $_POST['name'] )         ? sanitize_text_field( wp_unslash( $_POST['name'] ) )         : '';
    $email        = isset( $_POST['email'] )        ? sanitize_email( wp_unslash( $_POST['email'] ) )              : '';
    $phone        = isset( $_POST['phone'] )        ? sanitize_text_field( wp_unslash( $_POST['phone'] ) )         : '';
    $availability = isset( $_POST['availability'] ) ? array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['availability'] ) ) : array();
    $interests    = isset( $_POST['interests'] )    ? sanitize_textarea_field( wp_unslash( $_POST['interests'] ) ) : '';

    if ( '' === $name || ! is_email( $email ) ) {
        omnikitty_set_form_message( 'volunteer', 'error', __( 'Please complete the required fields.', 'omnikitty' ) );
        return;
    }

    $to      = omnikitty_admin_email();
    $subj    = sprintf( '[%s] Volunteer application from %s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $name );
    $body    = sprintf(
        "Volunteer application\n\nName: %s\nEmail: %s\nPhone: %s\nAvailability: %s\n\nInterests:\n%s",
        $name, $email, $phone, implode( ', ', $availability ), $interests
    );
    $headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

    wp_mail( $to, $subj, $body, $headers );
    omnikitty_set_form_message( 'volunteer', 'success', __( 'Thank you for volunteering! We will reach out soon.', 'omnikitty' ) );
}

function omnikitty_process_newsletter_form() {
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    if ( ! is_email( $email ) ) {
        omnikitty_set_form_message( 'newsletter', 'error', __( 'Please enter a valid email address.', 'omnikitty' ) );
        return;
    }

    $list = get_option( 'omnikitty_newsletter_list', array() );
    if ( ! in_array( $email, $list, true ) ) {
        $list[] = $email;
        update_option( 'omnikitty_newsletter_list', $list, false );
    }

    wp_mail(
        omnikitty_admin_email(),
        sprintf( '[%s] New newsletter signup', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) ),
        sprintf( "New email subscribed: %s", $email )
    );

    omnikitty_set_form_message( 'newsletter', 'success', __( 'Thanks! You are subscribed.', 'omnikitty' ) );
}

/* ==========================================================================
 * Pagination & breadcrumbs helpers
 * ========================================================================== */

function omnikitty_pagination() {
    global $wp_query;

    $pagination = paginate_links( array(
        'total'     => $wp_query->max_num_pages,
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'prev_text' => __( '&laquo; Prev', 'omnikitty' ),
        'next_text' => __( 'Next &raquo;', 'omnikitty' ),
        'type'      => 'array',
    ) );

    if ( ! empty( $pagination ) ) {
        echo '<nav class="pagination" aria-label="' . esc_attr__( 'Posts navigation', 'omnikitty' ) . '">';
        foreach ( $pagination as $link ) {
            echo wp_kses_post( $link );
        }
        echo '</nav>';
    }
}

function omnikitty_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }
    $sep = '<span class="separator" aria-hidden="true">›</span>';
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'omnikitty' ) . '">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'omnikitty' ) . '</a>';

    if ( is_singular( 'cat' ) ) {
        echo ' ' . $sep . ' <a href="' . esc_url( get_post_type_archive_link( 'cat' ) ) . '">' . esc_html__( 'Adopt', 'omnikitty' ) . '</a>';
        echo ' ' . $sep . ' <span>' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_post_type_archive( 'cat' ) ) {
        echo ' ' . $sep . ' <span>' . esc_html__( 'Adopt', 'omnikitty' ) . '</span>';
    } elseif ( is_singular() ) {
        echo ' ' . $sep . ' <span>' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_archive() ) {
        echo ' ' . $sep . ' <span>' . wp_kses_post( get_the_archive_title() ) . '</span>';
    } elseif ( is_search() ) {
        echo ' ' . $sep . ' <span>' . sprintf( esc_html__( 'Search: %s', 'omnikitty' ), esc_html( get_search_query() ) ) . '</span>';
    } elseif ( is_404() ) {
        echo ' ' . $sep . ' <span>' . esc_html__( 'Not Found', 'omnikitty' ) . '</span>';
    }

    echo '</nav>';
}

/* ==========================================================================
 * Security headers & cleanup
 * ========================================================================== */

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

function omnikitty_remove_version_strings( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src',  'omnikitty_remove_version_strings', 9999 );
add_filter( 'script_loader_src', 'omnikitty_remove_version_strings', 9999 );

/* ==========================================================================
 * Excerpt length & "more" link
 * ========================================================================== */

function omnikitty_excerpt_length( $length ) {
    return 28;
}
add_filter( 'excerpt_length', 'omnikitty_excerpt_length', 999 );

function omnikitty_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'omnikitty_excerpt_more' );

/* ==========================================================================
 * Body classes
 * ========================================================================== */

function omnikitty_body_classes( $classes ) {
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }
    if ( is_singular( 'cat' ) ) {
        $classes[] = 'single-cat-page';
    }
    return $classes;
}
add_filter( 'body_class', 'omnikitty_body_classes' );

/* ==========================================================================
 * Activation: flush rewrite rules so /adopt/ works immediately
 * ========================================================================== */

function omnikitty_after_switch_theme() {
    omnikitty_register_cat_post_type();
    omnikitty_register_cat_taxonomies();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'omnikitty_after_switch_theme' );
