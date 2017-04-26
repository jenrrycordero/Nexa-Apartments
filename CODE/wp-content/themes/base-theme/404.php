<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Fire_Collection
 */

get_header();

// Use Home Page Fields for all pages that don't have content
$postId = get_option('page_on_front');

if( get_field('layouts', $postId) ):
    $layoutIndex = 0;
    while ( has_sub_field('layouts', $postId) ) :
        $layoutIndex++;
        include theme_get_template_part("layout/404");

    endwhile;
endif;

get_footer();
