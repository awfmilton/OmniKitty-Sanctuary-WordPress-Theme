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
			<!-- Newsletter signup form placeholder -->
			<h4><?php esc_html_e( 'Sign up for our newsletter', 'omnikitty' ); ?></h4>
			<form>
				<input type="email" placeholder="Your email address">
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
