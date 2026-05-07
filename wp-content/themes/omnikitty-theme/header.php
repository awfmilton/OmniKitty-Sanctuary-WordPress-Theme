<?php
/**
 * The header for the OmniKitty theme.
 *
 * @package OmniKitty
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="profile" href="https://gmpg.org/xfn/11" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'omnikitty' ); ?></a>

<header id="masthead" class="site-header" role="banner">
    <div class="site-header-inner">
        <div class="site-branding">
            <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php endif; ?>

            <div>
                <?php if ( is_front_page() && is_home() ) : ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php else : ?>
                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php endif; ?>

                <?php $description = get_bloginfo( 'description', 'display' ); ?>
                <?php if ( $description || is_customize_preview() ) : ?>
                    <p class="site-description"><?php echo esc_html( $description ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'omnikitty' ); ?></span>
            <span aria-hidden="true">☰ <?php esc_html_e( 'Menu', 'omnikitty' ); ?></span>
        </button>

        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary', 'omnikitty' ); ?>">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                ) );
            } else {
                echo '<ul id="primary-menu">';
                echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'omnikitty' ) . '</a></li>';
                $archive = get_post_type_archive_link( 'cat' );
                if ( $archive ) {
                    echo '<li><a href="' . esc_url( $archive ) . '">' . esc_html__( 'Adopt', 'omnikitty' ) . '</a></li>';
                }
                echo '</ul>';
            }
            ?>
        </nav>
    </div>
</header>

<main id="primary" class="site-content">
