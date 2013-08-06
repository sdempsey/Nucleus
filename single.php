<?php get_header(); ?>

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
		<p class="date"><?php human_friendly_date(); ?></p>
		<?php the_content(); ?>

		<?php if ( get_the_author_meta( 'description' ) ) : ?>
			<?php echo get_avatar( get_the_author_meta( 'user_email' ) ); ?>
			<h3>About <?php echo get_the_author() ; ?></h3>
			<?php the_author_meta( 'description' ); ?>
		<?php endif; ?>

		<?php if ( comments_open() || '0' != get_comments_number() ) comments_template( '', true ); ?>
	</article>
	<?php endwhile; ?>

<?php get_footer(); ?>