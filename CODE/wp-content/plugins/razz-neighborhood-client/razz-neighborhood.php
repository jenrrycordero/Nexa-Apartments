<?php
/*
Plugin Name: Razz Neighborhood Client
Plugin URI: http://razzinteractive.com
Description: Client Plugin to show property neighborhood <a href="https://docs.google.com/document/d/1l-fBE3Ld4thUdTxVVkVh1N-5QYRAw10fzN2ZWwhqGYA/edit?usp=sharing" target="_blank"><strong>Razz Tool Documentation</strong></a>
Version: 1.0.1
Author: Igor & Yerays - @razzinteractive.com
Author URI: http://razzinteractive.com
License: GPLv2 or later
*/


function neighborhood_shortcode($atts)
{

    $atts = (is_array($atts)) ? $atts : array();
    $NBHDToolDefaultAttrs = array(
        'id' => 0,
        'height' => 510, //pixels
        'fullscreen' => false, //pixels
    );

    $NBHDToolAttrs = shortcode_atts($NBHDToolDefaultAttrs, $atts);
    $mapHeight = $NBHDToolAttrs['height'];
    $isFullScreen = $NBHDToolAttrs['fullscreen'];

    $iframe_url = 'http://apt.razzinteractive.com/neighborhood-tool/wp-content/plugins/razz-neighborhood-master/';
    // Parameters as array of key => value pairs
    $iframe_url = add_query_arg($NBHDToolAttrs, $iframe_url);

    $style = '';
    if ($isFullScreen == 'true') {
        $style = '<style>.razz-neighborhood-widget iframe {height: 100vh;}</style>';
    }

    return '
    <section class="razz-neighborhood-widget">
        ' . $style . '
        <iframe src="' . $iframe_url .'" width="100%" height="' . $mapHeight . 'px"></iframe>
    </section>';
}

add_shortcode('razz_neighborhood', 'neighborhood_shortcode');


// Enqueue Plugin Styles and Java Script
function razz_neighborhood_client_script()
{
    // CSS files
    wp_enqueue_style('razz-nbhd-client-style', plugin_dir_url(__FILE__) . 'style.css');
}

add_action('wp_enqueue_scripts', 'razz_neighborhood_client_script');