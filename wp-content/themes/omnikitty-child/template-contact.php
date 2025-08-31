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
                // Display feedback messages
                display_form_feedback('contact');
                ?>

                <form action="<?php echo esc_url( get_permalink() ); ?>" method="post" id="contact-form">
                    <p>
                        <label for="contact-name">Name <span class="required">*</span></label>
                        <input type="text" id="contact-name" name="contact_name" value="<?php echo isset($_POST['contact_name']) ? esc_attr($_POST['contact_name']) : ''; ?>" required>
                    </p>
                    <p>
                        <label for="contact-email">Email <span class="required">*</span></label>
                        <input type="email" id="contact-email" name="contact_email" value="<?php echo isset($_POST['contact_email']) ? esc_attr($_POST['contact_email']) : ''; ?>" required>
                    </p>
                    <p>
                        <label for="contact-message">Message <span class="required">*</span></label>
                        <textarea id="contact-message" name="contact_message" rows="5" required><?php echo isset($_POST['contact_message']) ? esc_textarea($_POST['contact_message']) : ''; ?></textarea>
                    </p>
                    <p>
                        <?php wp_nonce_field( 'contact_form_action', 'contact_form_nonce' ); ?>
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
