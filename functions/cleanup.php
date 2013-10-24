<?php

/*  --------------------------------------------------------------------------------------------------
	 CLEAN UP FRONT-END OUTPUT
	-------------------------------------------------------------------------------------------------- */

	// Clean up wp_head()
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
	}
	add_action('init', 'head_cleanup');


	// Clean up output of stylesheet <link> tags
	function clean_style_tag($input) {
		preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
		// Only display media if it is meaningful
		$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
		return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
	}
	add_filter('style_loader_tag', 'clean_style_tag');


	// Remove search functionality if not needed
	//function fb_filter_query( $query, $error = true ) {
	//	if ( is_search() ) {
	//		$query->is_search = false;
	//		$query->query_vars[s] = false;
	//		$query->query[s] = false;
	//		// to error
	//		if ( $error == true )
	//		$query->is_404 = true;
	//	}
	//}
	//add_action( 'parse_query', 'fb_filter_query' );
	//add_filter( 'get_search_form', create_function( '$a', "return null;" ) );


	// Stop wrapping images in <p>
	function filter_ptags_on_images($content) {
		return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}
	add_filter('the_content', 'filter_ptags_on_images');


	// Clean up shortcode output
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


	/**
	 * Wrap embedded media as suggested by Readability
	 * http://www.readability.com/publishers/guidelines#publisher
	 */
	function embed_wrap($cache, $url, $attr = '', $post_ID = '') {
		return '<div class="entry-content-asset">' . $cache . '</div>';
	}
	add_filter('embed_oembed_html', 'embed_wrap', 10, 4);


	// Remove unnecessary self-closing tags
	function remove_self_closing_tags($input) {
		return str_replace(' />', '>', $input);
	}
	add_filter('get_avatar',          'remove_self_closing_tags'); // <img />
	add_filter('comment_id_fields',   'remove_self_closing_tags'); // <input />
	add_filter('post_thumbnail_html', 'remove_self_closing_tags'); // <img />


	// Add slug to body classes
	function add_slug_to_body_class($classes) {
	    global $post;
	    if (is_home()) {
	        $key = array_search('blog', $classes);
	        if ($key > -1) {
	            unset($classes[$key]);
	        }
	    } elseif (is_page()) {
	        $classes[] = sanitize_html_class($post->post_name);
	    } elseif (is_singular()) {
	        $classes[] = sanitize_html_class($post->post_name);
	    }
	    return $classes;
	}
	add_filter('body_class', 'add_slug_to_body_class');


	// Add body class if sidebar is active
	function add_body_sidebar_class($classes) {
	    if (is_active_sidebar('sidebar')) {
	        $classes[] = 'has-sidebar';
	    }
	    return $classes;
	}
	add_filter('body_class','add_body_sidebar_class');


	// Custom excerpt
	function custom_excerpt_length( $length ) {
		return 40; // number of words
	}
	function custom_excerpt_more($more) {
		global $post;
		return '&hellip; <a class="more" href="'. get_permalink($post->ID) . '">Read more &rarr;</a>';
	}
	add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
	add_filter('excerpt_more', 'custom_excerpt_more');


	// Remove #more jump link on posts
	function remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
	add_filter( 'the_content_more_link', 'remove_more_link_scroll' );


	// Remove width and height from wp-caption
	function fixed_img_caption_shortcode($attr, $content = null) {
		if ( ! isset( $attr['caption'] ) ) {
			if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
				$content = $matches[1];
				$attr['caption'] = trim( $matches[2] );
			}
		}
		$output = apply_filters('img_caption_shortcode', '', $attr, $content);
		if ( $output != '' ) return $output;
		extract(shortcode_atts(array('id' => '','align' => 'alignnone','width' => '','caption' => ''), $attr));
		if ( 1 > (int) $width || empty($caption) ) return $content;
		if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
		return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >' . do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
	}
	add_shortcode('wp_caption', 'fixed_img_caption_shortcode');
	add_shortcode('caption', 'fixed_img_caption_shortcode');


	// Add featured images to RSS feed
	function rss_post_thumbnail($content) {
		global $post;
		if(has_post_thumbnail($post->ID)) {
			$content = '<p>' . get_the_post_thumbnail($post->ID) .
			'</p>' . get_the_content();
		}
		return $content;
	}
	add_filter('the_excerpt_rss', 'rss_post_thumbnail');
	add_filter('the_content_feed', 'rss_post_thumbnail');


	// Add a class to the last post in a loop
	function last_post_class($classes){
		global $wp_query;
		if(($wp_query->current_post+1) == $wp_query->post_count) $classes[] = 'last';
		return $classes;
	}
	add_filter('post_class', 'last_post_class');

/*  --------------------------------------------------------------------------------------------------
	 CLEAN UP BACK-END
	-------------------------------------------------------------------------------------------------- */

	// Remove unnecessary dashboard widgets
	function remove_dashboard_widgets() {
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
		remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
		remove_meta_box('dashboard_primary', 'dashboard', 'normal');
		remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
	}
	add_action('admin_init', 'remove_dashboard_widgets');


/*  --------------------------------------------------------------------------------------------------
	 NICE SEARCH by Mark Jaquith - http://txfx.net/wordpress-plugins/nice-search
	 Redirects search results from /?s=query to /search/query/, converts %20 to +
	-------------------------------------------------------------------------------------------------- */

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