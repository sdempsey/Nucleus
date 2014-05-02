<?php get_header(); ?>

    <?php if ( have_posts() ): the_post(); ?>

        <h2>Author Archive: <?php echo get_the_author() ; ?></h2>

        <?php if ( get_the_author_meta( 'description' ) ) : ?>

            <?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
            <h3>About <?php echo get_the_author() ; ?></h3>
            <?php the_author_meta( 'description' ); ?>

        <?php endif; ?>

        <?php rewind_posts(); while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php get_template_part( 'parts/meta' ); ?>
            <?php get_template_part( 'parts/excerpt' ); ?>
        </article>

        <?php endwhile; ?>

    <?php else: ?>

        <h2>No posts to display</h2>

    <?php endif; ?>

    <?php pagination(); ?>

<?php get_footer(); ?>