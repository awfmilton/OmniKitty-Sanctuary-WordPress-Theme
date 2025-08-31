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
 * Create required pages on theme activation.
 */
function omnikitty_child_create_form_pages() {
    // Check if the "Contact" page exists
    if ( null === get_page_by_title( 'Contact' ) ) {
        wp_insert_post(
            array(
                'post_title'    => 'Contact',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
                'page_template' => 'template-contact.php',
            )
        );
    }

    // Check if the "Volunteer" page exists
    if ( null === get_page_by_title( 'Volunteer' ) ) {
        wp_insert_post(
            array(
                'post_title'    => 'Volunteer',
                'post_content'  => 'Use this page to provide information about volunteering opportunities.',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
                'page_template' => 'template-volunteer.php',
            )
        );
    }
}
add_action( 'after_switch_theme', 'omnikitty_child_create_form_pages' );
