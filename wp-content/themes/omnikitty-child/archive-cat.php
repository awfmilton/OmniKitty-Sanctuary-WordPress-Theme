<?php
/**
 * The template for displaying the cat archive.
 *
 * @package OmniKitty Child
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <header class="page-header">
            <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
            <div class="cat-filters">
                <form id="cat-filter-form" action="" method="POST">
                    <div class="filter-group">
                        <label for="adoption_status_filter"><?php _e('Adoption Status', 'omnikitty-child'); ?></label>
                        <select name="adoption_status_filter" id="adoption_status_filter">
                            <option value=""><?php _e('All Statuses', 'omnikitty-child'); ?></option>
                            <?php
                            $terms = get_terms(array('taxonomy' => 'adoption_status', 'hide_empty' => false));
                            foreach ($terms as $term) {
                                echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="cat_breed_filter"><?php _e('Breed', 'omnikitty-child'); ?></label>
                        <select name="cat_breed_filter" id="cat_breed_filter">
                            <option value=""><?php _e('All Breeds', 'omnikitty-child'); ?></option>
                            <?php
                            $terms = get_terms(array('taxonomy' => 'cat_breed', 'hide_empty' => false));
                            foreach ($terms as $term) {
                                echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <?php wp_nonce_field('filter_cats_nonce', 'security'); ?>
                </form>
            </div>
        </header><!-- .page-header -->

        <div id="cat-grid" class="cat-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="cat-card">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="cat-card-image">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="cat-card-content">
                                <h2 class="cat-card-title"><?php the_title(); ?></h2>
                                <p class="cat-card-status">
                                    <?php
                                    $status_terms = get_the_terms(get_the_ID(), 'adoption_status');
                                    if ($status_terms && !is_wp_error($status_terms)) {
                                        echo esc_html($status_terms[0]->name);
                                    }
                                    ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p><?php _e('No cats found matching your criteria.', 'omnikitty-child'); ?></p>
            <?php endif; ?>
        </div><!-- #cat-grid -->

        <?php the_posts_pagination(); ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();
