<?php

/*	----------------------------------------------------------------------------------------------------
	 EXTRAS
	---------------------------------------------------------------------------------------------------- */

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