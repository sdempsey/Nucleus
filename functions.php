<?php
    require_once locate_template('/functions/admin.php');
    require_once locate_template('/functions/cleanup.php');
    require_once locate_template('/functions/extras.php');
    require_once locate_template('/functions/pagination.php');

/*  ==========================================================================
     SCRIPTS, STYLESHEETS, AND FAVICONS
    ========================================================================== */

    function script_enqueuer() {

        wp_enqueue_style( 'reset', get_template_directory_uri() . '/css/reset.css', '1.0', 'screen' );
        wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts.css', '1.0', 'screen' );
        wp_enqueue_style( 'screen', get_stylesheet_uri(), '', '1.0', 'screen' );

        if ( is_singular() ) {
            wp_enqueue_script( 'comment-reply' );
        }

        wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/scripts/libraries/modernizr.js', null, '2.7.1', true );
        wp_enqueue_script( 'site', get_template_directory_uri() . '/scripts/site/main.js', array('jquery'), '1.0', true );

        // Localize site URLs for use in JavaScripts
        $site_info = array(
            'home_url' => get_home_url(),
            'theme_directory' => get_template_directory_uri()
        );
        wp_localize_script( 'site', 'SiteInfo', $site_info );
    }
    add_action( 'wp_enqueue_scripts', 'script_enqueuer' );


/*  ==========================================================================
     IMAGES & MEDIA
    ========================================================================== */

/*   Oembed object maximum width
    --------------------------------------------------------------------------  */

    if ( !isset( $content_width ) )
        $content_width = 660;

/*   Custom image sizes
    -------------------------------------------------------------------------- */

    //add_image_size('your_custom_size', 1000, 500, true);


/*   Add custom image sizes as choices when inserting media
    -------------------------------------------------------------------------- */

    //function vtl_custom_sizes( $sizes ) {
    //    return array_merge( $sizes, array(
    //        'your_custom_size' => __('Your Custom Size Name'),
    //    ) );
    //}
    //add_filter( 'image_size_names_choose', 'vtl_custom_sizes' );


/*  ==========================================================================
     MENUS
    ========================================================================== */

    register_nav_menus(array(
        'main_nav' => 'Main Navigation',
        'footer_nav' => 'Footer Navigation'
    ));


/*  ==========================================================================
     WIDGETS
    ========================================================================== */


/*  ==========================================================================
     SHORTCODES
    ========================================================================== */


/*  ==========================================================================
     SITE-SPECIFIC CUSTOMIZATIONS
    ========================================================================== */

/*   Customize login
    -------------------------------------------------------------------------- */

    // function custom_login_logo() {
    //     echo "<style>
    //     body.login #login h1 a {
    //          background: url('".get_bloginfo('template_url')."/images/custom-logo.png') no-repeat scroll center top transparent;
    //          width: 274px;
    //          height: 63px;
    //     }
    //     </style>";
    // }
    // add_filter('login_headerurl', create_function(false,"return '".home_url()."';")); // Logo link
    // add_filter('login_headertitle', create_function(false,"return 'Powered by WordPress';")); // Logo tooltip text
    // add_action("login_head", "custom_login_logo");


/*  ==========================================================================
     EDITOR CUSTOMIZATIONS
    ==========================================================================  */

/*   Custom Styles
     http://codex.wordpress.org/TinyMCE_Custom_Styles#Style_Format_Arguments

     Uncomment the following lines to create arrays of custom styles.
     Add "styleselect" to the toolbar arrays to add the dropdown to the editor.
    -------------------------------------------------------------------------- */

    // function custom_tinymce_styles($custom_styles) {
    //     $styles = array(
    //         array(
    //             'title' => 'Text',
    //             'items' => array(
    //                 array(
    //                     'title' => 'Intro Text',
    //                     'selector' => 'p',
    //                     'classes' => 'intro-text',
    //                     'wrapper' => false
    //                     ),
    //                 array(
    //                     'title' => 'Pull Quote',
    //                     'selector' => 'p',
    //                     'classes' => 'pull-quote',
    //                     'wrapper' => false
    //                     )
    //                 )
    //             ),
    //         array(
    //             'title' => 'Buttons',
    //             'items' => array(
    //                 array(
    //                     'title' => 'Red Button',
    //                     'selector' => 'a',
    //                     'classes' => 'red-button',
    //                     'wrapper' => false
    //                     ),
    //                 array(
    //                     'title' => 'Blue Button',
    //                     'selector' => 'a',
    //                     'classes' => 'blue-button',
    //                     'wrapper' => false
    //                     )
    //                 )
    //             ),
    //         array(
    //             'title' => 'Blocks',
    //             'items' => array(
    //                 array(
    //                     'title' => 'Call to Action',
    //                     'block' => 'div',
    //                     'classes' => 'cta',
    //                     'wrapper' => true
    //                     )
    //                 )
    //             )
    //         );

    //     $custom_styles['style_formats'] = json_encode( $styles );
    //     return $custom_styles;
    // }
    // add_filter( 'tiny_mce_before_init', 'custom_tinymce_styles' );


/*   Options & Buttons
    -------------------------------------------------------------------------- */

    function custom_tinymce($options) {
        $options['wordpress_adv_hidden'] = false;
        $options['plugins'] = 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen,hr,charmap,textcolor';
        $options['content_css'] = get_template_directory_uri() . '/css/editor-style.css';

        $options['toolbar1'] = 'bold,italic,underline,superscript,forecolor,alignleft,aligncenter,alignright,outdent,indent,bullist,numlist,hr,link,unlink,wp_more,fullscreen';
        $options['toolbar2'] = 'formatselect,fontsizeselect,pastetext,charmap,removeformat,undo,redo,wp_help';
        $options['block_formats'] = 'Paragraph=p; Blockquote=blockquote; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6';
        $options['fontsize_formats'] = '0.75em 0.875em 1rem 1.125em 1.25em 1.375em 1.5em 1.75em 1.875em 2em';
        $options['color_formats'] = 'ffffff ba192c 20b7dd';

        // Uncomment this if you want to add a font select list.
        // Add "fontselect" to the toolbar arrays to add the dropdown to the editor.
        // $options['font_formats'] = 'Helvetica=Helvetica, Arial, sans-serif;'.
        //                            'Georgia=Georgia, Cambria, Times New Roman, Times, serif;'.
        //                            'Custom Font=Custom Font, Helvetica Neue, Helvetica, Arial, sans-serif';

        return $options;
    }
    add_filter('tiny_mce_before_init', 'custom_tinymce');


/*   Advanced Custom Fields WYSIWYG Buttons
    -------------------------------------------------------------------------- */

    function custom_acf_toolbars($toolbars) {
        $toolbars['Basic' ][1] = array( 'bold,italic,underline,superscript,forecolor,alignleft,aligncenter,alignright,outdent,indent,bullist,numlist,hr,link,unlink,wp_more,fullscreen' );
        $toolbars['Full' ][2] = array('formatselect,fontsizeselect,pastetext,charmap,removeformat,undo,redo,wp_help' );
        return $toolbars;
    }
    add_filter('acf/fields/wysiwyg/toolbars', 'custom_acf_toolbars');
?>