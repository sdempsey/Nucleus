<?php get_header(); ?>

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="img-preview">
		<?php
		$attachment_id = $post->ID;
		$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); ?>
		<a href="<?php echo $image_attributes[0]; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>"></a>
	</article>
	<?php endwhile; ?>

<?php get_footer(); ?>