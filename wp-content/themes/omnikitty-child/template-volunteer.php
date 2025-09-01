<?php
/**
 * Template Name: Volunteer Page
 *
 * This is the template that displays the volunteer application form.
 *
 * @package OmniKitty
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php
                // Display feedback messages from URL query parameters
                if ( isset( $_GET['volunteer_status'] ) ) {
                    $status = sanitize_key( $_GET['volunteer_status'] );
                    if ( $status === 'success' ) {
                        echo '<div class="form-success" style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px;">Thank you for your application! We will be in touch soon.</div>';
                    } elseif ( $status === 'field_error' ) {
                        echo '<div class="form-error" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">Please fill in all required fields.</div>';
                    } elseif ( $status === 'nonce_error' ) {
                        echo '<div class="form-error" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">Security check failed. Please try again.</div>';
                    }
                }
                ?>

                <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" id="volunteer-form">
                    <p>
                        <label for="volunteer-name">Name <span class="required">*</span></label>
                        <input type="text" id="volunteer-name" name="volunteer_name" required>
                    </p>
                    <p>
                        <label for="volunteer-email">Email <span class="required">*</span></label>
                        <input type="email" id="volunteer-email" name="volunteer_email" required>
                    </p>
                    <p>
                        <label for="volunteer-availability">Availability <span class="required">*</span></label>
                        <textarea id="volunteer-availability" name="volunteer_availability" rows="5" required></textarea>
                    </p>
                    <p>
                        <input type="hidden" name="action" value="volunteer_form">
                        <?php wp_nonce_field( 'omnikitty_volunteer_form_action', 'volunteer_form_nonce' ); ?>
                        <input type="submit" name="volunteer_form_submit" value="Submit Application">
                    </p>
                </form>

                <?php
                // Display page content if any
                the_content();
                ?>
            </div><!-- .entry-content -->
        </article><!-- #post-## -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
