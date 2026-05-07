<?php
/**
 * Primary sidebar.
 *
 * @package OmniKittyChild
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    // Render a sensible default if no widgets configured.
    ?>
    <aside class="widget-area" role="complementary">
        <section class="widget">
            <h3 class="widget-title"><?php esc_html_e( 'Search', 'omnikitty-child' ); ?></h3>
            <?php get_search_form(); ?>
        </section>

        <section class="widget">
            <h3 class="widget-title"><?php esc_html_e( 'Featured Cats', 'omnikitty-child' ); ?></h3>
            <ul>
                <?php
                $cats = new WP_Query( array(
                    'post_type'      => 'cat',
                    'posts_per_page' => 5,
                ) );
                if ( $cats->have_posts() ) :
                    while ( $cats->have_posts() ) : $cats->the_post();
                        printf(
                            '<li><a href="%s">%s</a></li>',
                            esc_url( get_permalink() ),
                            esc_html( get_the_title() )
                        );
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<li>' . esc_html__( 'No cats yet.', 'omnikitty-child' ) . '</li>';
                endif;
                ?>
            </ul>
        </section>

        <section class="widget">
            <h3 class="widget-title"><?php esc_html_e( 'Recent Stories', 'omnikitty-child' ); ?></h3>
            <ul>
                <?php
                $recent = wp_get_recent_posts( array( 'numberposts' => 5, 'post_status' => 'publish' ) );
                foreach ( $recent as $r ) {
                    printf(
                        '<li><a href="%s">%s</a></li>',
                        esc_url( get_permalink( $r['ID'] ) ),
                        esc_html( $r['post_title'] )
                    );
                }
                ?>
            </ul>
        </section>
    </aside>
    <?php
    return;
}
?>
<aside class="widget-area" role="complementary">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
