<?php

/*	----------------------------------------------------------------------------------------------------
	 EXTRAS
	---------------------------------------------------------------------------------------------------- */

	/**
	 * Fancy Excerpt
	 * http://www.semiologic.com/software/fancy-excerpt
	 *
	 * Replaces core excerpt and is sentence-aware. No more '...'!
	 */
	load_plugin_textdomain('fancy-excerpt', false, dirname(plugin_basename(__FILE__)) . '/lang');
	class fancy_excerpt {
	    function fancy_excerpt() {
	        remove_filter('get_the_excerpt', 'wp_trim_excerpt');
	        add_filter('get_the_excerpt', array($this, 'trim_excerpt'), 1);
	    }
		function trim_excerpt($text) {
			$text = trim($text);
			if ( $text || !in_the_loop() )
				return wp_trim_excerpt($text);
			$more = sprintf(__('Read more...', 'fancy-excerpt'), get_the_title());
			$text = get_the_content($more);
			$text = str_replace(array("\r\n", "\r"), "\n", $text);
			if ( !preg_match("|" . preg_quote($more, '|') . "</a>$|", $text)
				&& count(preg_split("~\s+~", trim(strip_tags($text)))) > 30
			) {
				global $escape_fancy_excerpt;
				$escape_fancy_excerpt = array();
				$text = fancy_excerpt::escape($text);
				$bits = preg_split("/(<(?:h[1-6]|p|ul|ol|li|dl|dd|table|tr|pre|blockquote)\b[^>]*>|\n{2,})/i", $text, null, PREG_SPLIT_DELIM_CAPTURE);
				$text = '';
				$count = 0;
				foreach ( $bits as $bit ) {
					$text .= $bit;
					$bit_count = trim(strip_tags($bit));
					if ( $bit_count === '' )
						continue;
					$count += count(preg_split("~\s+~", $bit_count));
					if ( $count > 30 )
						break;
				}
				$text = fancy_excerpt::unescape($text);
				$text = force_balance_tags($text);
				$text .= "\n\n"
					. '<p>'
					. apply_filters('the_content_more_link',
						'<a href="'. esc_url(apply_filters('the_permalink', get_permalink())) . '" class="more-link">'
						. $more
						. '</a>')
					. '</p>' . "\n";
			}
	        if ( function_exists('st_add_widget'))
	       	    remove_action('the_content', 'st_add_widget');
			$text = apply_filters('the_content', $text);
			return apply_filters('wp_trim_excerpt', $text, '');
		}
		function escape($text) {
			global $escape_fancy_excerpt;
			if ( !isset($escape_fancy_excerpt) )
				$escape_fancy_excerpt = array();
			foreach ( array(
				'blocks' => "/
					<\s*(script|style|object|textarea)(?:\s.*?)?>
					.*?
					<\s*\/\s*\\1\s*>
					/isx",
				) as $regex ) {
				$text = preg_replace_callback($regex, array($this, 'escape_callback'), $text);
			}
			return $text;
		}
		function escape_callback($match) {
			global $escape_fancy_excerpt;
			$tag_id = "----escape_fancy_excerpt:" . md5($match[0]) . "----";
			$escape_fancy_excerpt[$tag_id] = $match[0];
			return $tag_id;
		}
		function unescape($text) {
			global $escape_fancy_excerpt;
			if ( !$escape_fancy_excerpt )
				return $text;
			$unescape = array_reverse($escape_fancy_excerpt);
			return str_replace(array_keys($unescape), array_values($unescape), $text);
		}
	}
	$fancy_excerpt = new fancy_excerpt();


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


	// Create is_tree test - is_tree('value');
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


	// Create custom taxonomy test
	// Example: has_custom_tax('taxonomy_name','slug');
	// Example: has_custom_tax('taxonomy_name', array('slug1', 'slug2', 'slug3'));
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


	// Add field to widgets for custom classes
	// http://kucrut.org/add-custom-classes-to-any-widget/
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
		$widget_id	= $params[0]['widget_id'];
		$widget_obj	= $wp_registered_widgets[$widget_id];
		$widget_opt	= get_option($widget_obj['callback'][0]->option_name);
		$widget_num	= $widget_obj['params'][0]['number'];

		if ( isset($widget_opt[$widget_num]['classes']) && !empty($widget_opt[$widget_num]['classes']) )
			$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1 );

		return $params;
	}
	add_filter( 'dynamic_sidebar_params', 'kc_dynamic_sidebar_params' );


	// Custom Breadcrumbs
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
			$parent_id	= $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
			$page = get_page($parent_id);
			$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
			$parent_id	= $page->post_parent;
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