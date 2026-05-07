<?php
/**
 * Front page template — homepage with hero, stats, featured cats and CTAs.
 *
 * @package OmniKittyChild
 */

get_header(); ?>

<section class="hero" aria-labelledby="hero-title">
    <div class="hero-inner">
        <h1 id="hero-title"><?php esc_html_e( 'Saving Lives, One Cat at a Time', 'omnikitty-child' ); ?></h1>
        <p class="lead"><?php esc_html_e( 'OmniKitty Cat Sanctuary rescues, rehabilitates and rehomes cats in need — and pioneers AI bioacoustics research to give them a louder voice.', 'omnikitty-child' ); ?></p>
        <div class="hero-actions">
            <a class="button" href="<?php echo esc_url( get_post_type_archive_link( 'cat' ) ); ?>">
                <?php esc_html_e( 'Meet the Cats', 'omnikitty-child' ); ?>
            </a>
            <a class="button button-outline" href="<?php echo esc_url( home_url( '/donate/' ) ); ?>">
                <?php esc_html_e( 'Donate', 'omnikitty-child' ); ?>
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="site-container">
        <div class="stats-grid">
            <?php
            $available_count = wp_count_posts( 'cat' )->publish ?? 0;
            $stats = array(
                array( 'number' => '500+', 'label' => __( 'Cats Rescued', 'omnikitty-child' ) ),
                array( 'number' => (string) $available_count, 'label' => __( 'Cats On Site', 'omnikitty-child' ) ),
                array( 'number' => '12', 'label' => __( 'Years Caring', 'omnikitty-child' ) ),
                array( 'number' => '98%', 'label' => __( 'Adoption Success', 'omnikitty-child' ) ),
            );
            foreach ( $stats as $s ) :
                ?>
                <div class="stat fade-in">
                    <span class="stat-number"><?php echo esc_html( $s['number'] ); ?></span>
                    <span class="stat-label"><?php echo esc_html( $s['label'] ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="site-container">
        <header class="section-header">
            <h2><?php esc_html_e( 'Looking for a Forever Home', 'omnikitty-child' ); ?></h2>
            <p><?php esc_html_e( 'Meet a few of the wonderful cats currently waiting for their families.', 'omnikitty-child' ); ?></p>
        </header>

        <div class="post-grid">
            <?php
            $featured = new WP_Query( array(
                'post_type'      => 'cat',
                'posts_per_page' => 3,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'adoption_status',
                        'field'    => 'name',
                        'terms'    => 'Available for Adoption',
                    ),
                ),
            ) );

            if ( ! $featured->have_posts() ) {
                // Fall back to any cats if no statuses are set yet.
                $featured = new WP_Query( array(
                    'post_type'      => 'cat',
                    'posts_per_page' => 3,
                ) );
            }

            if ( $featured->have_posts() ) :
                while ( $featured->have_posts() ) : $featured->the_post();
                    $age    = omnikitty_get_cat_field( 'age' );
                    $gender = omnikitty_get_cat_field( 'gender' );
                    ?>
                    <article id="cat-<?php the_ID(); ?>" <?php post_class( 'fade-in' ); ?>>
                        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'cat-thumbnail', array( 'loading' => 'lazy' ) ); ?>
                            <?php else : ?>
                                <img src="https://placekitten.com/600/450" alt="" loading="lazy" />
                            <?php endif; ?>
                        </a>
                        <div class="post-content">
                            <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="post-meta">
                                <?php if ( $age ) : ?><span><?php echo esc_html( $age ); ?></span><?php endif; ?>
                                <?php if ( $gender ) : ?><span><?php echo esc_html( $gender ); ?></span><?php endif; ?>
                            </div>
                            <div class="post-excerpt"><?php the_excerpt(); ?></div>
                            <a class="read-more" href="<?php the_permalink(); ?>">
                                <?php esc_html_e( 'Meet me →', 'omnikitty-child' ); ?>
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>' . esc_html__( 'No cats available right now — please check back soon!', 'omnikitty-child' ) . '</p>';
            endif;
            ?>
        </div>

        <p class="text-center" style="margin-top: 2rem;">
            <a class="button button-secondary" href="<?php echo esc_url( get_post_type_archive_link( 'cat' ) ); ?>">
                <?php esc_html_e( 'See All Cats', 'omnikitty-child' ); ?>
            </a>
        </p>
    </div>
</section>

<section class="section">
    <div class="site-container">
        <header class="section-header">
            <h2><?php esc_html_e( 'How We Help', 'omnikitty-child' ); ?></h2>
            <p><?php esc_html_e( 'Three pillars that guide everything we do.', 'omnikitty-child' ); ?></p>
        </header>

        <div class="feature-grid">
            <div class="feature fade-in">
                <div class="feature-icon" aria-hidden="true">🏠</div>
                <h3><?php esc_html_e( 'Rescue & Rehome', 'omnikitty-child' ); ?></h3>
                <p><?php esc_html_e( 'We take in cats from shelters, hoarding situations and community rescues, then carefully match them with loving forever homes.', 'omnikitty-child' ); ?></p>
            </div>
            <div class="feature fade-in">
                <div class="feature-icon" aria-hidden="true">🔬</div>
                <h3><?php esc_html_e( 'AI Research', 'omnikitty-child' ); ?></h3>
                <p><?php esc_html_e( 'Our bioacoustics team builds models that interpret feline vocalisations, improving welfare for cats everywhere.', 'omnikitty-child' ); ?></p>
            </div>
            <div class="feature fade-in">
                <div class="feature-icon" aria-hidden="true">🌱</div>
                <h3><?php esc_html_e( 'Sustainable Products', 'omnikitty-child' ); ?></h3>
                <p><?php esc_html_e( 'Every product we sell is responsibly sourced, and 100% of profits fund sanctuary operations.', 'omnikitty-child' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="site-container">
        <header class="section-header">
            <h2><?php esc_html_e( 'Latest from the Sanctuary', 'omnikitty-child' ); ?></h2>
        </header>

        <div class="post-grid">
            <?php
            $recent = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3 ) );
            if ( $recent->have_posts() ) :
                while ( $recent->have_posts() ) : $recent->the_post();
                    ?>
                    <article <?php post_class( 'fade-in' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></a>
                        <?php endif; ?>
                        <div class="post-content">
                            <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="post-meta"><span><?php echo esc_html( get_the_date() ); ?></span></div>
                            <div class="post-excerpt"><?php the_excerpt(); ?></div>
                            <a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more →', 'omnikitty-child' ); ?></a>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer();
