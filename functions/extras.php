<?php

/*  ==========================================================================
     COMMENTS
    ==========================================================================  */

/*   Custom Comment Template
    --------------------------------------------------------------------------  */

    function vital_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment; ?>
        <?php if ( $comment->comment_approved == '1' ): ?>
        <li>
            <div id="comment-<?php comment_ID() ?>" class="comment">
                <div class="comment-meta">
                    <?php echo get_avatar( $comment, 96, '', get_comment_author() ); ?>
                    <h4 class="comment-author"><?php comment_author_link() ?></h4>
                    <time class="comment-time"><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
                </div>
                <div class="comment-text">
                    <?php comment_text() ?>
                    <?php comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?>
                </div>
            </div>
        <?php endif;
    }

/*   Custom "Reply" link text
    --------------------------------------------------------------------------  */

    function vital_comment_reply_link($link, $args, $comment){
        $comment = get_comment( $comment );
        // If no comment author is blank, use 'Anonymous'
        if ( empty($comment->comment_author) ) {
            if (!empty($comment->user_id)){
                $user=get_userdata($comment->user_id);
                $author=$user->user_login;
            } else {
                $author = __('Anonymous');
            }
        } else {
            $author = $comment->comment_author;
        }
        // If the user provided more than a first name, use only first name
        if(strpos($author, ' ')){
            $author = substr($author, 0, strpos($author, ' '));
        }
        // Replace Reply Link with "Reply to &lt;Author First Name>"
        $reply_link_text = $args['reply_text'];
        $link = str_replace($reply_link_text, 'Reply to ' . $author, $link);

        return $link;
    }
    add_filter('comment_reply_link', 'vital_comment_reply_link', 10, 3);


/*  ==========================================================================
     VITAL DASHBOARD WIDGETS
    ==========================================================================  */

    function vital_dashboard_widgets() {
         global $wp_meta_boxes;
         wp_add_dashboard_widget( 'dashboard_vital_feed', 'Latest from the Vital Blog', 'dashboard_vital_feed_output' );
    }
    function dashboard_vital_feed_output() {
         echo '<div class="rss-widget">';
         wp_widget_rss_output(array(
              'url' => 'http://vtldesign.com/feed',
              'title' => 'Latest from the Vital Blog',
              'items' => 5,
              'show_summary' => 1,
              'show_author' => 1,
              'show_date' => 1
         ));
         echo "</div>";
    }
    add_action('wp_dashboard_setup', 'vital_dashboard_widgets');


/* ==========================================================================
    SMART EXCERPT
    http://www.distractedbysquirrels.com/blog/wordpress-improved-dynamic-excerpt

    Returns an excerpt which is not longer than the given length and always
    ends with a complete sentence.
   ========================================================================== */

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
            echo apply_filters('the_excerpt',$text . '…');
        }
    }
    function reverse_strrchr($haystack, $needle, $trail) {
        return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) + $trail) : false;
    }


/* ==========================================================================
    HUMAN-FRIENDLY POST DATES

    Prints human friendly dates (ie. "2 days ago") if the post is less than
    one week old. Otherwise, it displays a standard datestamp.
   ========================================================================== */

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
        $humandate = str_replace('mins', 'minutes', $humandate);
        echo $humandate;
    }


/* ==========================================================================
    IS_TREE

    Test if page is parent or ancester of specific page ID.
    USAGE: if ( is_tree('value') ) { ... }
   ========================================================================== */

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


/* ==========================================================================
    HAS_CUSTOM_TAX

    Test for custom taxonomy.
    EXAMPLE: has_custom_tax('taxonomy_name','slug');
    EXAMPLE: has_custom_tax('taxonomy_name', array('slug1', 'slug2', 'slug3'));
   ========================================================================== */

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


/* ==========================================================================
    ADD CUSTOM CLASSES FIELD TO WIDGETS
    http://kucrut.org/add-custom-classes-to-any-widget
   ========================================================================== */

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


/* ==========================================================================
    BREADCRUMBS
   ========================================================================== */

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
?>