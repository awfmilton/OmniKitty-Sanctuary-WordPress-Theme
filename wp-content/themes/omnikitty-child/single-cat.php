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

                            <a href="#adoption-form-section" class="adopt-me-button" style="display:inline-block; background-color: #ff69b4; color: #fff; padding: 15px 25px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-top: 20px;">
                                <?php esc_html_e( 'Adopt Me!', 'omnikitty-child' ); ?>
                            </a>
                        </div>
                    </div>
                </div><!-- .entry-content -->

            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; // End of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<div id="adoption-form-section" style="padding-top: 20px;">
    <?php
    // Display success or error messages
    if ( isset( $_GET['adoption_status'] ) ) {
        $status = sanitize_key( $_GET['adoption_status'] );

        if ( $status === 'success' && get_transient( 'omnikitty_adoption_success_' . get_the_ID() ) ) {
            echo '<div class="adoption-notice success-notice" style="color: green; border: 1px solid green; padding: 15px; margin-bottom: 20px;">';
            echo '<strong>' . esc_html__( 'Success!', 'omnikitty-child' ) . '</strong><br>';
            echo esc_html( get_transient( 'omnikitty_adoption_success_' . get_the_ID() ) );
            echo '</div>';
            delete_transient( 'omnikitty_adoption_success_' . get_the_ID() );
        } else {
            $error_messages = array(
                'nonce_error' => __( 'Security check failed. Please try again.', 'omnikitty-child' ),
                'field_error' => __( 'Please fill out all required fields.', 'omnikitty-child' ),
                'email_error' => __( 'Please provide a valid email address.', 'omnikitty-child' ),
            );
            if ( array_key_exists( $status, $error_messages ) ) {
                echo '<div class="adoption-notice error-notice" style="color: red; border: 1px solid red; padding: 15px; margin-bottom: 20px;">';
                echo '<strong>' . esc_html__( 'Error!', 'omnikitty-child' ) . '</strong><br>';
                echo esc_html( $error_messages[ $status ] );
                echo '</div>';
            }
        }
    }
    ?>

    <form id="adoption-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
        <h3><?php esc_html_e( 'Interested in Adopting ', 'omnikitty-child' ); ?><?php the_title(); ?>?</h3>
        <p><?php esc_html_e( 'Fill out the form below to start the adoption process.', 'omnikitty-child' ); ?></p>

        <input type="hidden" name="action" value="adoption_form">
        <input type="hidden" name="cat_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
        <?php wp_nonce_field( 'omnikitty_adoption_form_nonce', 'omnikitty_adoption_nonce' ); ?>

        <p>
            <label for="adopter_name" style="display: block; margin-bottom: 5px;"><strong><?php esc_html_e( 'Your Name', 'omnikitty-child' ); ?></strong></label>
            <input type="text" id="adopter_name" name="adopter_name" required style="width: 100%; padding: 8px;">
        </p>
        <p>
            <label for="adopter_email" style="display: block; margin-bottom: 5px;"><strong><?php esc_html_e( 'Your Email', 'omnikitty-child' ); ?></strong></label>
            <input type="email" id="adopter_email" name="adopter_email" required style="width: 100%; padding: 8px;">
        </p>
        <p>
            <label for="adopter_message" style="display: block; margin-bottom: 5px;"><strong><?php esc_html_e( 'Tell us about yourself and your home', 'omnikitty-child' ); ?></strong></label>
            <textarea id="adopter_message" name="adopter_message" rows="5" required style="width: 100%; padding: 8px;"></textarea>
        </p>
        <p>
            <button type="submit" style="background-color: #ff69b4; color: #fff; padding: 15px 25px; border-radius: 5px; text-decoration: none; font-weight: bold; border: none; cursor: pointer;"><?php esc_html_e( 'Submit Application', 'omnikitty-child' ); ?></button>
        </p>
    </form>
</div>

<?php
get_sidebar();
get_footer();
