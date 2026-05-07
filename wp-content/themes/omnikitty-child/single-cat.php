<?php
/**
 * Single cat profile page with adoption inquiry form.
 *
 * @package OmniKittyChild
 */

get_header();

while ( have_posts() ) : the_post();
    $age           = omnikitty_get_cat_field( 'age' );
    $gender        = omnikitty_get_cat_field( 'gender' );
    $special_needs = omnikitty_get_cat_field( 'special_needs' );
    $statuses      = wp_get_post_terms( get_the_ID(), 'adoption_status' );
    $breeds        = wp_get_post_terms( get_the_ID(), 'cat_breed' );
    ?>

    <article <?php post_class(); ?> itemscope itemtype="https://schema.org/Product">
        <div class="site-container">
            <?php omnikitty_breadcrumbs(); ?>

            <div class="cat-profile">
                <div class="cat-profile-photo">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'cat-large', array( 'itemprop' => 'image' ) ); ?>
                    <?php else : ?>
                        <img src="https://placekitten.com/1200/800" alt="<?php echo esc_attr( get_the_title() ); ?>" />
                    <?php endif; ?>
                </div>

                <div class="cat-profile-info">
                    <h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1>

                    <div class="cat-badges">
                        <?php if ( ! is_wp_error( $statuses ) && $statuses ) :
                            $status_slug = $statuses[0]->slug;
                            $class_map = array(
                                'available-for-adoption' => 'status-available',
                                'adoption-pending'       => 'status-pending',
                                'happily-homed'          => 'status-homed',
                                'not-available'          => 'status-not-available',
                            );
                            $class = isset( $class_map[ $status_slug ] ) ? $class_map[ $status_slug ] : '';
                            ?>
                            <span class="cat-badge <?php echo esc_attr( $class ); ?>">
                                <?php echo esc_html( $statuses[0]->name ); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <table class="cat-info-table">
                        <tbody>
                            <?php if ( $age ) : ?>
                                <tr><th><?php esc_html_e( 'Age', 'omnikitty-child' ); ?></th><td><?php echo esc_html( $age ); ?></td></tr>
                            <?php endif; ?>
                            <?php if ( $gender ) : ?>
                                <tr><th><?php esc_html_e( 'Gender', 'omnikitty-child' ); ?></th><td><?php echo esc_html( $gender ); ?></td></tr>
                            <?php endif; ?>
                            <?php if ( ! is_wp_error( $breeds ) && $breeds ) : ?>
                                <tr>
                                    <th><?php esc_html_e( 'Breed', 'omnikitty-child' ); ?></th>
                                    <td>
                                        <?php
                                        $names = wp_list_pluck( $breeds, 'name' );
                                        echo esc_html( implode( ', ', $names ) );
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if ( $special_needs ) : ?>
                                <tr>
                                    <th><?php esc_html_e( 'Special needs', 'omnikitty-child' ); ?></th>
                                    <td><?php echo esc_html( $special_needs ); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div itemprop="description"><?php the_content(); ?></div>

                    <p>
                        <a class="button" href="#adopt-<?php the_ID(); ?>">
                            <?php esc_html_e( 'Inquire About Adoption', 'omnikitty-child' ); ?>
                        </a>
                    </p>
                </div>
            </div>

            <section class="adoption-form-wrap" id="adopt-<?php the_ID(); ?>">
                <h2><?php printf( esc_html__( 'Adopt %s', 'omnikitty-child' ), esc_html( get_the_title() ) ); ?></h2>
                <p><?php esc_html_e( 'Tell us a little about yourself and we will be in touch within a few business days.', 'omnikitty-child' ); ?></p>

                <?php omnikitty_render_form_message( 'adoption' ); ?>

                <form method="post" action="" class="omnikitty-form" novalidate>
                    <?php wp_nonce_field( 'omnikitty_form_adoption', 'omnikitty_nonce' ); ?>
                    <input type="hidden" name="omnikitty_form" value="adoption" />
                    <input type="hidden" name="cat_id" value="<?php echo esc_attr( get_the_ID() ); ?>" />
                    <input type="text" name="omnikitty_website" value="" tabindex="-1" autocomplete="off" style="display:none" aria-hidden="true" />

                    <div class="form-row required">
                        <label for="adopt-name"><?php esc_html_e( 'Your name', 'omnikitty-child' ); ?></label>
                        <input type="text" id="adopt-name" name="name" required />
                    </div>
                    <div class="form-row required">
                        <label for="adopt-email"><?php esc_html_e( 'Email', 'omnikitty-child' ); ?></label>
                        <input type="email" id="adopt-email" name="email" required />
                    </div>
                    <div class="form-row">
                        <label for="adopt-phone"><?php esc_html_e( 'Phone', 'omnikitty-child' ); ?></label>
                        <input type="tel" id="adopt-phone" name="phone" />
                    </div>
                    <div class="form-row">
                        <label for="adopt-message"><?php esc_html_e( 'Tell us about your home', 'omnikitty-child' ); ?></label>
                        <textarea id="adopt-message" name="message" rows="5" placeholder="<?php esc_attr_e( 'Other pets, family members, experience with cats, etc.', 'omnikitty-child' ); ?>"></textarea>
                    </div>
                    <button type="submit" class="button">
                        <?php esc_html_e( 'Send Inquiry', 'omnikitty-child' ); ?>
                    </button>
                </form>
            </section>
        </div>
    </article>

<?php endwhile;

get_footer();
