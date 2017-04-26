<?php


//Basic theme support and images definitions.
add_action('after_setup_theme', 'a_theme_setup');
function a_theme_setup()
{
	// Add Menu Support
	add_theme_support('menus');

	// Add Thumbnail Theme Support
	add_theme_support('post-thumbnails');

	//TODO: load images sizes dynamically?

	// Images sizes.
	add_image_size('icon', 30, 30, true);
	add_image_size('extra-large', 1500, '', true);
	add_image_size('large', 700, '', true);
	add_image_size('medium', 250, '', true);
	add_image_size('small', 120, '', true);
	add_image_size('square-small', 100, 100, true);
	add_image_size('square-medium', 300, 300, true);
	add_image_size('gallery', 800, 800, true);
	add_image_size('gallery-2', 1000, 500, true);

	add_theme_support(
		'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	/*
	 * TODO: develop code to support the different-specific type of post-types.
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support(
		'post-formats', array(
			'aside',
			'image',
			'video'
		)
	);

	// Enables post and comment RSS feed links to head
	add_theme_support('automatic-feed-links');

	// Localisation Support
	load_theme_textdomain(THEME_DOMAIN, get_template_directory() . '/languages');
}

// Add Custom Scripts to wp_head
add_action('init', 'a_theme_header_scripts');
function a_theme_header_scripts()
{
	if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

		//Load of the Critical/Important JS. Modernizr, Conditionizr, isMobile
		wp_register_script('vendors-scripts-head', get_template_directory_uri() . '/dist/js/vendors-head.min.js', array(), THEME_VERSION);
		wp_enqueue_script('vendors-scripts-head'); // Enqueue it!

		//Load of the rest of the vendors/external libraries, Bootstrap.
		wp_register_script('vendors-scripts-footer', get_template_directory_uri() . '/dist/js/vendors-footer.min.js', array(), THEME_VERSION, true);
		wp_enqueue_script('vendors-scripts-footer'); // Enqueue it!

		wp_register_script('underscore', get_template_directory_uri() . '/assets/scripts/underscore-min.js', array(), THEME_VERSION, true);
		wp_enqueue_script('underscore'); // Enqueue it!

        wp_register_script('owl-carousel', get_template_directory_uri() . '/assets/owlcarousel/owl.carousel.min.js', array(), THEME_VERSION, true);
        wp_enqueue_script('owl-carousel'); // Enqueue it!

        wp_register_script('scroll-magic', get_template_directory_uri() . '/assets/gsap/ScrollMagic.min.js', array(), THEME_VERSION, true);
        wp_enqueue_script('scroll-magic'); // Enqueue it!

        wp_register_script('tween-lite', get_template_directory_uri() . '/assets/gsap/TweenMax.min.js', array(), THEME_VERSION, true);
        wp_enqueue_script('tween-lite'); // Enqueue it!

        wp_register_script('tween-lite-debug', get_template_directory_uri() . '/assets/gsap/debug.addIndicators.min.js', array(), THEME_VERSION, true);
        wp_enqueue_script('tween-lite-debug'); // Enqueue it!

        wp_register_script('tween-lite-animation', get_template_directory_uri() . '/assets/gsap/animation.gsap.min.js', array(), THEME_VERSION, true);
        wp_enqueue_script('tween-lite-animation'); // Enqueue it!

		//Enqueue Styles.
		wp_enqueue_style('theme-owl-carousel', get_template_directory_uri() . '/assets/owlcarousel/owl.carousel.min.css', array(), THEME_VERSION);
		wp_enqueue_style('theme-style', get_template_directory_uri() . '/dist/css/theme.min.css', array(), THEME_VERSION);

        //leave this 2 files at the end always.
        wp_enqueue_style('theme-style-override', get_template_directory_uri() . '/assets/style/_custom.css', array(), THEME_VERSION);
		wp_register_script('theme-script-override', get_template_directory_uri() . '/assets/scripts/_custom.js', array('jquery'), THEME_VERSION, true);
		wp_enqueue_script('theme-script-override');

		wp_register_script('nexa-scripts-footer', get_template_directory_uri() . '/assets/scripts/_nexa.js', array('jquery'), THEME_VERSION, true);
		wp_enqueue_script('nexa-scripts-footer');

		wp_register_script('jquery-history', get_template_directory_uri() . '/assets/history.js/scripts/bundled/html4+html5/jquery.history.js', array('jquery'), THEME_VERSION, true);
		wp_enqueue_script('jquery-history');

		wp_localize_script(
			'nexa-scripts-footer', 'ajax_object', array(
			'ajax_url' => admin_url('admin-ajax.php')
		));
	}
}

add_action('init', 'a_theme_menus');
function a_theme_menus()
{
	register_nav_menus(
		array( // Using array to specify more menus if needed
			'header-menu' => __('Header Menu', THEME_DOMAIN),   // Main Navigation
			'sidebar-menu' => __('Sidebar Menu', THEME_DOMAIN),  // Sidebar Navigation
			'footer-menu' => __('Footer Menu', THEME_DOMAIN)    // Extra Navigation if needed (duplicate as many as you need!)
		)
	);
}


// ###############################     ADMIN ACTIONS     ############################ //
add_action('admin_enqueue_scripts', 'a_theme_header_scripts_admin');
function a_theme_header_scripts_admin()
{
	wp_enqueue_style('admin-theme-style', get_template_directory_uri() . '/assets/style/admin/_main.css', array(), THEME_VERSION);
	wp_register_script('admin-theme-script', get_template_directory_uri() . '/assets/scripts/admin/_main.js', array('jquery'), THEME_VERSION);

	$hide_extra_options = get_field("hide_extra_options", 'options');
	if (!$hide_extra_options) {
		wp_register_script('admin-theme-script-override', get_template_directory_uri() . '/assets/scripts/admin/_main_override.js', array('jquery', 'admin-theme-script'), THEME_VERSION);
	}

	wp_enqueue_script('admin-theme-script');
	wp_enqueue_script('admin-theme-script-override');
}