<?php
/**
 * Main template file.
 *
 * Fallback template used whenever a more specific template doesn't apply.
 *
 * @package OmniKitty
 */

get_header(); ?>

<div class="site-container">
    <?php omnikitty_breadcrumbs(); ?>

    <div class="content-area<?php echo is_active_sidebar( 'sidebar-1' ) ? '' : ' full-width'; ?>">
        <div class="main-content">

            <?php if ( is_home() && ! is_front_page() ) : ?>
                <header class="page-header">
                    <h1 class="entry-title"><?php single_post_title(); ?></h1>
                </header>
            <?php elseif ( is_archive() ) : ?>
                <header class="page-header">
                    <h1 class="entry-title"><?php the_archive_title(); ?></h1>
                    <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                </header>
            <?php endif; ?>

            <?php if ( have_posts() ) : ?>

                <div class="post-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                                </a>
                            <?php endif; ?>

                            <div class="post-content">
                                <h2 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <?php if ( 'post' === get_post_type() ) : ?>
                                    <div class="post-meta">
                                        <span class="posted-on"><?php echo esc_html( get_the_date() ); ?></span>
                                        <span class="byline"><?php the_author(); ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="post-excerpt"><?php the_excerpt(); ?></div>

                                <a class="read-more" href="<?php the_permalink(); ?>">
                                    <?php esc_html_e( 'Read more →', 'omnikitty' ); ?>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php omnikitty_pagination(); ?>

            <?php else : ?>

                <article class="no-results">
                    <h2><?php esc_html_e( 'Nothing found', 'omnikitty' ); ?></h2>
                    <p><?php esc_html_e( 'Sorry, no content matched your request. Try a search:', 'omnikitty' ); ?></p>
                    <?php get_search_form(); ?>
                </article>

            <?php endif; ?>

        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer();
