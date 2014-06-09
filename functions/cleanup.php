<?php

/* ==========================================================================
    CLEANUP FRONT-END OUTPUT
   ========================================================================== */

/*  Clean up wp_head()
   -------------------------------------------------------------------------- */

    function head_cleanup() {
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'index_rel_link' );
        remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
        remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
        remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head');
    }
    add_action('init', 'head_cleanup');


/*  Clean up output of stylesheet <link> tags
    (Removes IDs and non-meaningful media attributes)
   -------------------------------------------------------------------------- */

    function clean_style_tag($input) {
        preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
        $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
        return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
    }
    add_filter('style_loader_tag', 'clean_style_tag');


/*   Limit number of post revisions kept
    --------------------------------------------------------------------------  */

    function custom_revisions_number($num, $post) {
        $num = 12;
        return $num;
    }
    add_filter('wp_revisions_to_keep', 'custom_revisions_number', 10, 2);


/*   Remove pages from search results
    --------------------------------------------------------------------------  */

    function search_filter($query) {
        if ($query->is_search) {
            $query->set('post_type', 'post');
        }
        return $query;
    }
    add_filter('pre_get_posts','search_filter');


/*  Stop wrapping images in <p>
   -------------------------------------------------------------------------- */

    function filter_ptags_on_images($content) {
        return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    }
    add_filter('the_content', 'filter_ptags_on_images');


/*  Clean up shortcode output
   -------------------------------------------------------------------------- */

    function clean_shortcodes($content){
        $array = array (
            '<p>[' => '[',
            ']</p>' => ']',
            ']<br />' => ']'
        );
        $content = strtr($content, $array);
        return $content;
    }
    add_filter('the_content', 'clean_shortcodes');


/*  Remove #more jump link on posts
   -------------------------------------------------------------------------- */

    function remove_more_link_scroll( $link ) {
        $link = preg_replace( '|#more-[0-9]+|', '', $link );
        return $link;
    }
    add_filter( 'the_content_more_link', 'remove_more_link_scroll' );


/*  NICE SEARCH by Mark Jaquith
    http://txfx.net/wordpress-plugins/nice-search

    Redirects search results from /?s=query to /search/query/ and converts %20 to +
   -------------------------------------------------------------------------- */

    function cws_nice_search_redirect() {
        global $wp_rewrite;
        if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
            return;

        $search_base = $wp_rewrite->search_base;
        if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
            wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
            exit();
        }
    }
    add_action( 'template_redirect', 'cws_nice_search_redirect' );

    function nice_search_redirect() {
        global $wp_rewrite;
        if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
            return;
        }

        $search_base = $wp_rewrite->search_base;
        if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {
            wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
            exit();
        }
    }
    add_action('template_redirect', 'nice_search_redirect');
?>