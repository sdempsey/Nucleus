<form method="get" id="searchform" class="search-form" action="<?php echo home_url(); ?>" role="search">
    <label for="s" class="search-form-label screen-reader-text"><?php _ex( 'Search', 'assistive text', '_s' ); ?></label>
    <input type="search" class="search-form-field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', '_s' ); ?>" />
    <input type="submit" class="search-form-submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', '_s' ); ?>" />
</form>