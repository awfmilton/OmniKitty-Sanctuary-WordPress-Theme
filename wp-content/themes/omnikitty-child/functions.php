<?php
/**
 * OmniKitty Child Theme Functions
 *
 * @package OmniKitty Child
 */

/**
 * Enqueue scripts and styles.
 */
function omnikitty_child_enqueue_assets() {
	$parent_style = 'parent-style';

	// Enqueue parent theme stylesheet.
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );

	// Enqueue child theme stylesheet.
	wp_enqueue_style(
		'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get( 'Version' )
	);

	// Enqueue child theme javascript.
	wp_enqueue_script(
		'child-theme-js',
		get_stylesheet_directory_uri() . '/js/child-theme.js',
		array( 'jquery' ), // Add jQuery as a dependency
		wp_get_theme()->get( 'Version' ),
		true // Load in footer
	);

	// Localize the script with new data
	wp_localize_script(
		'child-theme-js',
		'omnikitty_ajax_object',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'filter_cats_nonce' ),
            'action'   => 'filter_cats'
		)
	);
}
add_action( 'wp_enqueue_scripts', 'omnikitty_child_enqueue_assets' );

/**
 * AJAX handler for filtering cats.
 */
function omnikitty_filter_cats_handler() {
    check_ajax_referer('filter_cats_nonce', 'security');

    $tax_query = array('relation' => 'AND');

    if (isset($_POST['adoption_status_filter']) && !empty($_POST['adoption_status_filter'])) {
        $tax_query[] = array(
            'taxonomy' => 'adoption_status',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['adoption_status_filter']),
        );
    }

    if (isset($_POST['cat_breed_filter']) && !empty($_POST['cat_breed_filter'])) {
        $tax_query[] = array(
            'taxonomy' => 'cat_breed',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['cat_breed_filter']),
        );
    }

    $args = array(
        'post_type' => 'cat',
        'posts_per_page' => -1,
        'tax_query' => $tax_query,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="cat-card">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="cat-card-image">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="cat-card-content">
                        <h2 class="cat-card-title"><?php the_title(); ?></h2>
                        <p class="cat-card-status">
                            <?php
                            $status_terms = get_the_terms(get_the_ID(), 'adoption_status');
                            if ($status_terms && !is_wp_error($status_terms)) {
                                echo esc_html($status_terms[0]->name);
                            }
                            ?>
                        </p>
                    </div>
                </a>
            </div>
            <?php
        }
    } else {
        echo '<p>' . __('No cats found matching your criteria.', 'omnikitty-child') . '</p>';
    }

    wp_die();
}
add_action('wp_ajax_filter_cats', 'omnikitty_filter_cats_handler');
add_action('wp_ajax_nopriv_filter_cats', 'omnikitty_filter_cats_handler');

/**
 * Modify the main search query to include multiple post types.
 *
 * @param WP_Query $query The main query object.
 */
function omnikitty_child_expand_search_query( $query ) {
    // Check if it's a search query and not in the admin area
    if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
        $query->set( 'post_type', array( 'post', 'page', 'cat' ) );
    }
}
add_action( 'pre_get_posts', 'omnikitty_child_expand_search_query' );

/**
 * Secure Form Handling
 */

// Global variable to store form feedback
$form_feedback = [];

/**
 * Handles the submission of the Contact Form.
 */
function omnikitty_handle_contact_form() {
    global $form_feedback;

    if ( isset( $_POST['contact_form_submit'] ) ) {
        // Verify nonce
        if ( ! isset( $_POST['contact_form_nonce'] ) || ! wp_verify_nonce( $_POST['contact_form_nonce'], 'contact_form_action' ) ) {
            $form_feedback['contact_error'] = 'Nonce verification failed. Please try again.';
            return;
        }

        // Sanitize form fields
        $name = isset( $_POST['contact_name'] ) ? sanitize_text_field( $_POST['contact_name'] ) : '';
        $email = isset( $_POST['contact_email'] ) ? sanitize_email( $_POST['contact_email'] ) : '';
        $message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( $_POST['contact_message'] ) : '';

        // Basic validation
        if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
            $form_feedback['contact_error'] = 'Please fill in all required fields.';
            return;
        }
        if ( ! is_email( $email ) ) {
            $form_feedback['contact_error'] = 'Please provide a valid email address.';
            return;
        }

        // At this point, you would typically send an email.
        // For this implementation, we'll just set a success message.
        // Example: wp_mail( get_option('admin_email'), 'New Contact Form Submission', "Name: $name\nEmail: $email\nMessage: $message" );

        $form_feedback['contact_success'] = 'Thank you for your message! We will get back to you shortly.';

        // Clear the form fields after successful submission
        unset($_POST['contact_name'], $_POST['contact_email'], $_POST['contact_message']);
    }
}
add_action( 'init', 'omnikitty_handle_contact_form' );

/**
 * Handles the submission of the Volunteer Form.
 */
function omnikitty_handle_volunteer_form() {
    global $form_feedback;

    if ( isset( $_POST['volunteer_form_submit'] ) ) {
        // Verify nonce
        if ( ! isset( $_POST['volunteer_form_nonce'] ) || ! wp_verify_nonce( $_POST['volunteer_form_nonce'], 'volunteer_form_action' ) ) {
            $form_feedback['volunteer_error'] = 'Nonce verification failed. Please try again.';
            return;
        }

        // Sanitize form fields
        $name = isset( $_POST['volunteer_name'] ) ? sanitize_text_field( $_POST['volunteer_name'] ) : '';
        $email = isset( $_POST['volunteer_email'] ) ? sanitize_email( $_POST['volunteer_email'] ) : '';
        $availability = isset( $_POST['volunteer_availability'] ) ? sanitize_textarea_field( $_POST['volunteer_availability'] ) : '';

        // Basic validation
        if ( empty( $name ) || empty( $email ) || empty( $availability ) ) {
            $form_feedback['volunteer_error'] = 'Please fill in all required fields.';
            return;
        }
        if ( ! is_email( $email ) ) {
            $form_feedback['volunteer_error'] = 'Please provide a valid email address.';
            return;
        }

        // Set success message
        $form_feedback['volunteer_success'] = 'Thank you for your interest in volunteering! We have received your application.';

        // Clear the form fields
        unset($_POST['volunteer_name'], $_POST['volunteer_email'], $_POST['volunteer_availability']);
    }
}
add_action( 'init', 'omnikitty_handle_volunteer_form' );

/**
 * Helper function to display form feedback.
 * @param string $form_type 'contact' or 'volunteer'
 */
function display_form_feedback( $form_type ) {
    global $form_feedback;
    if ( isset( $form_feedback[ $form_type . '_success' ] ) ) {
        echo '<div class="form-success" style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px;">' . esc_html( $form_feedback[ $form_type . '_success' ] ) . '</div>';
    } elseif ( isset( $form_feedback[ $form_type . '_error' ] ) ) {
        echo '<div class="form-error" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">' . esc_html( $form_feedback[ $form_type . '_error' ] ) . '</div>';
    }
}

/**
 * Sets up the theme by creating initial content, pages, and terms.
 *
 * This function is hooked into `after_switch_theme` and runs only once.
 */
function omnikitty_child_setup_theme_content() {
    // Check if the setup has already been run
    if ( get_option( 'omnikitty_content_setup_done' ) ) {
        return;
    }

    // Create taxonomy terms
    $taxonomies = array(
        'adoption_status' => array( 'Available', 'Pending', 'Adopted' ),
        'cat_breed'       => array( 'Domestic Shorthair', 'Siamese', 'Tabby', 'Maine Coon' ),
    );

    foreach ( $taxonomies as $taxonomy => $terms ) {
        foreach ( $terms as $term_name ) {
            if ( ! term_exists( $term_name, $taxonomy ) ) {
                wp_insert_term( $term_name, $taxonomy );
            }
        }
    }

    // Create pages
    $pages = array(
        'Homepage'   => array(
            'content'   => 'Welcome to the OmniKitty Sanctuary! Our mission is to provide a safe and caring environment for every cat.',
            'template'  => 'front-page.php',
        ),
        'About Us'   => array( 'content' => 'Learn more about our history, our team, and our mission.' ),
        'Research'   => array( 'content' => 'Discover the latest in feline research and care techniques.' ),
        'Products'   => array( 'content' => 'Browse our selection of cat care products.' ),
        'Donate'     => array( 'content' => 'Your generous donations help us care for our cats. Thank you for your support!' ),
        'Volunteer'  => array(
            'content'   => 'Join our team of dedicated volunteers and make a difference.',
            'template'  => 'template-volunteer.php',
        ),
        'Contact'    => array(
            'content'   => 'Get in touch with us for any inquiries.',
            'template'  => 'template-contact.php',
        ),
    );

    foreach ( $pages as $title => $data ) {
        if ( null === get_page_by_title( $title ) ) {
            $post_id = wp_insert_post( array(
                'post_title'    => $title,
                'post_content'  => $data['content'],
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
            ) );

            if ( $post_id && isset( $data['template'] ) ) {
                update_post_meta( $post_id, '_wp_page_template', $data['template'] );
            }

            if ( 'Homepage' === $title ) {
                update_option( 'show_on_front', 'page' );
                update_option( 'page_on_front', $post_id );
            }
        }
    }

    // Create sample blog posts
    $posts = array(
        'The Benefits of a Raw Food Diet for Cats' => 'Exploring the advantages of raw feeding for feline health.',
        'Understanding Feline Body Language' => 'A guide to interpreting your cat\'s behavior.',
        'Creating a Cat-Friendly Home Environment' => 'Tips for making your home a paradise for your pets.',
    );

    foreach ( $posts as $title => $content ) {
        if ( null === get_page_by_title( $title, OBJECT, 'post' ) ) {
            wp_insert_post( array(
                'post_title'    => $title,
                'post_content'  => $content,
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'post',
            ) );
        }
    }

    // Create sample cats
    $cats = array(
        array(
            'title'   => 'Whiskers',
            'content' => 'A friendly and playful tabby looking for a forever home.',
            'age'     => '2',
            'gender'  => 'Male',
            'needs'   => 'None',
            'status'  => 'Available',
            'breed'   => 'Tabby',
        ),
        array(
            'title'   => 'Shadow',
            'content' => 'A shy but sweet siamese who loves quiet afternoons.',
            'age'     => '4',
            'gender'  => 'Female',
            'needs'   => 'Requires a quiet home with no other pets.',
            'status'  => 'Available',
            'breed'   => 'Siamese',
        ),
        array(
            'title'   => 'Leo',
            'content' => 'A majestic Maine Coon with a gentle heart.',
            'age'     => '3',
            'gender'  => 'Male',
            'needs'   => 'Regular grooming to maintain his beautiful coat.',
            'status'  => 'Pending',
            'breed'   => 'Maine Coon',
        ),
    );

    foreach ( $cats as $cat_data ) {
        if ( null === get_page_by_title( $cat_data['title'], OBJECT, 'cat' ) ) {
            $cat_id = wp_insert_post( array(
                'post_title'    => $cat_data['title'],
                'post_content'  => $cat_data['content'],
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'cat',
            ) );

            if ( $cat_id ) {
                // Assign taxonomies
                wp_set_object_terms( $cat_id, $cat_data['status'], 'adoption_status' );
                wp_set_object_terms( $cat_id, $cat_data['breed'], 'cat_breed' );

                // Add custom fields
                update_post_meta( $cat_id, 'age', $cat_data['age'] );
                update_post_meta( $cat_id, 'gender', $cat_data['gender'] );
                update_post_meta( $cat_id, 'special_needs', $cat_data['needs'] );
            }
        }
    }

    // Set the option to prevent this from running again
    update_option( 'omnikitty_content_setup_done', true );
}
add_action( 'after_switch_theme', 'omnikitty_child_setup_theme_content' );
