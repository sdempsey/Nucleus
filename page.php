<?php get_header(); ?>

    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
        <?php if ( comments_open() || '0' != get_comments_number() ) comments_template( '', true ); ?>
    <?php endwhile; ?>

<?php get_footer(); ?>