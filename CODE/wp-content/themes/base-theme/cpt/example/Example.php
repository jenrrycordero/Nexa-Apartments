<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


include_once "classes/ExampleShortcode.php";
include_once "classes/ExampleHelpers.php";


class Example
{

	private $ShortCode;

	function __construct()
	{
		add_action( 'init', 'create_post_type' );
		$this->ShortCode	= new ExampleShortcode();
	}


	public static function include_defaults()
	{
		wp_enqueue_style('example_style', get_stylesheet_directory() . "/cpt/example/example-style.css", array(), THEME_VERSION);
		wp_enqueue_script('example_script', get_stylesheet_directory() . "/cpt/example/example-script.js", array(), THEME_VERSION);
	}
}

$example = new Example();
