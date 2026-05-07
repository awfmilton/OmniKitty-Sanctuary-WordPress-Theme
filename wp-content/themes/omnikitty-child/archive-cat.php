<?php
/**
 * Cat archive: listing, filtering and pagination.
 *
 * @package OmniKittyChild
 */

get_header();

$current_status = isset( $_GET['adoption_status'] ) ? sanitize_title( wp_unslash( $_GET['adoption_status'] ) ) : '';
$current_breed  = isset( $_GET['breed'] )           ? sanitize_title( wp_unslash( $_GET['breed'] ) )           : '';

$statuses = get_terms( array( 'taxonomy' => 'adoption_status', 'hide_empty' => false ) );
$breeds   = get_terms( array( 'taxonomy' => 'cat_breed',       'hide_empty' => false ) );
?>

<div class="site-container">
    <?php omnikitty_breadcrumbs(); ?>

    <header class="page-hero">
        <h1><?php esc_html_e( 'Adopt a Cat', 'omnikitty-child' ); ?></h1>
        <p><?php esc_html_e( 'Browse our wonderful cats waiting for their forever homes. Filter by adoption status or breed to find your perfect match.', 'omnikitty-child' ); ?></p>
    </header>

    <form class="cat-filters" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'cat' ) ); ?>">
        <div class="form-row">
            <label for="filter-status"><?php esc_html_e( 'Status', 'omnikitty-child' ); ?></label>
            <select name="adoption_status" id="filter-status">
                <option value=""><?php esc_html_e( 'All statuses', 'omnikitty-child' ); ?></option>
                <?php if ( ! is_wp_error( $statuses ) ) : foreach ( $statuses as $term ) : ?>
                    <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_status, $term->slug ); ?>>
                        <?php echo esc_html( $term->name ); ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </div>

        <div class="form-row">
            <label for="filter-breed"><?php esc_html_e( 'Breed', 'omnikitty-child' ); ?></label>
            <select name="breed" id="filter-breed">
                <option value=""><?php esc_html_e( 'All breeds', 'omnikitty-child' ); ?></option>
                <?php if ( ! is_wp_error( $breeds ) ) : foreach ( $breeds as $term ) : ?>
                    <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_breed, $term->slug ); ?>>
                        <?php echo esc_html( $term->name ); ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="button"><?php esc_html_e( 'Filter', 'omnikitty-child' ); ?></button>
            <a class="button button-outline" href="<?php echo esc_url( get_post_type_archive_link( 'cat' ) ); ?>">
                <?php esc_html_e( 'Reset', 'omnikitty-child' ); ?>
            </a>
        </div>
    </form>

    <?php if ( have_posts() ) : ?>
        <div class="post-grid">
            <?php while ( have_posts() ) : the_post();
                $age    = omnikitty_get_cat_field( 'age' );
                $gender = omnikitty_get_cat_field( 'gender' );
                $status = wp_get_post_terms( get_the_ID(), 'adoption_status' );
                $breed  = wp_get_post_terms( get_the_ID(), 'cat_breed' );
                ?>
                <article id="cat-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'cat-thumbnail', array( 'loading' => 'lazy' ) ); ?>
                        <?php else : ?>
                            <img src="https://placekitten.com/600/450" alt="" loading="lazy" />
                        <?php endif; ?>
                    </a>

                    <div class="post-content">
                        <div class="cat-badges">
                            <?php if ( ! is_wp_error( $status ) && $status ) :
                                $status_slug = $status[0]->slug;
                                $class_map = array(
                                    'available-for-adoption' => 'status-available',
                                    'adoption-pending'       => 'status-pending',
                                    'happily-homed'          => 'status-homed',
                                    'not-available'          => 'status-not-available',
                                );
                                $class = isset( $class_map[ $status_slug ] ) ? $class_map[ $status_slug ] : '';
                                ?>
                                <span class="cat-badge <?php echo esc_attr( $class ); ?>">
                                    <?php echo esc_html( $status[0]->name ); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ( ! is_wp_error( $breed ) && $breed ) : ?>
                                <span class="cat-badge"><?php echo esc_html( $breed[0]->name ); ?></span>
                            <?php endif; ?>
                        </div>

                        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                        <div class="post-meta">
                            <?php if ( $age ) : ?><span><?php echo esc_html( $age ); ?></span><?php endif; ?>
                            <?php if ( $gender ) : ?><span><?php echo esc_html( $gender ); ?></span><?php endif; ?>
                        </div>

                        <div class="post-excerpt"><?php the_excerpt(); ?></div>
                        <a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Meet me →', 'omnikitty-child' ); ?></a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php omnikitty_pagination(); ?>

    <?php else : ?>
        <p><?php esc_html_e( 'No cats matched your filters. Try clearing them to see all available cats.', 'omnikitty-child' ); ?></p>
    <?php endif; ?>
</div>

<?php get_footer();
