<?php
/**
 * The template for displaying search results pages
 *
 * @package OmniKitty Child
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <header class="page-header">
        <h1 class="page-title">
            <?php
            /* translators: %s: search query. */
            printf( esc_html__( 'Search Results for: %s', 'omnikitty-child' ), '<span>' . get_search_query() . '</span>' );
            ?>
        </h1>
    </header><!-- .page-header -->

    <?php
    // Breadcrumbs
    if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
    }
    ?>

    <?php if ( have_posts() ) : ?>

        <?php
        // Group results by post type
        $results_by_type = array(
            'post' => array(),
            'page' => array(),
            'cat'  => array(),
        );

        while ( have_posts() ) {
            the_post();
            $results_by_type[ get_post_type() ][] = get_the_ID();
        }
        rewind_posts();
        ?>

        <?php foreach ( $results_by_type as $post_type => $post_ids ) : ?>
            <?php if ( ! empty( $post_ids ) ) : ?>
                <section class="search-results-group">
                    <h2 class="search-results-group-title">
                        <?php
                        $post_type_obj = get_post_type_object( $post_type );
                        echo esc_html( $post_type_obj->labels->name );
                        ?>
                    </h2>

                    <?php
                    $args = array(
                        'post_type' => $post_type,
                        'post__in'  => $post_ids,
                        'orderby'   => 'post__in',
                    );
                    $query = new WP_Query( $args );
                    ?>

                    <?php if ( $query->have_posts() ) : ?>
                        <div class="search-results-grid">
                            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                <?php get_template_part( 'template-parts/content', 'search' ); ?>
                            <?php endwhile; ?>
                        </div>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php
        // Pagination
        the_posts_pagination( array(
            'prev_text' => __( 'Previous', 'omnikitty-child' ),
            'next_text' => __( 'Next', 'omnikitty-child' ),
        ) );
        ?>

    <?php else : ?>

        <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'omnikitty-child' ); ?></p>
        <?php get_search_form(); ?>

    <?php endif; ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
