<?php get_header(); ?>

	<?php if ( have_posts() ): the_post(); ?>

	<h2>Author Archive: <?php echo get_the_author() ; ?></h2>

	<?php if ( get_the_author_meta( 'description' ) ) : ?>
		<?php echo get_avatar( get_the_author_meta( 'user_email' ) ); ?>
		<h3>About <?php echo get_the_author() ; ?></h3>
		<?php the_author_meta( 'description' ); ?>
	<?php endif; ?>

	<?php rewind_posts(); while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">
		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<p class="date"><?php human_friendly_date(); ?></p>
		<?php
		if( strpos($post->post_content, '<!--more-->') >= 1 ) {
			the_content('Read more &rarr;');
		} else {
			the_excerpt();
		} ?>
	</article>
	<?php endwhile; ?>

	<?php else: ?>
	<h2>No posts to display for <?php echo get_the_author() ; ?></h2>
	<?php endif; ?>

	<?php pagination(); ?>

<?php get_footer(); ?>