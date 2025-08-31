<?php
/**
 * The template for displaying the homepage
 *
 * @package OmniKitty Child
 */

get_header();
?>

	<main id="main" class="site-main" role="main">

		<!-- Hero Section -->
		<section class="hero-section">
			<div class="hero-content">
				<h1><?php esc_html_e( 'Welcome to OmniKitty', 'omnikitty-child' ); ?></h1>
				<p><?php esc_html_e( 'Our mission is to provide a safe, loving, and permanent home for every cat in our care. We are dedicated to rescuing, rehabilitating, and rehoming cats in need.', 'omnikitty-child' ); ?></p>
			</div>
		</section>

		<!-- Featured Cats Section -->
		<section class="featured-cats">
			<h2 class="section-title"><?php esc_html_e( 'Featured Cats', 'omnikitty-child' ); ?></h2>
			<div class="cat-grid">
				<?php
				$featured_cats_query = new WP_Query(
					array(
						'post_type'      => 'cat',
						'posts_per_page' => 4,
						'no_found_rows'  => true,
					)
				);

				if ( $featured_cats_query->have_posts() ) :
					while ( $featured_cats_query->have_posts() ) :
						$featured_cats_query->the_post();
						?>
						<div class="cat-card">
							<a href="<?php the_permalink(); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="cat-card-image">
										<?php the_post_thumbnail( 'medium' ); ?>
									</div>
								<?php endif; ?>
								<h3 class="cat-card-title"><?php the_title(); ?></h3>
							</a>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<p><?php esc_html_e( 'No featured cats available at the moment. Please check back soon!', 'omnikitty-child' ); ?></p>
					<?php
				endif;
				?>
			</div>
		</section>

		<!-- About Us Section Placeholder -->
		<section class="about-us-placeholder">
			<!-- Content for the "About Us" section will go here. -->
			<h2 class="section-title"><?php esc_html_e( 'About Our Sanctuary', 'omnikitty-child' ); ?></h2>
			<p><?php esc_html_e( 'More information about the sanctuary, our history, and our team will be added here.', 'omnikitty-child' ); ?></p>
		</section>

		<!-- Research Section Placeholder -->
		<section class="research-placeholder">
			<!-- Content for the "Research" section will go here. -->
			<h2 class="section-title"><?php esc_html_e( 'Our Research', 'omnikitty-child' ); ?></h2>
			<p><?php esc_html_e( 'Details about our research initiatives and findings will be presented in this area.', 'omnikitty-child' ); ?></p>
		</section>

		<!-- More sections can be added here as needed -->

	</main><!-- #main -->

<?php
get_footer();
