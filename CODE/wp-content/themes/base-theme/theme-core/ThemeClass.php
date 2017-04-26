<?php

/**
 * Class ThemeClass This class is created to be used as part of ANY class developed for plugins/CPT/Shortcodes
 * so they provide a common interface to implement the get_instance filter. This way it can be overrode or soemthing
 * at any time.
 */
abstract class ThemeClass
{
	public function __construct()
	{
		add_filter( 'get_instance', [ $this, 'get_instance' ] );
	}

	public function get_instance()
	{
		return $this; // return the object
	}
}