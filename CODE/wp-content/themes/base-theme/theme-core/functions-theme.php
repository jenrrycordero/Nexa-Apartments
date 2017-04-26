<?php

/**
 * Function to get the filepath to include the Menu file..
 *
 * @param string $menu Menu name of the file to be included
 *
 * @return string return the filepath ready to be included.
 */
function theme_get_menu($menu)
{
	return theme_get_templates_parts_directory() . "/menus/$menu.php";
}

/**
 * Function to get the filepath to include the CPT.
 *
 * @param string $cptSlug This is the CPT slug. should be lowercase.
 *
 * @return string return the filepath ready to be included.
 */
function theme_get_cpt($cptSlug)
{
	return theme_get_cpt_directory() . "/$cptSlug/" . ucfirst($cptSlug) . ".php";
}

/**
 * Function to get the path to the single template. If no param provided will return the default one.
 *
 * @param string $post_type post type to infer the single tempalte from.
 *
 * @return string
 */
function theme_get_single_template( $post_type = "")
{

	$default_path = theme_get_templates_directory() . "/single.php";
	$cpt_path = theme_get_cpt_directory() . "/$post_type/$post_type-single.php";

	return is_string($post_type) && is_file($cpt_path) ? $cpt_path : $default_path;
}



/**
 * @param string $part path inside the templates_parts_directory to the required file. No need to include the opening / nor the ending .php
 * @param string $default optional.default file to include if the $part isnt a proper file. Default 404
 * @param string $extension optional. Indicate the extension of the file to be loaded. Default php.
 *
 * @return string the path to the templates directory.
 */
function theme_get_template_part($part, $default = "404", $extension = "php")
{
	$part = trim($part,".php");
	$file = theme_get_templates_parts_directory() . "/$part.$extension";
	if ( !is_file($file) ) $file = theme_get_templates_parts_directory() . "/$default.$extension";
	return $file;
}

/** @return string the path to the theme core directory. Do not include the / */
function theme_get_core_directory() { return get_stylesheet_directory() . "/theme-core"; }
/** @return string the path to the templates directory.  */
function theme_get_templates_directory() { return get_stylesheet_directory() . "/templates"; }
/** @return string the path to the templates directory. */
function theme_get_templates_parts_directory() { return get_stylesheet_directory() . "/templates-parts"; }

/** @return string the path to the CPT default directory. */
function theme_get_cpt_directory() { return get_stylesheet_directory() . "/cpt"; }

/** @return string the path to the assets directory. */
function theme_get_assets_directory() { return get_stylesheet_directory() . "/assets"; }
