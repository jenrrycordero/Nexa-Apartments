<?php
/**
 * Created by PhpStorm.
 * User: aliannieves
 * Date: 11/14/16
 * Time: 6:42 PM
 */

// Add page slug to body class - Credit: Starkers Wordpress Theme
add_filter('body_class', 'f_add_slug_to_body_class');
function f_add_slug_to_body_class($classes)
{
	global $post;
	if (is_home() )
	{
		$key = array_search('blog', $classes);
		if ($key > -1) unset($classes[$key]);
	}
	elseif (is_page())
	{
		$classes[] = sanitize_html_class($post->post_name);
	}
	elseif (is_singular())
	{
		$classes[] = sanitize_html_class($post->post_name);
	}

	return $classes;
}

add_filter( 'single_template', 'f_include_custom_single_template' );
/**
 * Filter the single_template to use the one specific for the CPT if it exist. If no fallback to the default one.
 */
function f_include_custom_single_template( $single )
{
	global $post;

	return theme_get_single_template($post->post_type);
}


add_filter( 'image_resize_dimensions', 'f_image_resize_dimensions', 1, 6 );
/**
 * Scale up images based on the images sizes defined (or when editing or when rebuilding images).
 * The idea is to force specific height regardless the image quality for small images.
 * The function take in consideration images to be cropped or no. This is slightly changed function
 * image_resize_dimensions() in wp-icludes/media.php
 *
 * @see http://core.trac.wordpress.org/ticket/23713
 */
function f_image_resize_dimensions( $nonsense, $orig_w, $orig_h, $dest_w, $dest_h, $crop = false)
{
	if ( $crop )
	{
		// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
		$aspect_ratio = $orig_w / $orig_h;

		if ( $dest_h > $orig_h || $dest_w > $orig_w )
		{
			$new_w = $dest_w;
			$new_h = $dest_h;
		}
		else
		{
			$new_w = min($dest_w, $orig_w);
			$new_h = min($dest_h, $orig_h);
		}

		if ( !$new_w ) $new_w = intval($new_h * $aspect_ratio);
		if ( !$new_h ) $new_h = intval($new_w / $aspect_ratio);

		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		$s_x = floor( ($orig_w - $crop_w) / 2 );
		$s_y = floor( ($orig_h - $crop_h) / 2 );
	}
	else
	{
		// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
		$crop_w = $orig_w;
		$crop_h = $orig_h;

		$s_x = 0;
		$s_y = 0;

		/* wp_constrain_dimensions() doesn't consider higher values for $dest :( */
		/* So just use that function only for scaling down ... */
		if ($orig_w >= $dest_w && $orig_h >= $dest_h )
		{
			list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
		}
		else
		{
			$ratio = $dest_w / $orig_w;
			$w = intval( $orig_w  * $ratio );
			$h = intval( $orig_h * $ratio );
			list( $new_w, $new_h ) = array( $w, $h );
		}
	}

	// if the resulting image would be the same size or larger we don't want to resize it
	// Now WE need larger images ...
	//if ( $new_w >= $orig_w && $new_h >= $orig_h )
	if ( $new_w == $orig_w && $new_h == $orig_h )
		return false;

	// the return array matches the parameters to imagecopyresampled()
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}