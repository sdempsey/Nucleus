<?php

/*	----------------------------------------------------------------------------------------------------
	 ENQUEUE JQUERY FROM GOOGLE CDN WITH FALLBACK TO LOCAL WORDPRESS COPY
	 http://gist.github.com/wpsmith/4083811
	---------------------------------------------------------------------------------------------------- */

function wps_enqueue_jquery() {
	// Setup Google URI, default
	$protocol = ( isset( $_SERVER['HTTPS'] ) && 'on' == $_SERVER['HTTPS'] ) ? 'https' : 'http';

	// Get Latest Version
	// $url      = $protocol . '://code.jquery.com/jquery-latest.min.js';

	// Get Specific Version
	$url = $protocol . '://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js';

	// Setup WordPress URI
	$wpurl = get_bloginfo( 'template_url') . '/scripts/jquery-1.9.1.min.js';

	// Setup version
	$ver = null;

	// Deregister WordPress default jQuery
	wp_deregister_script( 'jquery' );

	// Check transient, if false, set URI to WordPress URI
	delete_transient( 'google_jquery' );

	if ( 'false' == ( $google = get_transient( 'google_jquery' ) ) ) {
		$url = $wpurl;
	}
	// Transient failed
	elseif ( false === $google ) {
		// Ping Google
		$resp = wp_remote_head( $url );

		// Use Google jQuery
		if ( ! is_wp_error( $resp ) && 200 == $resp['response']['code'] ) {
			// Set transient for 5 minutes
			set_transient( 'google_jquery', 'true', 60 * 5 );
		}

		// Use WordPress jQuery
		else {
			// Set transient for 5 minutes
			set_transient( 'google_jquery', 'false', 60 * 5 );

			// Use WordPress URI
			$url = $wpurl;

			// Set jQuery Version, WP standards
			$ver = '1.9.1';
		}
	}

	// Register surefire jQuery
	wp_register_script( 'jquery', $url, array(), $ver, false );

	// Enqueue jQuery
	wp_enqueue_script( 'jquery' );
}

add_action( 'wp_enqueue_scripts', 'wps_enqueue_jquery' );
?>