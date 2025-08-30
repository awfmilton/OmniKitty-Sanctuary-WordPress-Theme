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
