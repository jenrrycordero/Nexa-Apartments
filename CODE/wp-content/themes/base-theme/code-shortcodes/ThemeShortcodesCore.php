<?php

class ThemeShortcodesCore extends ThemeClass
{

	/**
	 * Get the date
	 * @see date()
	 *
	 * @param array $attributes An array with the following indexes :\n
	 *                          - year  : Default 0. 1 to display year.
	 *                          - month : Default 0. 1 to display month
	 *                          - day   : Default 0. 1 to display day
	 *                          - format : Default empty. Format to display. Override year, month, day displays.
	 *                          - date  : timestamp for the date or empty or now to use it to generate the date from
	 *                          there
	 *
	 * @return string a text representation of the date.
	 */
	public function theme_date( $attributes )
	{
		$atts = shortcode_atts(
			array(
				'year'  => '0',
				'month' => '0',
				'day'   => '0',
				'format' => '',
				'date'  => 'now'
			), $attributes );

		if ( $atts['format'] )
			$format = $atts['format'];
		else
		{
			$format = ( $atts['year'] ) ? "Y" : "";
			$format .= ( $atts['month'] ) ? " m" : "";
			$format .= ( $atts['day'] ) ? " d" : "";
		}

		$timestamp = ( $atts['date'] == "now" ) ? time() : $atts['date'];

		return date( $format, $timestamp );
	}

	/**
	 * Get the Image defined on the options page as <b>Footer Image</b>.
	 * @see wp_get_attachment_image_url(), wp_get_attachment_metadata()
	 *
	 * @param array $attributes An array with the following indexes :\n
	 *                          - url  : Default 0. 1 to return just the url.
	 *                          - object : Default 0. 1 to return an object with the media info. If url=1 this param will be ignore.
	 *                          - field : Default "footer_image". Indicate the field to be retrieved. The whole code works under 'options' only.
	 *
	 * @return string an url for the image or empty if fails. keep in mind that the Object will return the values defined on
	 *                wp_get_attachment_metadata(), unless the field was stored to return an object. In which case that
	 *                object will be returned. (the defualt implementation of the field is to return an ID)
	 */
	public function theme_footer_image( $attributes )
	{
		$atts = shortcode_atts(
			array(
				'url'  => '1',
				'object' => '0',
				'field' => 'footer_image'
			), $attributes );

		$image = get_field( $atts['field'], 'option');
		if ( !$image ) return '';

		if ( $atts['url'] )
		{
			if ( is_array($image) ) return $image['url'];
			if ( is_int($image) ) return wp_get_attachment_image_url($image, 'full');
		}
		else if ( $atts['object'] )
		{
			if ( is_array($image) ) return $image;
			if ( is_int($image) ) return wp_get_attachment_metadata($image);
		}

		return '';
	}


}