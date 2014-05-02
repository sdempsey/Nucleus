<?php get_header(); ?>

    <?php if ( have_posts() ): ?>

        <h1><?php echo single_cat_title( '', false ); ?></h1>

        <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>">
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php get_template_part( 'parts/meta' ); ?>
            <?php get_template_part( 'parts/excerpt' ); ?>
        </article>

        <?php endwhile; ?>

    <?php else: ?>

        <h2>No posts to display</h2>

    <?php endif; ?>

    <?php pagination(); ?>

<?php get_footer(); ?>