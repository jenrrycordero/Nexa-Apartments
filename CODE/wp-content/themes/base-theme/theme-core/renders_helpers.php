<?php
/**
 * This file contains functions that (usually) are not used directly but through the renders_* functions instead.
 * They are defined so they can be access directly if needed.
 */

/**
 * Function to get the html structure for an image. This can be used to get just the information and build the html code
 * if needed. The function uses the following constant values: <b>THEME_IMG_LAZY_LOAD_CLASS</b>.
 *
 * @param String     $image_id  Image id from what we want to get the custom sources.
 * @param String     $size_sd   Image size name for the low quality image
 * @param String     $size_hd   Image size name for the high quality image
 * @param string     $size_retina Image size name for the retina quality image
 * @param string     $class     Custom class to add to the html object. By default its 'img-responsive'. The function already add the general class for the js code to work properly.
 * @param int        $force_dimensions Indicate if the IMG should have the dimensions forced to the img_hd element.
 * @param bool|FALSE $return_array By default its FALSE, if TRUE its going to return an Array with the 3 img src as index (img_sd, img_hd, img_retina)
 *
 * @return array|string The return type depends on the return_array parameter, if its an Array then this have the indexs: array(img_sd, img_hd, img_retina). For the Html
 *                      string this is ready to be output.
 */
function _get_image($image_id, $size_sd, $size_hd, $size_retina = 'url', $class = "img-responsive", $force_dimensions = 1, $return_array = FALSE)
{
	$html = '';

	// First we retrieve all the img src generated for each image size (sd, hd and retina).
	$t          = wp_get_attachment_image_src( $image_id, $size_sd );
	$img_src_sd = $t[0];

	$t          = wp_get_attachment_image_src( $image_id, $size_hd );
	$img_src_hd = $t[0];
	$img_w = $t[1];
	$img_h = $t[2];

	$img_src_retina = '';
	if ( $size_retina )
	{
		$t              = wp_get_attachment_image_src( $image_id, $size_retina );
		$img_src_retina = $t[0];
	}

	if ( $return_array )
	{
		//this is useful to build the html object 100% custom.
		return array(
			'img_sd' => $img_src_sd,
			'img_hd' => $img_src_hd,
			'img_retina' => $img_src_retina,
		);
	}
	$img_extra = "";
	if ( $force_dimensions )
	{
		$img_extra = " width='$img_w' height='$img_h' ";
	}

	// if it should return an HTML code then we process to build it,
	// to use the data attributes we are going to need for the js

	$obj_class = " $class " . THEME_IMG_LAZY_LOAD_CLASS;

	$html = "<img src='$img_src_sd' class='$obj_class' data-img-hd='$img_src_hd' data-img-retina='$img_src_retina' $img_extra/>";
	return $html;
}


/**
 * Function to get the html structure for a background image. This can be used to get just the information and build the
 * html code if needed. The function uses the following constant values: <b>THEME_IMG_BG_HTML_CLASS</b> y <b>THEME_IMG_BG_LAZY_LOAD_CLASS</b>.
 * If its set to use the html te parent class should have a position relative, since the code return an absolute element,
 * and any sibiling should have a z-index > than the one for the background element
 *
 * @param String     $image_id      Image id from what we want to get the custom sources.
 * @param String     $size_sd       Image size name for the low quality image
 * @param String     $size_hd       Image size name for the high quality image
 * @param string     $size_retina   Image size name for the retina quality image
 * @param string     $class         Custom class to add to the html object. By default its 'bg-element-full'.
 *                                  The function already add the general class for the js code to work properly.
 * @param bool|FALSE $return_array  By default its FALSE, if TRUE its going to return an Array with the 3 img src as index (img_sd, img_hd, img_retina)
 *
 * @return array|string The return type depends on the return_array parameter, if its an Array then this have the indexs: array(img_sd, img_hd, img_retina). For the Html
 *                      string this is ready to be output. Important note is that the object require some CSS rules to work properly.
 *
 * @see wp_get_attachment_image_src
 */
function _get_image_as_background($image_id, $size_sd, $size_hd, $size_retina = 'url', $class = "bg-element-full", $return_array = FALSE, $innerHtml = '')
{
	$html = '';

	// First we retrieve all the img src generated for each image size (sd, hd and retina).
	$t          = wp_get_attachment_image_src( $image_id, $size_sd );
	$img_src_sd = $t[0];

	$t          = wp_get_attachment_image_src( $image_id, $size_hd );
	$img_src_hd = $t[0];

	$img_src_retina = '';
	if ( $size_retina )
	{
		$t              = wp_get_attachment_image_src( $image_id, $size_retina );
		$img_src_retina = $t[0];
	}

	if ( $return_array )
	{
		//this is useful to build the html object 100% custom.
		return array(
			'img_sd'     => $img_src_sd,
			'img_hd'     => $img_src_hd,
			'img_retina' => $img_src_retina,
		);
	}


	// if it should return an HTML code then we process to build it,
	// to use the data attributes we are going to need for the js

	$obj_class = THEME_IMG_BG_HTML_CLASS . " $class " . THEME_IMG_BG_LAZY_LOAD_CLASS;

	$html = "<div class='$obj_class' style=\"background-image: url('$img_src_sd');\" data-img-hd='$img_src_hd' data-img-retina='$img_src_retina'>$innerHtml</div>";
	return $html;
}


/**
 * Function to get the ordered array of social links from the options page. This function work against the DEFAULT structure and order
 * of the options page.
 *
 * @return array with the information for the link. Each index is an array as follow: array( url=>'', img=>'', img_hover=>'').
 */
function _get_social_list_from_options()
{
	$facebook_link   = get_field( 'facebook', 'option' );
	$facebook_order  = get_field( 'facebook_order', 'option' );
	$twitter_link    = get_field( 'twitter', 'option' );
	$twitter_order   = get_field( 'twitter_order', 'option' );
	$instagram_link  = get_field( 'instagram', 'option' );
	$instagram_order = get_field( 'instagram_order', 'option' );
	$youtube_link    = get_field( 'youtube', 'option' );
	$youtube_order   = get_field( 'youtube_order', 'option' );
	$spotify_link    = get_field( 'spotify', 'option' );
	$spotify_order   = get_field( 'spotify_order', 'option' );
	$google_link     = get_field( 'google', 'option' );
	$google_order    = get_field( 'google_order', 'option' );

	//now we need to build the array in the proper order.
	$social_array = array();

	$social_array[ $facebook_order+1 ] = array(
		"url" => $facebook_link,
		"class" => "fa-facebook-f",
		"img" => "",
		"img_hover" => ""
	);
	$social_array[ $twitter_order+1 ] =  array(
		"url" => $twitter_link,
		"class" => "fa-twitter",
		"img" => "",
		"img_hover" => ""
	);
	$social_array[ $instagram_order+1 ] =  array(
		"url" => $instagram_link,
		"class" => "fa-instagram",
		"img" => "",
		"img_hover" => ""
	);
	$social_array[ $youtube_order+1 ] =  array(
		"url" => $youtube_link,
		"class" => "fa-youtube-play",
		"img" => "",
		"img_hover" => ""
	);
	$social_array[ $spotify_order+1 ] =  array(
		"url" => $spotify_link,
		"class" => "fa-spotify",
		"img" => "",
		"img_hover" => ""
	);
	$social_array[ $google_order+1 ] =  array(
		"url" => $google_link,
		"class" => "fa-google-plus",
		"img" => "",
		"img_hover" => ""
	);

	ksort($social_array);
	return $social_array;
}
