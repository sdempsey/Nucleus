<?php get_header(); ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php if(is_sticky()) { echo 'class="sticky-post"';}?>>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php
            if( strpos($post->post_content, '<!--more-->') >= 1 ) {
                the_content('Read more &rarr;');
            } else {
                vtl_smart_excerpt(200);
            } ?>
        </article>
    <?php endwhile; ?>

    <?php else: ?>
        <h2>No posts to display</h2>
    <?php endif; ?>

    <?php pagination(); ?>

<?php get_footer(); ?>