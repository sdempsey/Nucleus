<?php

    /**
     * Smart Excerpt
     * http://www.distractedbysquirrels.com/blog/wordpress-improved-dynamic-excerpt
     *
     * Returns an excerpt which is not longer than the given length and always ends with a complete sentence.
     */
    function vtl_smart_excerpt($length) { // Max excerpt length. Length is set in characters
        global $post;
        $text = $post->post_excerpt;
        if ( '' == $text ) {
            $text = get_the_content('');
            $text = apply_filters('the_content', $text);
            $text = str_replace(']]>', ']]>', $text);
        }
        $text = strip_shortcodes($text); // optional, recommended
        $text = strip_tags($text); // use ' $text = strip_tags($text,'<p><a>'); ' if you want to keep some tags
        if ( empty($length) ) {
            $length = 300;
        }
        $text = substr($text,0,$length);
        $excerpt = reverse_strrchr($text, '.', 1);
        if( $excerpt ) {
            echo apply_filters('the_excerpt',$excerpt);
        } else {
            echo apply_filters('the_excerpt',$text);
        }
    }
    function reverse_strrchr($haystack, $needle, $trail) {
        return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) + $trail) : false;
    }


    /**
     * Human-friendly Post Dates
     *
     * Prints human friendly dates (ie. "2 days ago") if the post is less than 1 week old
     * Otherwise, it displays a standard datestamp
     */
    function human_friendly_date() {
        global $post;
        $today = date("r");
        $postdate = get_the_time('r');
        $difference = round((strtotime($today) - strtotime($postdate))/(24*60*60),0);
            if ($difference >= 7) {
                $humandate = the_time('F j, Y');
            } else {
                $humandate = human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';
            }
        echo $humandate;
    }


    /**
     * IS_TREE TEST
     * is_tree('value');
     */
    function is_tree( $pid ) {
        global $post;
        if ( is_page($pid) )
            return true;
        $anc = get_post_ancestors( $post->ID );
        foreach ( $anc as $ancestor ) {
            if( is_page() && $ancestor == $pid ) {
                return true; }
        }
        return false;
    }


    /**
     * CUSTOM TAXONOMY TEST
     * Example: has_custom_tax('taxonomy_name','slug');
     * Example: has_custom_tax('taxonomy_name', array('slug1', 'slug2', 'slug3'));
     */
    function has_custom_tax($tax, $term, $_post = NULL) {
        if ( !$tax || !$term ) { return FALSE; }
        if ( $_post ) {
            $_post = get_post( $_post );
        } else {
            $_post =& $GLOBALS['post'];
        }
        if ( !$_post ) { return FALSE; }
        $return = is_object_in_term( $_post->ID, $tax, $term );
        if ( is_wp_error( $return ) ) { return FALSE; }
        return $return;
    }


    /**
     * ADD CUSTOM CLASSES FIELD ON WIDGETS
     * http://kucrut.org/add-custom-classes-to-any-widget
     */
    function kc_widget_form_extend( $instance, $widget ) {
        if ( !isset($instance['classes']) )
            $instance['classes'] = null;
        $row = "<p>\n";
        $row .= "\t<label for='widget-{$widget->id_base}-{$widget->number}-classes'>Additional Classes <small>(separate with spaces)</small></label>\n";
        $row .= "\t<input type='text' name='widget-{$widget->id_base}[{$widget->number}][classes]' id='widget-{$widget->id_base}-{$widget->number}-classes' class='widefat' value='{$instance['classes']}'/>\n";
        $row .= "</p>\n";

        echo $row;
        return $instance;
    }
    add_filter('widget_form_callback', 'kc_widget_form_extend', 10, 2);
    function kc_widget_update( $instance, $new_instance ) {
        $instance['classes'] = $new_instance['classes'];
        return $instance;
    }
    add_filter( 'widget_update_callback', 'kc_widget_update', 10, 2 );function kc_dynamic_sidebar_params( $params ) {
        global $wp_registered_widgets;
        $widget_id  = $params[0]['widget_id'];
        $widget_obj = $wp_registered_widgets[$widget_id];
        $widget_opt = get_option($widget_obj['callback'][0]->option_name);
        $widget_num = $widget_obj['params'][0]['number'];

        if ( isset($widget_opt[$widget_num]['classes']) && !empty($widget_opt[$widget_num]['classes']) )
            $params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1 );

        return $params;
    }
    add_filter( 'dynamic_sidebar_params', 'kc_dynamic_sidebar_params' );


    /**
     * BREADCRUMBS
     */
    function vtl_page_breadcrumbs() {
        $delimiter = ':';
        $currentBefore = '<span class="current">';
        $currentAfter = '</span>';
        if ( !is_home() && !is_front_page() || is_paged() ) {
        echo '<div class="breadcrumbs">';
        global $post;
        if ( is_page() && !$post->post_parent ) {
            echo $currentBefore;
            the_title();
            echo $currentAfter; }
        elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $currentBefore;
            the_title();
            echo $currentAfter;
        }
        echo '</div>';
        }
    }

    /**
     * COLOR MY POSTS
     * Color post rows depending on the posts' status
     * http://remicorson.com/color-my-posts
     */
    class rc_color_my_posts {
        function __construct() {
            add_action('admin_footer', array( &$this,'rc_color_my_admin_posts') );
        }
        function rc_color_my_admin_posts(){
            echo "<style>
                /* Color by post Status */
                .status-draft { background: #ffffe0 !important;}
                .status-future { background: #E9F2D3 !important;}
                .status-publish {}
                .status-pending { background: #D3E4ED !important;}
                .status-private { background: #FFECE6 !important;}
                .post-password-required { background: #ff9894 !important;}

                /* Color by author data */
                .author-self {}
                .author-other {}

                /* Color by post format */
                .format-aside {}
                .format-gallery {}
                .format-link {}
                .format-image {}
                .format-quote {}
                .format-status {}
                .format-video {}
                .format-audio {}
                .format-chat {}
                .format-standard {}

                /* Color by post category (change blog by the category slug) */
                .category-blog {}

                /* Color by custom post type (change xxxxx by the custom post type slug) */
                .xxxxx {}
                .type-xxxxx {}

                /* Color by post ID (change xxxxx by the post ID) */
                .post-xxxxx {}

                /* Color by post tag (change xxxxx by the tag slug) */
                .tag-xxxxx {}

                /* Color hAtom compliance */
                .hentry {}

            </style>";
        }
    }
    $GLOBALS['color_my_posts'] = new rc_color_my_posts();
?>