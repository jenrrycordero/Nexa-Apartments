<?php


function foobar_func($atts)
{
	return "foo and bar";
}

add_shortcode('foobar', 'foobar_func');


add_shortcode('razz_show_models', 'razz_show_models_shortcode');


function razz_show_models_shortcode($atts, $content = null)
{
	extract(shortcode_atts(
			array(
				'all' => 1,
				'filters' => 1,
				'orderby' => 'name',
				'order' => 'asc',
				'limit' => -1
			), $atts
		)
	);

	$html = "";


	//Second we retrieve the models based on the options of the sortcode.
	/*
	$query_args = array(
			'post_type' => 'razz-apt-model',
			'posts_per_page' => $limit,
			'orderby' => $orderby,
			'order' => $order
	);
	*/

	$query_args = array(
		'post_type' => 'razz-apt-model',
		'posts_per_page' => $limit,
		'meta_key' => 'sqft',
		'orderby' => 'meta_value_num',
		'order' => $order
	);

	$query = new WP_Query($query_args);

	$bed_count = array();
	$html_array = array();

	if ($query->have_posts()) {

		$html .= "<div id='razz-model-wrapper-container' class='razz-apartment-model-basic half-wrapper fadeInBottom col-xs-12 floor-plans'>
                    <div id='razz-model-wrapper'>";

		$url_base_path = plugins_url('razz_apt_models/ajax_request.php', dirname(__FILE__));

		$row = 0;
		$galleryHtml = '';
		while ($query->have_posts()) {
			$query->the_post();

			$item_html = '';

			//meta info about the model. ACF.
			$beds = get_field('bed');
			$baths = get_field('bath');
			$sqft = get_field('sqft');

			$model_price_override = get_field('specific_price_override');
			$price = get_field('price');

			$labels = get_field('filters');
			$model_image = get_field('image');

			$beds_baths = $beds ? "$beds Bed | " : "";
			$beds_baths .= $baths ? "$baths Bath" : "";

			$model_area = "";
			$model_price = "";

			if (!in_array($beds, $bed_count)) {
				array_push($bed_count, $beds);
			}

			ksort($bed_count);

			//settings from the options page.ACF
			$model_title_class = "model-title";
			$display_sqft = get_field('show_sq_ft', "option");
			$display_price = get_field('show_price', "option");
			$display_price_label = get_field('price_override_label_model_list', "option");

			if ($display_sqft && $sqft)
				$model_area = $sqft . '<span class="nowrap"> SQ FT</span>';

			if ($display_price) {

				if ($display_price_label != "") {
					$model_price = $display_price_label;
				} else if ($model_price_override != '') {
					$model_price = $model_price_override;
				} else {
					$model_price = ($price != "") ? $price : "";
				}
			}


			$model_class = '';

//			$terms = wp_get_post_terms($query->post->ID, 'razz-apt-model-tag', array("fields" => "names"));
//			foreach ($terms as $filter) {
//				$model_class .= str_replace(" ", "-", $filter) . " ";
//			}
//
			$sanitized_model_title = sanitize_title(get_the_title()) . "_" . get_the_ID();

            $floorPlanId = $query->post->ID;



			$item_html .= '
<div id="' . $sanitized_model_title . '" class="item-contents goto-model" data-model="' . $sanitized_model_title . '" data-id="' . $floorPlanId . '">
    <div class="img-razz-model-wrapper">
        <img src="' . $model_image['sizes']['medium'] . '" data-img-hd="' . $model_image['sizes']['large'] . '" data-full-img="' . $model_image['url'] . '" class="img-responsive img-lazy valign" alt="' . $model_image['alt'] . '" />
    </div>
    <div class="details-wrapper">
        <div class="details-header">
            <h3 class="' . $model_title_class . '">' . get_the_title() . ' | <span>' . $beds_baths . '</span></h3>
        </div>
        <div class="details-footer">
            ' .  ($display_sqft ? '<div class="sqft-info">' . $model_area . '</div>' : '') .
            ($display_price ? '<div class="price-info">' . $model_price. '</div>' : '') .
        '</div>
    </div>
</div>';

			$galleryItemInnerHtml = '
<div class="details-wrapper">
	<div class="details-header">
		<h3 class="' . $model_title_class . '">' . get_the_title() . ' | <span>' . $beds_baths . '</span></h3>
	</div>
  <div class="details-footer ' . $extra_class . '">' .
		($sqft ? '<div class="sqft-info">' . $sqft . ' SQ FT</div>' : '') .
		($price ? '<div class="price-info">' . $price . '</div>' : '') . '
	</div>
</div>';

			$galleryHtml = '<div class="item" data-id="' . $floorPlanId . '">' . _get_image_as_background($model_image['id'], 'medium', 'large', false, false, false, $galleryItemInnerHtml) . '</div>';

            if(!count($html_array[$beds]))
                $html_array[$beds] = array();

			array_push($html_array[$beds], array('html' => $item_html, 'gallery-item' => $galleryHtml));

		} // END While Loop


        ksort($html_array);

        $row = 0;
        $galleryHtml = '';
		foreach ($html_array as $key => $value) {
			foreach($value as $htmlItem) {
                $model_class = (floor($row / 4) % 2 == 0) ? ' row-odd' : ' row-even'; // build chess style grid

                $html .= '<div class="grid-item-wrap item col-xs-3 ' . $model_class . '" data-index="' . $row . '">' . $htmlItem['html'] . '</div>';
                $galleryHtml .= $htmlItem['gallery-item'];
                $row++;
            }

        }
		$html .= '</div></div>';

		$html .= RenderNexaGalleryHtml($galleryHtml);
	}

	return $html;
}

function RenderNexaGalleryHtml($carouselHtml)
{
	$filtersHtml = '';
    $print_pdf_link = '/wp-admin/admin-ajax.php';

	$html = '<div class="gallery-carousel floor-plans">
    <div class="my-slider">' . $carouselHtml . '</div>
    <div class="owl-nav gallery-preview-controls">
        <a class="primary-btn fa contact" href="/contact" data-slug="contact" data-title="Contact Us">
            <i class="fa fa-phone" aria-hidden="true"></i>
        </a>
        <a class="primary-btn fa print" href="' . $print_pdf_link . '">
            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
        </a>
        <a class="primary-btn gallery">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </a>
        <a class="primary-btn btn-arrow prev">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </a>
        <a class="primary-btn btn-arrow next">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </a>

    </div>
</div>
<div id="gallery-controls">
    <ul class="gallery-filters">' . $filtersHtml . '</ul>
    <div class="gallery-page-control">
        <a class="primary-btn gallery-filter">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </a>
    </div>
</div>';

	return $html;
}
