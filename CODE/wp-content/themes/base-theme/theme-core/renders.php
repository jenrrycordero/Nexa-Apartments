<?php
include_once get_stylesheet_directory() . "/theme-core/renders_helpers.php";

/**
 * Render (ouput directly) the information for the site by.
 *
 * @param string $label      Text to prepend to the site By.
 * @param bool   $displayGTM Optional. Indicate this if we want to display gotomyapartment.com. Default true.
 * @param string $appendRazz Optional. Any parameters we want to append to the RAZZ url. Default empty
 * @param string $appendGTM  Optional. Any parameters we want to append to the GMT url. Default empty
 */
function render_site_by( $label, $displayGTM = TRUE, $appendRazz = "", $appendGTM = "" )
{
	?>
	<span class="site-by-label"><?php echo $label; ?></span>
	<a class="site-by-link razz-link" target="_blank" href="http://razzinteractive.com/<?php echo $appendRazz ?>">Razz
		Interactive</a>
	<?php if ( $displayGTM ): ?>
	<span class="site-by-and">and</span>
	<a class="site-by-link gtm-link" target="_blank" href="http://gotomyapartment.com/<?php echo $appendGTM ?>">GoToMyApartment</a>
	<?php
endif;
}

/**
 * Render (ouput directly) the icon with the classes to achieve the jump back to top.
 *
 * @param int $targetTop indicate the value of the jump-to.
 */
function render_back_top( $targetTop = 0 )
{
	?>
	<div class="jump-to footer-jump-top" data-target-offset="<?php echo $targetTop; ?>">
		<i class="fa fa-angle-up"></i>
	</div>
	<?php
}


/**
 * Function to render the html structure for a background image. This can be used to get just the information and build
 * the html code if needed. The function uses the following constant values: <b>THEME_IMG_BG_HTML_CLASS</b> y
 * <b>THEME_IMG_BG_LAZY_LOAD_CLASS</b>. If its set to use the html te parent class should have a position relative,
 * since the code return an absolute element, and any sibiling should have a z-index > than the one for the background
 * element
 *
 * @see _get_image_as_background()
 *
 * @param String $image_id          Image id from what we want to get the custom sources.
 * @param String $size_sd           Image size name for the low quality image
 * @param String $size_hd           Image size name for the high quality image
 * @param string $size_retina       Image size name for the retina quality image
 * @param string $class             Custom class to add to the html object. By default its 'bg-element-full'.
 *                                  The function already add the general class for the js code to work properly.
 */
function render_image_as_background( $image_id, $size_sd, $size_hd, $size_retina = 'url', $class = "bg-element-full" )
{
	echo _get_image_as_background( $image_id, $size_sd, $size_hd, $size_retina, $class );
}


/**
 * Function to render the html structure for an image. This can be used to get just the information and build the html
 * code if needed. The function uses the following constant values: <b>THEME_IMG_LAZY_LOAD_CLASS</b>.
 * @see _get_image();
 *
 * @param String $image_id         Image id from what we want to get the custom sources.
 * @param String $size_sd          Image size name for the low quality image
 * @param String $size_hd          Image size name for the high quality image
 * @param string $size_retina      Image size name for the retina quality image
 * @param string $class            Custom class to add to the html object. By default its 'img-responsive'. The
 *                                 function already add the general class for the js code to work properly.
 * @param int    $force_dimensions Indicate if the IMG should have the dimensions forced to the img_hd element.
 */
function render_image(
	$image_id, $size_sd, $size_hd, $size_retina = 'url', $class = "img-responsive", $force_dimensions = 1 )
{
	echo _get_image( $image_id, $size_sd, $size_hd, $size_retina, $class, $force_dimensions );
}


/**
 * Function to render a list of social link from a list of options fields.
 * @see _get_social_list_from_options()
 *
 * @param string $wrapper_class Optional. Class to add to the whole wrapper. Default empty
 * @param string $element_class Optional. Class to add to each element within the list of social link.
 * @param string $link_class Optional. Class to add to each individual link of the list.
 * @param string $wrapper_tag Optional. HTML tag to be used when rendering the elements. If a list is used
 *                            then the elements will be li, if no they will be regular divs. Default is ul.
 *
 * @return string Return the HTML associated with the social list retrieved.
 */
function render_social_list_from_options( $wrapper_class = "", $element_class = "", $link_class = "", $wrapper_tag = "ul" )
{
	$html = "";

	$element_tag = ( $wrapper_tag == "ul" || $wrapper_tag == "ol" ) ? "li" : "div";
	$target = "_blank";

	$social_array = _get_social_list_from_options();

	$html .= "<$wrapper_tag class='social-list-wrapper $wrapper_class'>";

	foreach ( $social_array as $order => $social )
	{
		if ( empty($social) || !$social['url']) continue;

		$link = $social['url'];

		$html .= "<$element_tag class='social-list-element $element_class'>";
		$html .= "<a href='$link' target='$target' class='$link_class'><i class='fa fa-facebook-f'></i></a>";
		$html .= "</$element_tag>";
	}

	$html .= "</$wrapper_tag>";

	//we use the approach of concatenating html to ensure there is no space/lines between elements so
	//if they have a display:inline-block there wont be any issue
	echo $html;
}


// #######################################       PENDING       #####################################

/**
 * function to standarize and get the loadMore button.
 *
 * @param string $load_more_target          ID where the loadmore info will be appended to. Dont include the #.
 * @param array  $load_more_array_info      Information needed to build the loadmore request
 * @param string $load_more_text            Text to display on the loadmore. Default Load More
 * @param string $btn_id                    Id of the button. Optional. default none.
 * @param string $btn_extra_class           extra class to be added to the button. Optional. Default empty.
 * @param bool   $display_icon              Decide if you want to display an icon. Default true
 * @param string $overray_load_more_icon    Text or icon to override the default icon.
 *
 * @return string Html ready to be outputed.
 */
function render_button($load_more_target, $load_more_array_info, $load_more_text = "Load More", $btn_id = "", $btn_extra_class = "", $display_icon = TRUE,  $overray_load_more_icon = "")
{

	$load_more_text_and_icon = $load_more_text;
	$load_more_target =  str_replace("#", "", $load_more_target);

	if ( $display_icon )
	{
		if ( $overray_load_more_icon ) $load_more_text_and_icon .= " $overray_load_more_icon";
		else $load_more_text_and_icon .= " <span class='icon-add'></span>";
	}

	$btn_html_id = "";
	if ( $btn_id )
	{
		$btn_html_id = "id='$btn_id'";
	}

	$load_more_auto_tries = wp_is_mobile() ? 0 : 2;
	$load_more_auto_tries = 0;
	$load_more_auto_tries_initial = 2;
	$load_more_counter_attr = " data-load-more-counter=$load_more_auto_tries data-load-more-counter-initial=$load_more_auto_tries_initial ";

	$json_encoded_load_more_info = json_encode($load_more_array_info);

	$html = "<button $btn_html_id class='btn btn-load-more btn-effect-1 $btn_extra_class ' data-enable-load-more='1' data-loadmore-target='#$load_more_target' data-loadmore-info='$json_encoded_load_more_info' $load_more_counter_attr>
            <span class=''>$load_more_text_and_icon</span></button>";

	return $html;
}


