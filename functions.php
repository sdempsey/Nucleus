<?php

	require_once locate_template('/functions/admin.php');
	require_once locate_template('/functions/cleanup.php');
	require_once locate_template('/functions/comments.php');
	require_once locate_template('/functions/extras.php');
	require_once locate_template('/functions/jquery.php');
	require_once locate_template('/functions/pagination.php');

/*	----------------------------------------------------------------------------------------------------
	 SCRIPTS AND STYLESHEETS
	---------------------------------------------------------------------------------------------------- */

	function script_enqueuer() {

		wp_enqueue_style( 'screen', get_stylesheet_uri(), '', '1.0', 'screen' );

		wp_enqueue_script( 'site', get_template_directory_uri() . '/scripts/site.js', array('jquery'), '1.0', true );
	}
	add_action( 'wp_enqueue_scripts', 'script_enqueuer' );


	// IE-specific assets
	function add_ie_scripts () {
		global $is_IE;
		if ($is_IE) {
			echo '<!--[if lt IE 9]>'."\n";
			echo '<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>'."\n";
			echo '<![endif]-->'."\n";
		}
	}
	add_action('wp_head', 'add_ie_scripts', 1);

/*	----------------------------------------------------------------------------------------------------
	 IMAGES
	---------------------------------------------------------------------------------------------------- */

	add_theme_support( 'post-thumbnails' );

/*	----------------------------------------------------------------------------------------------------
	 MENUS
	---------------------------------------------------------------------------------------------------- */

	register_nav_menus(array(
		'main_navigation' => 'Main Navigation',
		'footer_navigation' => 'Footer Navigation'
	));

/*	----------------------------------------------------------------------------------------------------
	 CUSTOM POST TYPES
	---------------------------------------------------------------------------------------------------- */

	// require_once locate_template('/functions/custom-post-type.php');

/*	----------------------------------------------------------------------------------------------------
	 WIDGET AREAS
	---------------------------------------------------------------------------------------------------- */

	//if (function_exists('register_sidebar')) {
	//	register_sidebar(array(
	//		'name' => 'Footer Column 1',
	//		'id'   => 'footer_column1',
	//        'class'         => '',
	//		'description'   => 'These are widgets for Footer Column 1.',
	//		'before_widget' => '<div id="%1$s" class="widget %2$s">',
	//		'after_widget'  => '</div>',
	//		'before_title'  => '<h2>',
	//		'after_title'   => '</h2>'
	//	));
	//}

/*	----------------------------------------------------------------------------------------------------
	 CUSTOM SHORTCODES
	---------------------------------------------------------------------------------------------------- */

	require_once locate_template('/functions/shortcodes.php');

	// Email Encode Shortcode
	function email_encode_function( $atts, $content ){
		return '<a href="'.antispambot("mailto:".$content).'">'.antispambot($content).'</a>';
	}
	add_shortcode( 'email-encoder', 'email_encode_function' );

	// Simple shortcode
	// function custom_shortcode_x( $atts, $content = null ) {
	// 	return '<span class="custom_shortcode_x">' . $content . '</span>';
	// }
	// add_shortcode( 'shortcode-x-slug', 'custom_shortcode_x' );

	// Simple shortcode (allows nesting)
	// function custom_shortcode_y( $atts, $content = null ) {
	// 	return '<span class="custom_shortcode_y">' . do_shortcode($content) . '</span>';
	// }
	// add_shortcode( 'shortcode-x-slug', 'custom_shortcode_y' );

	// [bartag foo="foo-value"]
	// function bartag_func( $atts ) {
	// 	extract( shortcode_atts( array(
	// 		'foo' => 'something',
	// 		'bar' => 'something else',
	// 	), $atts ) );
	// 	return "{$foo} = {$bar}";
	// }
	// add_shortcode( 'bartag', 'bartag_func' );


/*	----------------------------------------------------------------------------------------------------
	 SITE-SPECIFIC
	---------------------------------------------------------------------------------------------------- */

	// Set a maximum width for Oembedded objects. Prevents user from busting layout widths.
	if ( !isset( $content_width ) )
		$content_width = 660;

	// Set custom classes on <body>. More useful than .page-id-XX classes
	//function custom_body_classes( $classes ) {
	//     if ( is_page(7) || is_category(5) || is_tag('neat') )
	//          $classes[] = 'neat-stuff';
	//
	//     return $classes;
	//}
	//add_filter( 'body_class', 'custom_body_classes');

/*	----------------------------------------------------------------------------------------------------
	 CUSTOMIZE TINYMCE
	---------------------------------------------------------------------------------------------------- */

	function myformatTinyMCE($in) {
		$in['remove_linebreaks'] = false;
		$in['gecko_spellcheck'] = false;
		$in['keep_styles'] = true;
		$in['accessibility_focus'] = true;
		$in['tabfocus_elements'] = 'major-publishing-actions';
		$in['media_strict'] = false;
		$in['paste_remove_styles'] = false;
		$in['paste_remove_spans'] = false;
		$in['paste_strip_class_attributes'] = 'none';
		$in['paste_text_use_dialog'] = true;
		$in['wpeditimage_disable_captions'] = true;
		$in['plugins'] = 'inlinepopups,tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs';
		$in['content_css'] = get_template_directory_uri() . "/css/editor-style.css";
		$in['wpautop'] = true;
		$in['apply_source_formatting'] = false;
		$in['theme_advanced_buttons1'] = 'formatselect,bold,italic,underline,sub,sup,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,justifyfull,hr,link,unlink,fullscreen,wp_adv';
		$in['theme_advanced_buttons2'] = 'styleselect,fontselect,fontsizeselect,forecolor,pastetext,removeformat,charmap,undo,redo,wp_more,wp_page';
		$in['theme_advanced_buttons3'] = '';
		$in['theme_advanced_buttons4'] = '';
		$in['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4,h5,h6,blockquote';
		$in['theme_advanced_styles'] = "Name of Style=className,Another Style=anotherClassName";
		$in['theme_advanced_fonts'] = 'Helvetica=Helvetica Neue, Helvetica, Arial, sans-serif;'.
									  'Custom Font=Custom Font, Helvetica Neue, Helvetica, Arial, sans-serif;'.
									  '';
		$in['theme_advanced_font_sizes'] = '0.75em,0.875em,1em,1.125em,1.25em,1.5em,1.75em,2em';
		$in['theme_advanced_text_colors'] = 'ba192c,20b7dd';
		$in['theme_advanced_more_colors'] = false;

		return $in;
	}
	add_filter('tiny_mce_before_init', 'myformatTinyMCE' );
?>