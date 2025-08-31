</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<div id="footer-widgets" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'footer-1' ); ?>
			</div>
		<?php endif; ?>

		<nav class="footer-navigation" role="navigation">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_id'        => 'footer-menu',
					'depth'          => 1,
				)
			);
			?>
		</nav>

		<div class="newsletter-signup">
			<h4><?php esc_html_e( 'Sign up for our newsletter', 'omnikitty' ); ?></h4>
			<?php
			// Display success or error messages
			if ( isset( $_GET['newsletter_status'] ) ) {
				$status = sanitize_key( $_GET['newsletter_status'] );
				if ( $status === 'success' && get_transient( 'omnikitty_newsletter_success' ) ) {
					echo '<p class="newsletter-success" style="color: green;">' . esc_html( get_transient( 'omnikitty_newsletter_success' ) ) . '</p>';
					delete_transient( 'omnikitty_newsletter_success' );
				} elseif ( $status === 'nonce_error' ) {
					echo '<p class="newsletter-error" style="color: red;">' . esc_html__( 'Security check failed. Please try again.', 'omnikitty' ) . '</p>';
				} elseif ( $status === 'email_error' ) {
					echo '<p class="newsletter-error" style="color: red;">' . esc_html__( 'Please provide a valid email address.', 'omnikitty' ) . '</p>';
				}
			}
			?>
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="newsletter_signup">
				<?php wp_nonce_field( 'omnikitty_newsletter_signup', 'omnikitty_newsletter_nonce' ); ?>
				<label for="newsletter-email" class="screen-reader-text"><?php esc_html_e( 'Email address', 'omnikitty' ); ?></label>
				<input type="email" id="newsletter-email" name="newsletter_email" placeholder="<?php esc_attr_e( 'Your email address', 'omnikitty' ); ?>" required>
				<button type="submit"><?php esc_html_e( 'Subscribe', 'omnikitty' ); ?></button>
			</form>
		</div>

		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'omnikitty' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'omnikitty' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
			<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'omnikitty' ), 'OmniKitty', '<a href="https://example.com">The OmniKitty Team</a>' );
			?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
