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
		array(), // No dependencies for now
		wp_get_theme()->get( 'Version' ),
		true // Load in footer
	);
}
add_action( 'wp_enqueue_scripts', 'omnikitty_child_enqueue_assets' );
