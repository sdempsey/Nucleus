<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>

    <title><?php ( is_front_page() ? wp_title() : wp_title( '|', true, 'right' ) ) ?></title>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>