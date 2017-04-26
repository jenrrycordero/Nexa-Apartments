<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fire_Collection
 */

get_header(); ?>

<?php
if( get_field('layouts') ):
    $layoutIndex = 0;
    while ( has_sub_field('layouts') ) :
        $layoutIndex++;
        include theme_get_template_part("layout/" . get_row_layout(), "layout/404");

    endwhile;
endif;
?>

<?php
get_footer();
