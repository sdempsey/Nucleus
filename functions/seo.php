<?php

/*   Add alt tag (post title) to any image that doesn't have one
    --------------------------------------------------------------------------  */

    function vtl_add_alt_tags($content) {
        global $post;
        preg_match_all('/<img (.*?)\/>/', $content, $images);
        if( !is_null($images) ) {
            foreach($images[1] as $index => $value) {
                print_r($images);
                if( preg_match('/alt=""/', $value) ) {
                    $new_img = str_replace('<img', '<img alt="'.$post->post_title.'"', $images[0][$index]);
                    $content = str_replace($images[0][$index], $new_img, $content);
                }
            }
        }
        return $content;
    }
    add_filter('the_content', 'vtl_add_alt_tags', 99999);

?>