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
