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
	$parent_style_handle = 'omnikitty-style'; // Same handle as in parent theme.
	$parent_theme_version = wp_get_theme('omnikitty-theme')->get('Version');

	// Enqueue parent theme's main stylesheet.
	wp_enqueue_style(
		$parent_style_handle,
		get_template_directory_uri() . '/style.css',
		array(),
		$parent_theme_version
	);

	// Enqueue parent theme's responsive stylesheet.
	wp_enqueue_style(
		'omnikitty-responsive',
		get_template_directory_uri() . '/responsive.css',
		array( $parent_style_handle ),
		$parent_theme_version
	);

	// Enqueue child theme stylesheet.
	wp_enqueue_style(
		'omnikitty-child-style', // new handle
		get_stylesheet_uri(), // This correctly gets the child theme's style.css URL
		array( 'omnikitty-responsive' ), // Depends on parent's responsive styles
		wp_get_theme()->get( 'Version' )
	);

	// Enqueue child theme javascript.
	$js_file = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/js/child-theme.js' : '/js/child-theme.min.js';
	wp_enqueue_script(
		'child-theme-js',
		get_stylesheet_directory_uri() . $js_file,
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
add_action( 'wp_enqueue_scripts', 'omnikitty_child_enqueue_assets', 20 );

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
                update_post_meta( $cat_id, '_cat_age', $cat_data['age'] );
                update_post_meta( $cat_id, '_cat_gender', $cat_data['gender'] );
                update_post_meta( $cat_id, '_cat_special_needs', $cat_data['needs'] );
            }
        }
    }

    // Set the option to prevent this from running again
    update_option( 'omnikitty_content_setup_done', true );
}
add_action( 'after_switch_theme', 'omnikitty_child_setup_theme_content' );

/**
 * Adds the 'loading="lazy"' attribute to all images.
 *
 * @param array $attr Attributes for the image tag.
 * @return array The modified attributes.
 */
function omnikitty_child_add_lazy_loading( $attr ) {
    $attr['loading'] = 'lazy';
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'omnikitty_child_add_lazy_loading', 10, 1 );
