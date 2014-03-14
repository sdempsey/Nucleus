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


/*  Add capabilities to Editors
   -------------------------------------------------------------------------- */

    function add_editor_cap(){
        $role = get_role('editor');
        $role->add_cap('gform_full_access');  // Gravity Forms
        $role->add_cap('edit_theme_options'); // Appearance
    }
    add_action('admin_init','add_editor_cap');


/*  Redirect non-admins to home URL after login
   -------------------------------------------------------------------------- */

    function my_login_redirect( $redirect_to, $request, $user ) {
        if ( is_array( $user->roles ) ) {
            if ( in_array( 'administrator', $user->roles ) )
                return home_url( '/wp-admin/' );
            else
                return home_url();
        }
    }
    add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );


/*  Remove admin menus from sidebar for non-admins

    NOTE: This doesn't block accessing pages directly via URL. Edit capabilities
    to block access completely.
   -------------------------------------------------------------------------- */

   function vtl_remove_menus() {

       // Menu Pages
       // remove_menu_page( 'edit-comments.php' );

       // Submenu Pages
       remove_submenu_page( 'themes.php', 'customize.php' );

   }
   if (!current_user_can('administrator'))
       add_action( 'admin_menu', 'vtl_remove_menus', 999 );


/*  Add/remove admin bar items for non-admins
   -------------------------------------------------------------------------- */

    function custom_admin_bar_render() {
      global $wp_admin_bar;
      // Remove
      $wp_admin_bar->remove_menu('wp-logo');
      $wp_admin_bar->remove_menu('comments');
      //$wp_admin_bar->remove_menu('new-post', 'new-content');
      $wp_admin_bar->remove_menu('my-account');
      // Add
      $wp_admin_bar->add_menu( array(
          'parent' => 'top-secondary',
          'id' => 'log_out',
          'title' => __('Log Out'),
          'href' => wp_logout_url()
      ));
    }
    add_action( 'wp_before_admin_bar_render', 'custom_admin_bar_render' );


/*  Remove meta boxes for non-admins
   -------------------------------------------------------------------------- */

    function remove_meta_boxes() {
      remove_meta_box('commentstatusdiv','page','normal'); // Comments status (discussion)
      remove_meta_box('commentsdiv','page','normal'); // Comments
      remove_meta_box('slugdiv','page','normal'); // Slug
      remove_meta_box('authordiv','page','normal'); // Author
      remove_meta_box('postcustom','page','normal'); // Custom fields (WordPress)
      remove_meta_box('postexcerpt','page','normal'); // Excerpt
      remove_meta_box('trackbacksdiv','page','normal'); // Trackbacks
      remove_meta_box('formatdiv','page','normal'); // Formats
      // remove_meta_box('tagsdiv-post_tag','page','normal'); // Tags
      // remove_meta_box('categorydiv','page','normal'); // Categories
      remove_meta_box('pageparentdiv','page','normal'); // Attributes
    }
    if(!current_user_can('administrator')) {
      add_action('admin_init','remove_meta_boxes'); }


/*  Hide WP update messages for non-admins
   -------------------------------------------------------------------------- */

    if ( !current_user_can('administrator') ) {
        add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) ); }


/*  Hide admin bar search box
   -------------------------------------------------------------------------- */

    function hide_admin_bar_search () { ?>
        <style type="text/css">
        #wpadminbar #adminbarsearch {
            display: none;
        }
        </style>
        <?php
    }
    add_action('admin_head', 'hide_admin_bar_search');
    add_action('wp_head', 'hide_admin_bar_search');


/*  Custom admin footer
   -------------------------------------------------------------------------- */

    function custom_admin_footer_text () {
        echo 'Copyright &copy; '. date("Y") .' '. get_bloginfo('name') .' | Site Design by <a href="http://vtldesign.com" target="_blank">Vital Design</a>';
    }
    add_filter('admin_footer_text', 'custom_admin_footer_text');


/*  Replace "Howdy" text on admin bar
   -------------------------------------------------------------------------- */

    function replace_howdy( $wp_admin_bar ) {
        $my_account=$wp_admin_bar->get_node('my-account');
        $newtitle = str_replace( 'Howdy,', 'Logged in as', $my_account->title );
        $wp_admin_bar->add_node( array(
            'id' => 'my-account',
            'title' => $newtitle,
        ) );
    }
    add_filter( 'admin_bar_menu', 'replace_howdy',25 );
?>