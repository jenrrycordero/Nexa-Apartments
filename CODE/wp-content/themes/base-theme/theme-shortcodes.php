<?php

/**
 * This file will include ONLY the shortcodes definitions and the function that will process the shortcode.
 * Do no add code here.
 */


add_shortcode( 'theme_date', array(new ThemeShortcodesCore, 'theme_date') );
add_shortcode( 'theme_footer_image', array(new ThemeShortcodesCore, 'theme_footer_image') );