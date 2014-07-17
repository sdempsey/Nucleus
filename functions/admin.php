<?php

/* ==========================================================================
    ADMINISTRATION AND PERMISSIONS
   ========================================================================== */

/*  Sort by PDF in Media Library
   -------------------------------------------------------------------------- */

    function modify_post_mime_types($post_mime_types) {
        $post_mime_types['application/pdf'] = array(__('PDF'), __('Manage PDF'), _n_noop('PDF <span class="count">(%s)</span>', 'PDF <span class="count">(%s)</span>'));
        return $post_mime_types;
    }
    add_filter('post_mime_types', 'modify_post_mime_types');


/*  Redirect non-admins to home URL after login
   -------------------------------------------------------------------------- */

    function my_login_redirect( $redirect_to, $request, $user ) {
        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
            if ( in_array( 'administrator', $user->roles ) )
                return home_url( '/wp-admin/' );
            else
                return home_url();
        }
    }
    add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );


/*  Hide WP update messages for non-admins
   -------------------------------------------------------------------------- */

    if ( !current_user_can('administrator') ) {
        add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) ); }


/*  Custom admin footer
   -------------------------------------------------------------------------- */

    function custom_admin_footer_text () {
        echo 'Copyright &copy; '. date("Y") .' '. get_bloginfo('name') .' | <a href="http://vtldesign.com" target="_blank">Made by Vital</a>';
    }
    add_filter('admin_footer_text', 'custom_admin_footer_text');


/*   Tweak TinyMCE listbox styles
    --------------------------------------------------------------------------  */

    function custom_tinymce_listbox_css() {
       echo '<style type="text/css">
                .mce-btn.mce-listbox {
                    background: transparent !important;
                    border-color: transparent !important;
                    border-radius: 2px !important;
                    -webkit-box-shadow: none !important;
                    box-shadow: none !important;
                }
                .mce-btn.mce-listbox:hover {
                    background: #fafafa !important;
                    border-color: #999 !important;
                    color: #222 !important;
                    -webkit-box-shadow: inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08) !important;
                    box-shadow: inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08) !important;
                }
                .mce-btn.mce-active,
                .mce-btn:active,
                .mce-btn.mce-active:hover {
                    background: #ebebeb !important;
                    border-color: #999 !important;
                    -webkit-box-shadow: inset 0 2px 5px -3px rgba(0,0,0,.3) !important;
                    box-shadow: inset 0 2px 5px -3px rgba(0,0,0,.3) !important;
                }
                .mce-menubtn span {
                    font-size: 13px;
                }
             </style>';
    }
    add_action('admin_head', 'custom_tinymce_listbox_css');
?>