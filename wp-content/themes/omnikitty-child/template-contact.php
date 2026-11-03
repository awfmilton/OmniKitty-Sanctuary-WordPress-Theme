<?php
/**
 * Template Name: Contact Page
 *
 * This is the template that displays the contact form.
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
                if ( isset( $_GET['contact_status'] ) ) {
                    $status = sanitize_key( $_GET['contact_status'] );
                    if ( $status === 'success' ) {
                        echo '<div class="form-success" style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px;">Thank you for your message! We will get back to you shortly.</div>';
                    } elseif ( $status === 'field_error' ) {
                        echo '<div class="form-error" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">Please fill in all required fields.</div>';
                    } elseif ( $status === 'nonce_error' ) {
                        echo '<div class="form-error" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">Security check failed. Please try again.</div>';
                    } elseif ( $status === 'mail_error' ) {
                        echo '<div class="form-error" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">There was a problem sending your message. Please try again or contact us directly.</div>';
                    }
                }
                ?>

                <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" id="contact-form">
                    <p>
                        <label for="contact-name">Name <span class="required">*</span></label>
                        <input type="text" id="contact-name" name="contact_name" required>
                    </p>
                    <p>
                        <label for="contact-email">Email <span class="required">*</span></label>
                        <input type="email" id="contact-email" name="contact_email" required>
                    </p>
                    <p>
                        <label for="contact-message">Message <span class="required">*</span></label>
                        <textarea id="contact-message" name="contact_message" rows="5" required></textarea>
                    </p>
                    <p>
                        <input type="hidden" name="action" value="contact_form">
                        <?php wp_nonce_field( 'omnikitty_contact_form_action', 'contact_form_nonce' ); ?>
                        <input type="submit" name="contact_form_submit" value="Send Message">
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
