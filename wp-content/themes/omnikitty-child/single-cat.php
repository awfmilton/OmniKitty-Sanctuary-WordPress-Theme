<?php
/**
 * The template for displaying single cat profiles.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package OmniKitty
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'cat-profile' ); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <div class="cat-profile-details">

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="cat-photo">
                                <?php the_post_thumbnail( 'large' ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="cat-info">
                            <h2><?php esc_html_e( 'About Me', 'omnikitty-child' ); ?></h2>
                            <?php the_content(); ?>

                            <h3><?php esc_html_e( 'Details', 'omnikitty-child' ); ?></h3>
                            <ul>
                                <?php
                                $age = get_post_meta( get_the_ID(), '_cat_age', true );
                                $gender = get_post_meta( get_the_ID(), '_cat_gender', true );
                                $special_needs = get_post_meta( get_the_ID(), '_cat_special_needs', true );
                                ?>
                                <?php if ( ! empty( $age ) ) : ?>
                                    <li><strong><?php esc_html_e( 'Age:', 'omnikitty-child' ); ?></strong> <?php echo esc_html( $age ); ?></li>
                                <?php endif; ?>

                                <?php if ( ! empty( $gender ) ) : ?>
                                    <li><strong><?php esc_html_e( 'Gender:', 'omnikitty-child' ); ?></strong> <?php echo esc_html( $gender ); ?></li>
                                <?php endif; ?>

                                <?php
                                echo get_the_term_list( get_the_ID(), 'adoption_status', '<li><strong>' . esc_html__( 'Adoption Status:', 'omnikitty-child' ) . '</strong> ', ', ', '</li>' );
                                echo get_the_term_list( get_the_ID(), 'cat_breed', '<li><strong>' . esc_html__( 'Breed:', 'omnikitty-child' ) . '</strong> ', ', ', '</li>' );
                                ?>

                                <?php if ( ! empty( $special_needs ) ) : ?>
                                    <li>
                                        <strong><?php esc_html_e( 'Special Needs:', 'omnikitty-child' ); ?></strong>
                                        <p><?php echo esc_html( $special_needs ); ?></p>
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <a href="#adopt-me-form" class="adopt-me-button" style="display:inline-block; background-color: #ff69b4; color: #fff; padding: 15px 25px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-top: 20px;">
                                <?php esc_html_e( 'Adopt Me!', 'omnikitty-child' ); ?>
                            </a>
                        </div>
                    </div>
                </div><!-- .entry-content -->

            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; // End of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
