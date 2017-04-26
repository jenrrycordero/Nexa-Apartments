<?php
/**
 * Template Name: Layout base
 */

get_header();

if (have_rows('layouts', $postId))
    include theme_get_template_part("layout/hero_banner", "layout/404");

get_footer();
