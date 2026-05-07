<?php
/**
 * Footer for the OmniKitty theme.
 *
 * @package OmniKitty
 */
?>
</main><!-- #primary -->

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-container">
        <div class="footer-widgets">
            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <?php dynamic_sidebar( 'footer-1' ); ?>
            <?php else : ?>
                <section class="footer-widget">
                    <h3><?php bloginfo( 'name' ); ?></h3>
                    <p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
                    <p><?php esc_html_e( 'Saving lives, one cat at a time.', 'omnikitty' ); ?></p>
                </section>
            <?php endif; ?>

            <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                <?php dynamic_sidebar( 'footer-2' ); ?>
            <?php else : ?>
                <section class="footer-widget">
                    <h3><?php esc_html_e( 'Quick Links', 'omnikitty' ); ?></h3>
                    <ul>
                        <?php
                        if ( has_nav_menu( 'footer' ) ) {
                            wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'container'      => false,
                                'items_wrap'     => '%3$s',
                            ) );
                        } else {
                            $links = array(
                                home_url( '/' )           => __( 'Home', 'omnikitty' ),
                                home_url( '/about/' )     => __( 'About Us', 'omnikitty' ),
                                home_url( '/adopt/' )     => __( 'Adopt', 'omnikitty' ),
                                home_url( '/donate/' )    => __( 'Donate', 'omnikitty' ),
                                home_url( '/volunteer/' ) => __( 'Volunteer', 'omnikitty' ),
                                home_url( '/contact/' )   => __( 'Contact', 'omnikitty' ),
                            );
                            foreach ( $links as $url => $label ) {
                                printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
                            }
                        }
                        ?>
                    </ul>
                </section>
            <?php endif; ?>

            <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                <?php dynamic_sidebar( 'footer-3' ); ?>
            <?php else : ?>
                <section class="footer-widget">
                    <h3><?php esc_html_e( 'Connect', 'omnikitty' ); ?></h3>
                    <ul class="social-links" aria-label="<?php esc_attr_e( 'Social media', 'omnikitty' ); ?>">
                        <li><a href="#" aria-label="<?php esc_attr_e( 'Facebook', 'omnikitty' ); ?>">f</a></li>
                        <li><a href="#" aria-label="<?php esc_attr_e( 'Instagram', 'omnikitty' ); ?>">ig</a></li>
                        <li><a href="#" aria-label="<?php esc_attr_e( 'Twitter', 'omnikitty' ); ?>">x</a></li>
                        <li><a href="#" aria-label="<?php esc_attr_e( 'YouTube', 'omnikitty' ); ?>">yt</a></li>
                    </ul>
                </section>
            <?php endif; ?>

            <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                <?php dynamic_sidebar( 'footer-4' ); ?>
            <?php else : ?>
                <section class="footer-widget">
                    <h3><?php esc_html_e( 'Newsletter', 'omnikitty' ); ?></h3>
                    <p><?php esc_html_e( 'Get rescue stories & adoption updates.', 'omnikitty' ); ?></p>
                    <?php omnikitty_render_form_message( 'newsletter' ); ?>
                    <form class="newsletter-form" method="post" action="">
                        <?php wp_nonce_field( 'omnikitty_form_newsletter', 'omnikitty_nonce' ); ?>
                        <input type="hidden" name="omnikitty_form" value="newsletter" />
                        <input type="text" name="omnikitty_website" value="" tabindex="-1" autocomplete="off" style="display:none" aria-hidden="true" />
                        <label class="screen-reader-text" for="newsletter-email"><?php esc_html_e( 'Email address', 'omnikitty' ); ?></label>
                        <input type="email" id="newsletter-email" name="email" placeholder="<?php esc_attr_e( 'you@example.com', 'omnikitty' ); ?>" required />
                        <button type="submit" class="button"><?php esc_html_e( 'Subscribe', 'omnikitty' ); ?></button>
                    </form>
                </section>
            <?php endif; ?>
        </div>

        <div class="site-info">
            <p>
                &copy; <?php echo esc_html( date( 'Y' ) ); ?>
                <?php bloginfo( 'name' ); ?>.
                <?php esc_html_e( 'All rights reserved.', 'omnikitty' ); ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
