<?php
/**
 * Custom search form.
 *
 * @package OmniKittyChild
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="search-field-<?php echo esc_attr( uniqid() ); ?>" class="screen-reader-text">
        <?php esc_html_e( 'Search for:', 'omnikitty-child' ); ?>
    </label>
    <div style="display:flex;gap:.5rem;">
        <input
            type="search"
            id="search-field-<?php echo esc_attr( uniqid() ); ?>"
            class="search-field"
            placeholder="<?php esc_attr_e( 'Search cats, stories, pages…', 'omnikitty-child' ); ?>"
            value="<?php echo esc_attr( get_search_query() ); ?>"
            name="s"
        />
        <button type="submit" class="button search-submit">
            <span class="screen-reader-text"><?php esc_html_e( 'Search', 'omnikitty-child' ); ?></span>
            <span aria-hidden="true">🔍</span>
        </button>
    </div>
</form>
