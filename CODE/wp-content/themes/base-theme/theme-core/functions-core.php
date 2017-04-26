<?php

// Add core definitions to be used across the theme, helpers and rendering functions.
if ( !defined("THEME_IMG_LAZY_LOAD_CLASS") ) define('THEME_IMG_LAZY_LOAD_CLASS', 'img-lazy');
if ( !defined("THEME_IMG_BG_LAZY_LOAD_CLASS") ) define('THEME_IMG_BG_LAZY_LOAD_CLASS', 'img-bg-lazy');
if ( !defined("THEME_IMG_BG_HTML_CLASS") ) define('THEME_IMG_BG_HTML_CLASS', 'bg-element');
if ( !defined("THEME_IMG_BG_INTEGRATED_HTML_CLASS") ) define('THEME_IMG_BG_INTEGRATED_HTML_CLASS', 'bg-element-int');
if ( !defined("THEME_DEFAULT_TIME_FORMAT") ) define ('THEME_TIME_FORMAT', 'Y-m-d H:i:s');

if ( !defined("THEME_AJAX_CONTENT_CLASS") ) define ('THEME_AJAX_CONTENT_CLASS', 'ajax_get_content');
if ( !defined("THEME_AJAX_LIGHTBOX_CLASS") ) define ('THEME_AJAX_LIGHTBOX_CLASS', 'ajax_get_content_lightbox');

if ( !defined("THEME_AJAX_ACTION_CONTENT") ) define ('THEME_AJAX_ACTION_CONTENT', 'get_content');
if ( !defined("THEME_AJAX_ACTION_LIGHTBOX") ) define ('THEME_AJAX_ACTION_LIGHTBOX', 'get_content_lightbox');

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3);    // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2);          // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link');               // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link');       // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'wp_generator');           // Display the XHTML generator that is generated on the wp_head hook, WP version
