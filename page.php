<?php get_header(); ?>

    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
        <?php comments_template('', true); ?>
    <?php endwhile; ?>

<?php get_footer(); ?>