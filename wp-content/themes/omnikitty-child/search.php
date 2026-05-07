<?php
/**
 * Advanced search results — filterable by post type.
 *
 * @package OmniKittyChild
 */

get_header();

$query        = get_search_query();
$current_type = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : '';

$type_labels = array(
    ''     => __( 'All', 'omnikitty-child' ),
    'cat'  => __( 'Cats', 'omnikitty-child' ),
    'post' => __( 'Stories', 'omnikitty-child' ),
    'page' => __( 'Pages', 'omnikitty-child' ),
);
?>

<div class="site-container">
    <?php omnikitty_breadcrumbs(); ?>

    <header class="page-hero">
        <h1><?php printf( esc_html__( 'Search Results for: %s', 'omnikitty-child' ), '<em>' . esc_html( $query ) . '</em>' ); ?></h1>
        <p>
            <?php
            global $wp_query;
            printf(
                esc_html( _n( '%d result found', '%d results found', $wp_query->found_posts, 'omnikitty-child' ) ),
                (int) $wp_query->found_posts
            );
            ?>
        </p>
    </header>

    <?php get_search_form(); ?>

    <nav class="search-filters" aria-label="<?php esc_attr_e( 'Filter results by type', 'omnikitty-child' ); ?>">
        <?php foreach ( $type_labels as $type => $label ) :
            $url = add_query_arg( array( 's' => $query, 'type' => $type ?: false ), home_url( '/' ) );
            $is_active = ( $current_type === $type );
            ?>
            <a class="<?php echo $is_active ? 'active' : ''; ?>" href="<?php echo esc_url( $url ); ?>">
                <?php echo esc_html( $label ); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <?php if ( have_posts() ) : ?>
        <div class="post-grid">
            <?php while ( have_posts() ) : the_post();
                $type_obj   = get_post_type_object( get_post_type() );
                $type_label = $type_obj ? $type_obj->labels->singular_name : '';
                ?>
                <article <?php post_class(); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></a>
                    <?php endif; ?>
                    <div class="post-content">
                        <p class="search-result-meta"><?php echo esc_html( $type_label ); ?></p>
                        <h2 class="post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="post-excerpt"><?php the_excerpt(); ?></div>
                        <a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View →', 'omnikitty-child' ); ?></a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php omnikitty_pagination(); ?>

    <?php else : ?>
        <p><?php esc_html_e( 'No results found. Try different keywords or browse our cats.', 'omnikitty-child' ); ?></p>
        <p><a class="button" href="<?php echo esc_url( get_post_type_archive_link( 'cat' ) ); ?>"><?php esc_html_e( 'See all cats', 'omnikitty-child' ); ?></a></p>
    <?php endif; ?>
</div>

<?php get_footer();
