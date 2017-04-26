<?php

/**
 * Class SassWatcherValidate Helper class for the SassWatcher class to define the validate functions to be used there.
 */
abstract class SassWatcherValidate
{
	/**
	 * Get the validated css string for the field.
	 *
	 * @param array  $field_value The original values of the saved field.
	 * @param string $name string that represent the css selector to apply the styles.
	 * @param string $mode
	 *
	 * @return string String with the validated format constructed from the field values.
	 */
	public static function get_background_field_validated( $field_value, $name, $mode = 'background') {

		if ( empty( $field_value['background-color']) )$field_value['background-color'] = 'transparent';
		if ( empty( $field_value['background-image']) )$field_value['background-image'] = 'none';

		$default_bg_value = array(
			'background-color'      => 'transparent',
			'background-repeat'     => 'repeat',
			'background-clip'       => 'border-box',
			'background-origin'     => 'padding-box',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
			'background-position'   => '0 0',
			'background-image'      => 'none',
			'media' => array(
				'id'        => null,
				'height'    => 0,
				'width'     => 0,
				'thumbnail' => null,
			),
			'background-text'      	=> 'null',
		);
		$field_value      = array_merge( $default_bg_value, $field_value);

		$all_value = '';
		foreach ( $field_value as $key => $item)
		{
			if( $key != 'media')
			{
				if( empty($item) ) $item = 'null';

				if( (substr($key, 11) == 'image' || substr($key, 11) == 'position') && $item != 'null')
				{
					$all_value = $all_value . ' "' . $item . '"';
				}
				else
				{
					$all_value = $all_value . ' ' . $item;
				}
			}
			else if ( $field_value['background-image'] != 'none')
			{
				$all_value = $all_value . ' ' . $item['width'] . ' ' . $item['height'];
			}
			else
			{
				$all_value = $all_value . ' 0 0';
			}
		}

		$css_string = "";

		if( $mode == 'background' )     $css_string = '$' . $name . ':' . $all_value . ';' . chr(13);
		else if( $mode == 'repeater' )  $css_string = '(' . $all_value . '), ';

		return $css_string;
	}

	/**
	 * Get the validated css string for the typography field.
	 *
	 * @param array  $field_value value of the field.
	 * @param string $name string of the css selector.
	 *
	 * @return string String with the validated format constructed from the field values.
	 */
	public static function get_typography_field_validate( $field_value, $name) {

		$default_value = array(
			'font-family'	=> 'null',
			'font-weight'	=> '400',
			'backupfont'	=> 'null',
			'text_align'	=> 'left',
			'direction'		=> 'ltr',
			'font_size'		=> 'null',
			'line_height' 	=> 'null',
			'font_style' 	=> 'normal',
			'text-color' 	=> 'null',
			'letter_spacing' => '0'
		);

		$field_value = array_merge( $default_value, $field_value);

		$all_value = '';
		foreach ( $field_value as $key => $item)
		{
			if (!$item)
			{
				$field_value[ $key] = $default_value[ $key];
				$item               = $field_value[ $key];
			}
			if ( ( ($key == 'font-family') or ($key == 'backupfont') ) and ( $item != 'null' ) )
			{
				$all_value = $all_value . ' "' . $item . '"';
			}
			else
			{
				$all_value = $all_value . ' ' . $item;
			}
		}

		return '$' . $name . ':' . $all_value . ';' . chr(13);
	}

}