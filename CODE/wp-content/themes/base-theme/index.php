<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 */

get_header();

$postId = get_the_ID();

// Use Home Page Fields for all pages that don't have content
if( !get_field('layouts') )
    $postId = get_option('page_on_front');

if (get_field('layouts', $postId)) {
    $layoutIndex = 0;
    while (has_sub_field('layouts', $postId)) :
        $layoutIndex++;
        include theme_get_template_part("layout/" . get_row_layout(), "layout/404");
    endwhile;
}

get_footer();