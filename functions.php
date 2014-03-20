<?php
    require_once locate_template('/functions/admin.php');
    require_once locate_template('/functions/cleanup.php');
    require_once locate_template('/functions/extras.php');
    require_once locate_template('/functions/pagination.php');

/* ==========================================================================
    SCRIPTS AND STYLESHEETS
   ========================================================================== */

    function script_enqueuer() {

        wp_enqueue_style( 'reset', get_template_directory_uri() . '/css/reset.css', '1.0', 'screen' );
        wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts.css', '1.0', 'screen' );
        //wp_enqueue_style( 'foundation-grid', get_template_directory_uri() . '/css/foundation-grid.css', '4.0', 'screen' );
        wp_enqueue_style( 'screen', get_stylesheet_uri(), '', '1.0', 'screen' );

        if ( is_singular() ) {
            wp_enqueue_script( 'comment-reply' );
        }

        wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/scripts/libraries/modernizr.js', null, '2.7.1', true );
        wp_enqueue_script( 'site', get_template_directory_uri() . '/scripts/site/main.js', array('jquery'), '1.0', true );
    }
    add_action( 'wp_enqueue_scripts', 'script_enqueuer' );


/*  IE-specific
   -------------------------------------------------------------------------- */

    function add_ie_scripts () {
        global $is_IE;
        if ($is_IE) {
            echo '<!--[if lt IE 9]>'."\n";
            echo '<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>'."\n";
            echo '<![endif]-->'."\n";
        }
    }
    add_action('wp_head', 'add_ie_scripts', 1);


/* ==========================================================================
    IMAGES & MEDIA
   ========================================================================== */

    add_theme_support( 'post-thumbnails' );


/* ==========================================================================
    Oembed object maximum width
   ========================================================================== */

    if ( !isset( $content_width ) )
        $content_width = 660;

/*  Custom image sizes
   -------------------------------------------------------------------------- */

    //add_image_size('your_custom_size', 1000, 500, true);


/*  Human-readable custom image names
   -------------------------------------------------------------------------- */

    //function vtl_custom_sizes( $sizes ) {
    //    return array_merge( $sizes, array(
    //        'your_custom_size' => __('Your Custom Size Name'),
    //    ) );
    //}
    //add_filter( 'image_size_names_choose', 'vtl_custom_sizes' );


/*  JPG compression
   -------------------------------------------------------------------------- */

    function jpeg_custom_quality( $quality ) {
        return 70;
    }
    add_filter( 'jpeg_quality', 'jpeg_custom_quality' );


/* ==========================================================================
    MENUS
   ========================================================================== */

    register_nav_menus(array(
        'main_nav' => 'Main Navigation',
        'footer_nav' => 'Footer Navigation'
    ));


/* ==========================================================================
    WIDGETS
   ========================================================================== */

    //if (function_exists('register_sidebar')) {
    //  register_sidebar(array(
    //      'name' => 'Footer Column 1',
    //      'id'   => 'footer_column1',
    //        'class'         => '',
    //      'description'   => 'These are widgets for Footer Column 1.',
    //      'before_widget' => '<div id="%1$s" class="widget %2$s">',
    //      'after_widget'  => '</div>',
    //      'before_title'  => '<h2>',
    //      'after_title'   => '</h2>'
    //  ));
    //}


/* ==========================================================================
    SHORTCODES
   ========================================================================== */

/*  Email Encode
    http://codex.wordpress.org/Function_Reference/antispambot
   -------------------------------------------------------------------------- */

    function email_encode_function($atts , $content = null){
        if (!is_email ($content))
            return;
        return '<a href="mailto:'.antispambot($content).'">'.antispambot($content).'</a>';
    }
    add_shortcode( 'email','email_encode_function');


    // Simple shortcode
    // function custom_shortcode_x( $atts, $content = null ) {
    //  return '<span class="custom_shortcode_x">' . $content . '</span>';
    // }
    // add_shortcode( 'shortcode-x-slug', 'custom_shortcode_x' );

    // Simple shortcode (allows nesting)
    // function custom_shortcode_y( $atts, $content = null ) {
    //  return '<span class="custom_shortcode_y">' . do_shortcode($content) . '</span>';
    // }
    // add_shortcode( 'shortcode-x-slug', 'custom_shortcode_y' );

    // [bartag foo="foo-value"]
    // function bartag_func( $atts ) {
    //  extract( shortcode_atts( array(
    //      'foo' => 'something',
    //      'bar' => 'something else',
    //  ), $atts ) );
    //  return "{$foo} = {$bar}";
    // }
    // add_shortcode( 'bartag', 'bartag_func' );


/* ==========================================================================
    SITE-SPECIFIC
   ========================================================================== */

/*   Add theme support
   --------------------------------------------------------------------------  */

   function vtl_add_theme_support() {

       // Add featured image support for only specific post types
       add_theme_support( 'post-thumbnails', array( 'post' ) );
   }
   add_action( 'after_setup_theme', 'vtl_add_theme_support', 11 );


/*  Customize login
  -------------------------------------------------------------------------- */

   function custom_login_logo() {
       echo "<style>
       body.login #login h1 a {
           background: url('".get_bloginfo('template_url')."/images/custom-logo.png') no-repeat scroll center top transparent;
           width: 274px;
           height: 63px;
       }
       </style>";
   }
   add_filter('login_headerurl', create_function(false,"return '".home_url()."';")); // Logo link
   add_filter('login_headertitle', create_function(false,"return 'Powered by WordPress';")); // Logo tooltip text
   add_action("login_head", "custom_login_logo");


/* ==========================================================================
    TINYMCE
   ========================================================================== */

/*  Custom Styles
    http://codex.wordpress.org/TinyMCE_Custom_Styles#Style_Format_Arguments
   -------------------------------------------------------------------------- */

    function my_mce_styles( $init_array ) {
        $style_formats = array(
            array(
                'title' => 'Style 1',
                'block' => 'div',
                'classes' => 'style-class-1',
                'wrapper' => true,

            ),
            array(
                'title' => 'Red Button',
                'selector' => 'a',
                'classes' => 'red-button',
                'wrapper' => false,
            )
        );
        $init_array['style_formats'] = json_encode( $style_formats );
        return $init_array;
    }
    add_filter( 'tiny_mce_before_init', 'my_mce_styles' );

/*  Options & Buttons
   -------------------------------------------------------------------------- */

    function custom_tinymce($options) {
        $options['wordpress_adv_hidden'] = false;
        $options['remove_linebreaks'] = false;
        $options['gecko_spellcheck'] = true;
        $options['theme_advanced_more_colors'] = false;
        $options['theme_advanced_more_lists'] = true;
        $options['content_css'] = get_template_directory_uri() . "/css/editor-style.css";

        $options['theme_advanced_buttons1'] = 'bold,italic,underline,sup,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,link,unlink,hr,wp_more,wp_page,fullscreen';
        $options['theme_advanced_buttons2'] = 'formatselect,styleselect,fontselect,fontsizeselect,forecolor,pastetext,pasteword,removeformat,charmap,undo,redo';
        $options['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4,h5,h6,blockquote';
        $options['theme_advanced_fonts'] = 'Helvetica=Helvetica Neue, Helvetica, Arial, sans-serif;'.
                                           'Custom Font=Custom Font, Helvetica Neue, Helvetica, Arial, sans-serif;'.
                                           '';
        $options['theme_advanced_font_sizes'] = '0.75em,0.875em,1em,1.125em,1.25em,1.5em,1.75em,2em';
        $options['theme_advanced_text_colors'] = 'ffffff,ba192c,20b7dd';

        return $options;
    }
    add_filter('tiny_mce_before_init', 'custom_tinymce' );

/*  Advanced Custom Fields WYSIWYG Buttons
   -------------------------------------------------------------------------- */

    function my_toolbars( $toolbars ) {
        $toolbars['Full' ][2] = array('formatselect','styleselect','fontselect', 'fontsizeselect','forecolor','pastetext','pasteword','removeformat','charmap','undo','redo','code' );
        return $toolbars;
    }
    add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
?>