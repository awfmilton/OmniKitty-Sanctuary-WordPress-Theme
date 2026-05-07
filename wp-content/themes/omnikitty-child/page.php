<?php
/**
 * Generic page template.
 *
 * @package OmniKittyChild
 */

get_header(); ?>

<div class="site-container">
    <?php omnikitty_breadcrumbs(); ?>

    <div class="content-area<?php echo is_active_sidebar( 'sidebar-1' ) && ! is_front_page() ? '' : ' full-width'; ?>">
        <div class="main-content">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="page-hero">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <?php if ( has_excerpt() ) : ?>
                            <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <?php endif; ?>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="page-thumbnail" style="margin-bottom: 2rem;">
                            <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;border-radius:8px;' ) ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        the_content();
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'omnikitty-child' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <?php if ( comments_open() || get_comments_number() ) : ?>
                        <div class="comments-area" style="margin-top: 3rem;">
                            <?php comments_template(); ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endwhile; ?>
        </div>

        <?php if ( is_active_sidebar( 'sidebar-1' ) && ! is_front_page() ) : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>
    </div>
</div>

<?php get_footer();
