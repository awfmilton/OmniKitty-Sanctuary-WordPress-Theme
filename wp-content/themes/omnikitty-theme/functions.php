<?php
/**
 * OmniKitty Theme Functions
 *
 * @package OmniKitty
 */

if ( ! function_exists( 'omnikitty_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function omnikitty_setup() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'omnikitty' ),
				'footer'  => esc_html__( 'Footer Menu', 'omnikitty' ),
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'omnikitty_setup' );

/**
 * Register "Cat" Custom Post Type
 */
function omnikitty_register_cat_cpt() {
    $labels = array(
        'name'                  => _x( 'Cats', 'Post Type General Name', 'omnikitty' ),
        'singular_name'         => _x( 'Cat', 'Post Type Singular Name', 'omnikitty' ),
        'menu_name'             => __( 'Cats', 'omnikitty' ),
        'name_admin_bar'        => __( 'Cat', 'omnikitty' ),
        'archives'              => __( 'Cat Archives', 'omnikitty' ),
        'attributes'            => __( 'Cat Attributes', 'omnikitty' ),
        'parent_item_colon'     => __( 'Parent Cat:', 'omnikitty' ),
        'all_items'             => __( 'All Cats', 'omnikitty' ),
        'add_new_item'          => __( 'Add New Cat', 'omnikitty' ),
        'add_new'               => __( 'Add New', 'omnikitty' ),
        'new_item'              => __( 'New Cat', 'omnikitty' ),
        'edit_item'             => __( 'Edit Cat', 'omnikitty' ),
        'update_item'           => __( 'Update Cat', 'omnikitty' ),
        'view_item'             => __( 'View Cat', 'omnikitty' ),
        'view_items'            => __( 'View Cats', 'omnikitty' ),
        'search_items'          => __( 'Search Cat', 'omnikitty' ),
        'not_found'             => __( 'Not found', 'omnikitty' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'omnikitty' ),
        'featured_image'        => __( 'Featured Image', 'omnikitty' ),
        'set_featured_image'    => __( 'Set featured image', 'omnikitty' ),
        'remove_featured_image' => __( 'Remove featured image', 'omnikitty' ),
        'use_featured_image'    => __( 'Use as featured image', 'omnikitty' ),
        'insert_into_item'      => __( 'Insert into cat', 'omnikitty' ),
        'uploaded_to_this_item' => __( 'Uploaded to this cat', 'omnikitty' ),
        'items_list'            => __( 'Cats list', 'omnikitty' ),
        'items_list_navigation' => __( 'Cats list navigation', 'omnikitty' ),
        'filter_items_list'     => __( 'Filter cats list', 'omnikitty' ),
    );
    $args = array(
        'label'                 => __( 'Cat', 'omnikitty' ),
        'description'           => __( 'Post type for cats', 'omnikitty' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => array( 'slug' => 'adopt' ),
    );
    register_post_type( 'cat', $args );
}
add_action( 'init', 'omnikitty_register_cat_cpt', 0 );

/**
 * Register taxonomies for the "Cat" CPT.
 */
function omnikitty_register_taxonomies() {
    // Adoption Status (hierarchical)
    $status_labels = array(
        'name'              => _x( 'Adoption Statuses', 'taxonomy general name', 'omnikitty' ),
        'singular_name'     => _x( 'Adoption Status', 'taxonomy singular name', 'omnikitty' ),
        'search_items'      => __( 'Search Statuses', 'omnikitty' ),
        'all_items'         => __( 'All Statuses', 'omnikitty' ),
        'parent_item'       => __( 'Parent Status', 'omnikitty' ),
        'parent_item_colon' => __( 'Parent Status:', 'omnikitty' ),
        'edit_item'         => __( 'Edit Status', 'omnikitty' ),
        'update_item'       => __( 'Update Status', 'omnikitty' ),
        'add_new_item'      => __( 'Add New Status', 'omnikitty' ),
        'new_item_name'     => __( 'New Status Name', 'omnikitty' ),
        'menu_name'         => __( 'Adoption Status', 'omnikitty' ),
    );
    $status_args = array(
        'hierarchical'      => true,
        'labels'            => $status_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'adoption-status' ),
    );
    register_taxonomy( 'adoption_status', array( 'cat' ), $status_args );

    // Cat Breeds (non-hierarchical)
    $breed_labels = array(
        'name'                       => _x( 'Breeds', 'taxonomy general name', 'omnikitty' ),
        'singular_name'              => _x( 'Breed', 'taxonomy singular name', 'omnikitty' ),
        'search_items'               => __( 'Search Breeds', 'omnikitty' ),
        'popular_items'              => __( 'Popular Breeds', 'omnikitty' ),
        'all_items'                  => __( 'All Breeds', 'omnikitty' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Breed', 'omnikitty' ),
        'update_item'                => __( 'Update Breed', 'omnikitty' ),
        'add_new_item'               => __( 'Add New Breed', 'omnikitty' ),
        'new_item_name'              => __( 'New Breed Name', 'omnikitty' ),
        'separate_items_with_commas' => __( 'Separate breeds with commas', 'omnikitty' ),
        'add_or_remove_items'        => __( 'Add or remove breeds', 'omnikitty' ),
        'choose_from_most_used'      => __( 'Choose from the most used breeds', 'omnikitty' ),
        'not_found'                  => __( 'No breeds found.', 'omnikitty' ),
        'menu_name'                  => __( 'Breeds', 'omnikitty' ),
    );
    $breed_args = array(
        'hierarchical'          => false,
        'labels'                => $breed_labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'breed' ),
    );
    register_taxonomy( 'cat_breed', 'cat', $breed_args );
}
add_action( 'init', 'omnikitty_register_taxonomies', 0 );

/**
 * Adds a meta box to the 'Cat' CPT edit screen.
 */
function omnikitty_add_cat_details_meta_box() {
    add_meta_box(
        'omnikitty_cat_details',          // Unique ID
        'Cat Details',                    // Box title
        'omnikitty_cat_details_html',     // Content callback, must be of type callable
        'cat',                            // Post type
        'normal',                         // Context
        'high'                            // Priority
    );
}
add_action( 'add_meta_boxes', 'omnikitty_add_cat_details_meta_box' );

/**
 * Renders the HTML for the 'Cat Details' meta box.
 *
 * @param WP_Post $post The post object.
 */
function omnikitty_cat_details_html( $post ) {
    $age = get_post_meta( $post->ID, '_cat_age', true );
    $gender = get_post_meta( $post->ID, '_cat_gender', true );
    $special_needs = get_post_meta( $post->ID, '_cat_special_needs', true );

    // Add a nonce field for security
    wp_nonce_field( 'omnikitty_save_cat_details_data', 'omnikitty_cat_details_nonce' );
    ?>
    <p>
        <label for="omnikitty_cat_age"><strong><?php esc_html_e( 'Age', 'omnikitty' ); ?></strong></label><br>
        <input type="text" id="omnikitty_cat_age" name="omnikitty_cat_age" value="<?php echo esc_attr( $age ); ?>" class="widefat">
    </p>
    <p>
        <label for="omnikitty_cat_gender"><strong><?php esc_html_e( 'Gender', 'omnikitty' ); ?></strong></label><br>
        <select id="omnikitty_cat_gender" name="omnikitty_cat_gender" class="widefat">
            <option value="Male" <?php selected( $gender, 'Male' ); ?>><?php esc_html_e( 'Male', 'omnikitty' ); ?></option>
            <option value="Female" <?php selected( $gender, 'Female' ); ?>><?php esc_html_e( 'Female', 'omnikitty' ); ?></option>
        </select>
    </p>
    <p>
        <label for="omnikitty_cat_special_needs"><strong><?php esc_html_e( 'Special Needs', 'omnikitty' ); ?></strong></label><br>
        <textarea id="omnikitty_cat_special_needs" name="omnikitty_cat_special_needs" class="widefat" rows="4"><?php echo esc_textarea( $special_needs ); ?></textarea>
    </p>
    <?php
}

/**
 * Saves the custom meta data for the 'Cat' CPT.
 *
 * @param int $post_id The ID of the post being saved.
 */
function omnikitty_save_cat_details_data( $post_id ) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['omnikitty_cat_details_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['omnikitty_cat_details_nonce'], 'omnikitty_save_cat_details_data' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Sanitize and save the 'Age' field.
    if ( isset( $_POST['omnikitty_cat_age'] ) ) {
        $age = sanitize_text_field( $_POST['omnikitty_cat_age'] );
        update_post_meta( $post_id, '_cat_age', $age );
    }

    // Sanitize and save the 'Gender' field.
    if ( isset( $_POST['omnikitty_cat_gender'] ) ) {
        $gender = sanitize_text_field( $_POST['omnikitty_cat_gender'] );
        update_post_meta( $post_id, '_cat_gender', $gender );
    }

    // Sanitize and save the 'Special Needs' field.
    if ( isset( $_POST['omnikitty_cat_special_needs'] ) ) {
        $special_needs = sanitize_textarea_field( $_POST['omnikitty_cat_special_needs'] );
        update_post_meta( $post_id, '_cat_special_needs', $special_needs );
    }
}
add_action( 'save_post_cat', 'omnikitty_save_cat_details_data' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function omnikitty_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'omnikitty' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'omnikitty' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'omnikitty_widgets_init' );

if ( ! function_exists( 'omnikitty_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function omnikitty_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'omnikitty' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'omnikitty_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function omnikitty_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'omnikitty' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'omnikitty_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function omnikitty_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'omnikitty' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'omnikitty' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'omnikitty' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'omnikitty' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'omnikitty' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'omnikitty' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'omnikitty_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function omnikitty_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'post-thumbnail' ); ?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

/**
 * Handles the contact form submission.
 */
function omnikitty_handle_contact_form() {
    // Verify nonce
    if ( ! isset( $_POST['contact_form_nonce'] ) || ! wp_verify_nonce( $_POST['contact_form_nonce'], 'omnikitty_contact_form_action' ) ) {
        wp_safe_redirect( add_query_arg( 'contact_status', 'nonce_error', wp_get_referer() . '#contact-form' ) );
        exit;
    }

    // Sanitize and validate fields
    $contact_name = isset( $_POST['contact_name'] ) ? sanitize_text_field( $_POST['contact_name'] ) : '';
    $contact_email = isset( $_POST['contact_email'] ) ? sanitize_email( $_POST['contact_email'] ) : '';
    $contact_message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( $_POST['contact_message'] ) : '';

    if ( empty( $contact_name ) || empty( $contact_message ) || ! is_email( $contact_email ) ) {
        wp_safe_redirect( add_query_arg( 'contact_status', 'field_error', wp_get_referer() . '#contact-form' ) );
        exit;
    }

    // Send an email with the form data.
    $admin_email = get_option( 'admin_email' );
    $subject = 'New Contact Form Submission from ' . $contact_name;
    $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $contact_name . ' <' . $contact_email . '>', 'Reply-To: ' . $contact_name . ' <' . $contact_email . '>');
    $body = "You have received a new message from your website contact form.\n\n";
    $body .= "Name: " . $contact_name . "\n";
    $body .= "Email: " . $contact_email . "\n";
    $body .= "Message:\n" . $contact_message . "\n";
    wp_mail( $admin_email, $subject, $body, $headers );

    // Redirect back with a success message
    wp_safe_redirect( add_query_arg( 'contact_status', 'success', wp_get_referer() . '#contact-form' ) );
    exit;
}
add_action( 'admin_post_nopriv_contact_form', 'omnikitty_handle_contact_form' );
add_action( 'admin_post_contact_form', 'omnikitty_handle_contact_form' );

/**
 * Handles the volunteer form submission.
 */
function omnikitty_handle_volunteer_form() {
    // Verify nonce
    if ( ! isset( $_POST['volunteer_form_nonce'] ) || ! wp_verify_nonce( $_POST['volunteer_form_nonce'], 'omnikitty_volunteer_form_action' ) ) {
        wp_safe_redirect( add_query_arg( 'volunteer_status', 'nonce_error', wp_get_referer() . '#volunteer-form' ) );
        exit;
    }

    // Sanitize and validate fields
    $volunteer_name = isset( $_POST['volunteer_name'] ) ? sanitize_text_field( $_POST['volunteer_name'] ) : '';
    $volunteer_email = isset( $_POST['volunteer_email'] ) ? sanitize_email( $_POST['volunteer_email'] ) : '';
    $volunteer_availability = isset( $_POST['volunteer_availability'] ) ? sanitize_textarea_field( $_POST['volunteer_availability'] ) : '';

    if ( empty( $volunteer_name ) || empty( $volunteer_availability ) || ! is_email( $volunteer_email ) ) {
        wp_safe_redirect( add_query_arg( 'volunteer_status', 'field_error', wp_get_referer() . '#volunteer-form' ) );
        exit;
    }

    // Send an email with the form data.
    $admin_email = get_option( 'admin_email' );
    $subject = 'New Volunteer Application from ' . $volunteer_name;
    $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $volunteer_name . ' <' . $volunteer_email . '>', 'Reply-To: ' . $volunteer_name . ' <' . $volunteer_email . '>');
    $body = "You have received a new volunteer application.\n\n";
    $body .= "Name: " . $volunteer_name . "\n";
    $body .= "Email: " . $volunteer_email . "\n";
    $body .= "Availability:\n" . $volunteer_availability . "\n";
    wp_mail( $admin_email, $subject, $body, $headers );

    // Redirect back with a success message
    wp_safe_redirect( add_query_arg( 'volunteer_status', 'success', wp_get_referer() . '#volunteer-form' ) );
    exit;
}
add_action( 'admin_post_nopriv_volunteer_form', 'omnikitty_handle_volunteer_form' );
add_action( 'admin_post_volunteer_form', 'omnikitty_handle_volunteer_form' );

/**
 * Handles the newsletter signup form submission.
 */
function omnikitty_handle_newsletter_signup() {
    // Verify nonce
    if ( ! isset( $_POST['omnikitty_newsletter_nonce'] ) || ! wp_verify_nonce( $_POST['omnikitty_newsletter_nonce'], 'omnikitty_newsletter_signup' ) ) {
        // If nonce fails, redirect back with an error message
        wp_safe_redirect( add_query_arg( 'newsletter_status', 'nonce_error', wp_get_referer() ) );
        exit;
    }

    // Sanitize and validate email
    if ( ! isset( $_POST['newsletter_email'] ) || ! is_email( $_POST['newsletter_email'] ) ) {
        // If email is invalid, redirect back with an error message
        wp_safe_redirect( add_query_arg( 'newsletter_status', 'email_error', wp_get_referer() ) );
        exit;
    }

    $email = sanitize_email( $_POST['newsletter_email'] );

    // In a real-world scenario, you would integrate with a mailing list service API here.
    // For now, we will just email the admin.
    $admin_email = get_option( 'admin_email' );
    $subject = 'New Newsletter Subscription';
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    $body = "A new user has subscribed to the newsletter:\n\nEmail: " . $email;
    wp_mail( $admin_email, $subject, $body, $headers );

    // Redirect back to the previous page with a success message
    wp_safe_redirect( add_query_arg( 'newsletter_status', 'success', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_nopriv_newsletter_signup', 'omnikitty_handle_newsletter_signup' );
add_action( 'admin_post_newsletter_signup', 'omnikitty_handle_newsletter_signup' );

/**
 * Handles the adoption form submission.
 */
function omnikitty_handle_adoption_form() {
    // Verify nonce
    if ( ! isset( $_POST['omnikitty_adoption_nonce'] ) || ! wp_verify_nonce( $_POST['omnikitty_adoption_nonce'], 'omnikitty_adoption_form_nonce' ) ) {
        wp_safe_redirect( add_query_arg( 'adoption_status', 'nonce_error', wp_get_referer() . '#adoption-form-section' ) );
        exit;
    }

    // Check that all required fields are present
    $required_fields = array( 'cat_id', 'adopter_name', 'adopter_email', 'adopter_message' );
    foreach ( $required_fields as $field ) {
        if ( empty( $_POST[ $field ] ) ) {
            wp_safe_redirect( add_query_arg( 'adoption_status', 'field_error', wp_get_referer() . '#adoption-form-section' ) );
            exit;
        }
    }

    // Sanitize form data
    $cat_id = absint( $_POST['cat_id'] );
    $adopter_name = sanitize_text_field( $_POST['adopter_name'] );
    $adopter_email = sanitize_email( $_POST['adopter_email'] );
    $adopter_message = sanitize_textarea_field( $_POST['adopter_message'] );

    // Validate email
    if ( ! is_email( $adopter_email ) ) {
        wp_safe_redirect( add_query_arg( 'adoption_status', 'email_error', wp_get_referer() . '#adoption-form-section' ) );
        exit;
    }

    // At this point, you would typically save the application to the database. For now, we will email it.
    $admin_email = get_option( 'admin_email' );
    $cat_name = get_the_title( $cat_id );
    $subject = 'New Adoption Application for ' . $cat_name;
    $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $adopter_name . ' <' . $adopter_email . '>', 'Reply-To: ' . $adopter_name . ' <' . $adopter_email . '>');
    $body = "You have received a new adoption application for " . $cat_name . ".\n\n";
    $body .= "Applicant Name: " . $adopter_name . "\n";
    $body .= "Applicant Email: " . $adopter_email . "\n";
    $body .= "Message:\n" . $adopter_message . "\n";
    wp_mail( $admin_email, $subject, $body, $headers );

    // Redirect back to the cat's profile page with a success message
    $redirect_url = add_query_arg( 'adoption_status', 'success', get_permalink( $cat_id ) );
    wp_safe_redirect( $redirect_url );
    exit;
}
add_action( 'admin_post_nopriv_adoption_form', 'omnikitty_handle_adoption_form' );
add_action( 'admin_post_adoption_form', 'omnikitty_handle_adoption_form' );

/**
 * Enqueue scripts and styles.
 */
function omnikitty_enqueue_styles() {
	wp_enqueue_style( 'omnikitty-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );

	// Enqueue responsive stylesheet
	wp_enqueue_style( 'omnikitty-responsive', get_template_directory_uri() . '/responsive.css', array( 'omnikitty-style' ), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'omnikitty_enqueue_styles' );
