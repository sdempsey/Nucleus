<?php
	if ( ! empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die ('Please do not load this page directly. Thanks!');
?>

<div id="comments" class="comments">
	<?php if ( post_password_required() ) : ?>
	<p>This post is password protected. Enter the password to view comments.</p>
</div>

<?php return; endif; ?>

<?php if ( have_comments() ) : ?>

	<h3 id="comments" class="comments-count"><?php comments_number( '<span>No</span> responses', '<span>One</span> response', _n( '<span>%</span> response', '<span>%</span> responses', get_comments_number() ) );?> to “<?php the_title(); ?>”</h3>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comments-nav-above" class="comments-nav">
		<h4 class="screen-reader-text">Comment Navigation</h4>
		<div class="comments-nav-prev"><?php previous_comments_link( '&larr; Older Comments' ); ?></div>
		<div class="comments-nav-next"><?php next_comments_link( 'Newer Comments &rarr;' ); ?></div>
	</nav>
	<?php endif; ?>

	<ol class="comments-list">
		<?php wp_list_comments( 'type=comment' ); ?>
	</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comments-nav-below" class="comments-nav">
		<h4 class="screen-reader-text">Comment Navigation</h4>
		<div class="comments-nav-prev"><?php previous_comments_link( '&larr; Older Comments' ); ?></div>
		<div class="comments-nav-next"><?php next_comments_link( 'Newer Comments &rarr;' ); ?></div>
	</nav>
	<?php endif; ?>

<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

	<p class="comments-closed">Comments are closed.</p>

<?php endif; ?>

	<?php comment_form(); ?>

</div>