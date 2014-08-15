<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>

    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js');})(document.documentElement);</script>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php ( is_front_page() ? wp_title() : wp_title( '|', true, 'right' ) ) ?></title>

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>