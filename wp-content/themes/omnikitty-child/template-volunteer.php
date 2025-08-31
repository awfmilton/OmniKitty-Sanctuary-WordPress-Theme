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
                // Display feedback messages
                display_form_feedback('volunteer');
                ?>

                <form action="<?php echo esc_url( get_permalink() ); ?>" method="post" id="volunteer-form">
                    <p>
                        <label for="volunteer-name">Name <span class="required">*</span></label>
                        <input type="text" id="volunteer-name" name="volunteer_name" value="<?php echo isset($_POST['volunteer_name']) ? esc_attr($_POST['volunteer_name']) : ''; ?>" required>
                    </p>
                    <p>
                        <label for="volunteer-email">Email <span class="required">*</span></label>
                        <input type="email" id="volunteer-email" name="volunteer_email" value="<?php echo isset($_POST['volunteer_email']) ? esc_attr($_POST['volunteer_email']) : ''; ?>" required>
                    </p>
                    <p>
                        <label for="volunteer-availability">Availability <span class="required">*</span></label>
                        <textarea id="volunteer-availability" name="volunteer_availability" rows="5" required><?php echo isset($_POST['volunteer_availability']) ? esc_textarea($_POST['volunteer_availability']) : ''; ?></textarea>
                    </p>
                    <p>
                        <?php wp_nonce_field( 'volunteer_form_action', 'volunteer_form_nonce' ); ?>
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
