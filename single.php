<?php get_header(); ?>

    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>">
            <h1><?php the_title(); ?></h1>
            <p class="date"><?php human_friendly_date(); ?></p>
            <?php the_content(); ?>

            <?php if ( get_the_author_meta( 'description' ) ) : ?>
                <?php echo get_avatar( get_the_author_meta( 'user_email' ) ); ?>
                <h2>About <?php echo get_the_author() ; ?></h2>
                <?php the_author_meta( 'description' ); ?>
            <?php endif; ?>

        </article>
    <?php endwhile; ?>

<?php get_footer(); ?>