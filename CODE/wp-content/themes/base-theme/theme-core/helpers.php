<?php

/**
 * Function to include all the files in the folder that match the expression defined in the second paramer.
 * @param string $folder Folder path to include from. No trailing / should be added.
 * @param string $extension Optional (default *.php). Regular expression that should be matched before including the file.
 */
function include_all_from_folder( $folder, $extension = "*.php")
{
	foreach (glob("{$folder}/{$extension}") as $filename)
		include $filename;
}

/**
 * Standardization of the wpnonce. Should be called to verify the nonce fields generates FROM the function generate_nonce_field_for
 * @param string $nonceName name|id of the field to be checked. The same on supplied to the generate_nonce_field_for fx
 *
 * @return false|int False if its not valid. 1 if valid and generated less than 12h. 2 if valid but generated more than 12h ago
 *
 * @see generate_nonce_field_for()
 */
function check_nonce_for($nonceName)
{
	return wp_verify_nonce  ( $_REQUEST[ "nonce_$nonceName" ],  $nonceName );
}

/**
 * Standardization of the nonce field, to make it easy to be checked through the check_nonce_for function. This functions output
 * the code to the standard exit, so if you want to get the value should prevent it to be send to the client.
 * @param string $nonceName name|id of the field to be generated.
 *
 * @see check_nonce_for()
 */
function generate_nonce_field_for($nonceName)
{
	wp_nonce_field ( $nonceName, "nonce_$nonceName" );
}


/**
 * Function to convert the color and generate the equivalent of the color with the specified opacity
 *
 * @param String     $color Initial color to apply the opacity. Needs to be on hex and can be either 3 or 6 Char.
 * @param bool|Float $opacity By default is FALSE (is interpreted as opacity: 1), can be any value from 0 to 1 (0.15, 0.785)
 *
 * @return string The color with the opacity applied in the format rgba(R,G,B,Opacity) or rgb(R,G,B) if the param opacity is set to false.
 */
function hex2rgba($color, $opacity = false) {

	if(empty($color)) return '';

	$default = 'rgb(0,0,0)';

	//Sanitize $color if "#" is provided
	if ($color[0] == '#' )
	{
		$color = substr( $color, 1 );
	}

	//Check if color has 6 or 3 characters and get values
	switch ( strlen($color) )
	{
		case 6:
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			break;
		case 3:
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			break;
		default:
			return $default;
	}

	//Convert hexadec to rgb
	$rgb =  array_map('hexdec', $hex);

	//Check if opacity is set(rgba or rgb)
	if($opacity)
	{
		if( abs($opacity) > 1 ) $opacity = 1.0;
		$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
	}
	else
	{
		$output = 'rgb('.implode(",",$rgb).')';
	}

	//Return rgb(a) color string
	return $output;
}


/**
 * function to get an elapsed time (like 1m ago, or 3 months ago) from a date.
 *
 * @param Int $datetime the vale to build the DateTime object.
 * @param bool $full to return a full or no.
 *
 * @return string with the date representation.
 */
function time_elapsed_string($datetime, $full = false) {
	$now = new DateTime;
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);

//    return $datetime;

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v)
	{
		if ($diff->$k) $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		else unset($string[$k]);
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}


/**
 * This function comapre to string and try to see if they are equals. It take in consideration the different spanish accents for
 * vocals and make them "equal" to the vocal without accent, then ó == o
 *
 * @param $str1 String First string to compare
 * @param $str2 String Second string to compare
 *
 * @return bool true if they are "equal", false if no.
 */
function compare_string_raw($str1, $str2)
{
	$search  = array("à","á","â","ä","æ","ã","å","ā", "è","é","ê","ê","ē","ė","ę", "î","ï","í","ì","į","ì", "ô","ö","ò","ó","œ","ø","ō","õ", "û","ü","ù","ú","ū");
	$replace = array("a","a","a","a","a","a","a","a", "e","e","e","e","e","e","e", "i","i","i","i","i","i", "o","o","o","o","o","o","o","o", "u","u","u","u","u");

	return strtolower(str_replace($search, $replace, $str1)) == strtolower(str_replace($search, $replace, $str2)) ;
}