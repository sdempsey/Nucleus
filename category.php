<?php get_header(); ?>

	<?php if ( have_posts() ): ?>
	<h2>Category Archive: <?php echo single_cat_title( '', false ); ?></h2>

	<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="date"><?php human_friendly_date(); ?></p>
		<?php the_excerpt(); ?>
	</article>
	<?php endwhile; ?>

	<?php else: ?>
	<h2>No posts to display in <?php echo single_cat_title( '', false ); ?></h2>
	<?php endif; ?>

	<?php pagination(); ?>

<?php get_footer(); ?>