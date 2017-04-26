<?php
function renderMainMenuSection()
{
    $mainMenuPages = get_field('pages', 'options');

    $index = 1;
    $menuHtml = '';
    $html = '';
    $columnClasses = array('fadeInLeft', 'fadeInRight');

    foreach ($mainMenuPages as $menuItem) {
        $postId = $menuItem['page'];
        $layouts = get_field('layouts', $postId);
        if (count($layouts)) {
            $layout = $layouts[0];
            $bg_image = $layout['background_image'];
            $title = get_the_title($postId);
            $slug = get_post_field('post_name', $postId);

            $innerHtml = '
			<div class="col-xs-12"><a href="/' .  $slug . '" class="inner-wrapper" data-post="' . $postId . '" data-slug="' . $slug . '" data-title="' . $title . '"><div class="title">' . $title . '</div></a></div>';
            $html .= _get_image_as_background($bg_image, 'medium', 'full', false, 'col-xs-6', false, $innerHtml);

            if ($index % 2 == 0 || $index == count($mainMenuPages)) {
                $class = $columnClasses[floor($index / 2) - 1];
                $html = '<div class="col-xs-6 main-menu half-wrapper ' . $class . '">' . $html . '</div>';
                $menuHtml .= $html;
                $html = ''; // reset content for next block
            }
            $index++;
        }
    }

    echo $menuHtml;
}

function renderContentWrapper()
{
    $layouts = array();

    if (!is_front_page()) {
        $postId = get_the_ID();
        $post = get_post($postId);
        $layouts = get_field('layouts', $postId);
    }

    $class = '';
    if (count($layouts) > 1)
        $class = 'scrollable';

    // Logo Image
    $headerLogoImageId = get_field('main_logo', 'options');
    $loaderHtml = '
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
                <div class="bounce4"></div>';
    ?>

    <div id="page-content-wrapper" class="<?php echo $class;?>">
        <div id="loader">
            <?php echo _get_image_as_background($headerLogoImageId, 'medium', 'full', false, 'spinner', false, $loaderHtml); ?>
        </div>
        <div class="controls">
            <div class="close-page-toggler">
                <a class="primary-btn close">
                    <span class="bar"></span>
                    <span class="bar"></span>
                </a>
            </div>
            <div class="scroll-page-toggle">
                <a class="primary-btn scroll">
                    <span class="mouse"></span>
                </a>
            </div>
        </div>
        <div class="ajax-content-wrapper">
            <?php
                if (!is_front_page())
                    echo renderPageContentByPostID($layouts, $post);
            ?>
        </div>
    </div>
    <?php
}

function renderPageContentByPostID($layouts, $post, $isHomePage = false)
{
    $html = '';
    if (count($layouts)) {
        $counter = 1;
        foreach ($layouts as $layout) {
            $contentClass = 'fadeInRight';
            $customClass = 'fadeInLeft';
            $hideClass = '';


            if (count($layouts) > 1) {
                if ($counter % 2 == 0) {
                    $contentClass = 'fadeInRight';
                    $customClass = 'fadeInLeft';
                } else {
                    $contentClass = 'fadeInLeft';
                    $customClass = 'fadeInRight';

                    if ( is_page_slug('floor-plans', $post) || is_page_slug('gallery', $post) || is_page_slug('apartments', $post) || is_page_slug('lifestyle', $post) )
                        $hideClass = ' hiddenClass';
                }
            }

            $htmlArray = array(
                $customClass => ($layout['page_columns_render'] == 2) ? renderCustomBlock($layout, $customClass . $hideClass, $post) : '',
                $contentClass => ''
            );

            $pageClass = '';
            if ( count($layout['gallery']) || ((is_page_slug('apartments', $post) || is_page_slug('floor-plans', $post)) && $layout['floor_plans_shortcode']) )
                $pageClass = 'gallery';

            $html .= '<div class="page ' . $pageClass . '">' . $htmlArray['fadeInLeft'] . renderFadeInBlock($layout, $contentClass, $post, $isHomePage) . $htmlArray['fadeInRight'] . '</div>';
            $counter++;
        }
    }

    if (is_page_slug('neighborhood', $post)) {
        $html .= '
<style>
@media (min-width: 860px) {
    #content-wrapper.visible .top-navigation-menu .site-branding { display: none;}
    #page-content-wrapper .controls {display: none;}
}
</style>';
    }


    return $html;
}


function renderFadeInBlock($layout, $contentClass, $post, $isHomePage){

    if ($layout['page_columns_render'] == 2) {

        if ($isHomePage) {
            $contentHtml = '
<h4 class="to-appear">' . $layout['title'] . '</h4>
<h3 class="to-appear">' . $layout['subtitle'] . '</h3>
<div class="to-appear">' . $layout['description'] . '</div>';
        }
        else {
            $contentHtml = '
<h2 class="to-appear">' . $layout['title'] . '</h2>
<h1 class="to-appear">' . $layout['subtitle'] . '</h1>
<div class="to-appear">' . $layout['description'] . '</div>';
        }


        $html = '
            <div class="col-xs-6 half-wrapper ' . $contentClass . '">
                <div class="col-xs-12 wrapper">
                    <div class="inner-wrapper">
                        <div class="wrapper-cell">' . $contentHtml . '</div>
                    </div>
                </div>
            </div>';
    }
    else {

        $layoutHtml = '';
        if (is_page_slug('gallery', $post) || is_page_slug('lifestyle', $post)) {
            $layoutHtml = renderCustomBlock($layout, 'fadeInBottom gallery', $post);
        }
        else if (is_page_slug('floor-plans', $post) || is_page_slug('apartments', $post)) {
            $layoutHtml = renderCustomBlock($layout, 'fadeInBottom gallery', $post);
        }
        else if (is_page_slug('contact', $post)) {
            $layoutHtml = renderCustomBlock($layout, 'fadeInBottom contact', $post);
        }

        $html = $layoutHtml;
    }

    return $html;
}

function renderNexaGallery($layout) {

    $galleryImages = $layout['gallery'];
    $columnClasses = array('fadeInLeft', 'fadeInRight');

    $html = $galleryGridHtml = '';
    $counter = 0;
    $array = array(2,2,2,2);

    while($counter < max(floor(count($galleryImages) / 8) -1, 0)) {
        $array = array_merge($array, $array);
        $counter++;
    }
    $counter = 0;

    while (count($array) < count($galleryImages)) {
        array_pop($array);
        $array = array_merge(array(1,1),$array);
    }

    $summ = 0;
    $index = 1;
    foreach ($galleryImages as $image){

        $class = ($array[$counter] == 1) ? 'col-xs-6' : 'col-xs-12';
        $size  = ($array[$counter] == 1) ? 'gallery' : 'gallery-2';
        $summ += $array[$counter];
        $html .= '
        <div class="grid-item-wrap ' . $class . '" data-index="' . $counter . '">' .
           _get_image_as_background($image['id'], $size, 'full', false, 'grid-item') . '
        </div>';

        if ($summ == 4) {
            $key = 0;
            if ($index % 2 == 0 || $index == count($galleryImages)) {
                $key  = 1;
            }
            $class = $columnClasses[$key];
            $html = '<div class="col-xs-6 half-wrapper ' . $class . '">' . $html . '</div>';
            $galleryGridHtml .= $html;
            $html = ''; // reset content for next block
            $index++;
            $summ = 0;
        }

        $counter++;
    }

    return $galleryGridHtml;
}

function renderGalleryFiltersContainer($layout) {

    // Get Media Categories
    $gallery_filter = get_terms('category');
    $galleryImages = $layout['gallery'];

    $filtersHtml = '';
    foreach ($gallery_filter as $filter) {
        $filtersHtml .= '<li class="gallery-filter" data-slug="' . $filter->slug . '">' . $filter->name . '</li>';
    }

    $carouselHtml = '';
    foreach ($galleryImages as $image) {
        $imageCaption = get_post($image['id']);
        $imageCaptionHtml = '<h3 class="image-caption">' . $imageCaption->post_excerpt . '</h3>';
        $carouselHtml .= '<div class="item">' . _get_image_as_background($image['id'], 'medium', 'full', false, '', false, $imageCaptionHtml) . '</div>';
    }
    $html =  '
<div class="gallery-carousel">
    <div class="my-slider">'  . $carouselHtml . '</div>
    <div class="owl-nav gallery-preview-controls">
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

function renderCustomBlock($layout, $customClass = '', $post)
{
    $bg_image = $layout['background_image'];
    $NBHDShortCode = $layout['page_shortcode'];
    $customClass .= ($layout['page_columns_render'] == 2) ? ' col-xs-6' : ' col-xs-12';
    // Logo Image
    $headerLogoImageId = get_field('main_logo', 'options');
    $mainLogoImage = _get_image($headerLogoImageId, 'medium', 'full');


    if (is_page_slug('neighborhood', $post)) {
        $customBlockHtml = '
        <div class="col-xs-6 half-wrapper ' . $customClass . '">'
            . do_shortcode($NBHDShortCode) . '
            <div class="arrow-toggler">
                <div class="primary-btn btn-arrow">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </div>';
    }
    else if ((is_page_slug('gallery', $post) || is_page_slug('lifestyle', $post)) && count($layout['gallery'])) {
        $customBlockHtml = renderNexaGallery($layout) . renderGalleryFiltersContainer($layout);
    }
    else if ((is_page_slug('floor-plans', $post) || is_page_slug('apartments', $post)) && $layout['floor_plans_shortcode']) {
        $customBlockHtml = do_shortcode($layout['floor_plans_shortcode']);
    }
    else if (is_page_slug('contact', $post)) {
        $address = get_field('address', 'options');
        $addressLink = get_field('address_link-to', 'options');
        $contactNumber = get_field('contact_number', 'options');
        $officeHours = get_field('office_hours', 'options');
        $contactNumberHref = preg_replace('/\D/', '', $contactNumber);
        $contactHtml = '';

        if ($address && $addressLink)
            $contactHtml .= '<a href="' . $addressLink . '" target="_blank">' . $address . '</a>';
        if ($contactNumber)
            $contactHtml .= '<a href="tel:' . $contactNumberHref . '">' . $contactNumber . '</a>';
        if ($officeHours)
            $contactHtml .= '<div class="office-hours to-appear">' . $officeHours . '</div>';


        $innerHtml = '
<div id="contact-form-wrapper">
<div class="form-header">
    <div class="logo to-appear">' . $mainLogoImage .'</div>
    <h1 class="to-appear">' . $layout['title'] . '</h1>
    <div class="contact to-appear">' . $contactHtml . '</div>
</div>
' . do_shortcode($layout['page_shortcode']) . getContactFormConversionScript() . '
</div>';
        $customBlockHtml = '<div class="half-wrapper ' . $customClass . '">' . _get_image_as_background($bg_image, 'medium', 'full', false, 'half-wrapper bg-parallax') . $innerHtml . '</div>';
    }
    else {
        $innerHtml = _get_image_as_background($bg_image, 'medium', 'full', false, 'half-wrapper bg-parallax ');
        $customBlockHtml = '<div class="half-wrapper ' . $customClass . '">' . $innerHtml . '</div>';
    }
    return $customBlockHtml;
}

function is_page_slug($slug, $post) {
    return $slug == $post->post_name;
}

function is_page_switched($post) {
    $switchedPages = array('neighborhood', 'blog');
    return in_array($post->post_name, $switchedPages);
}

function getContactFormConversionScript() {

    return '
<script type="text/javascript">

    // Track conversion
    /* <![CDATA[ */
    var google_conversion_id = 856404320;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "BCNkCJzelXAQ4OKumAM";
    var google_remarketing_only = false;
    /* ]]> */

    // save old document.write
    var oldDocumentWrite = document.write;

    // change document.write temporary
    document.write = function(node){
      jQuery("body").append(node);
    };

    // get script
    jQuery.getScript( "//www.googleadservices.com/pagead/conversion.js", function() {
      // replace the temp document.write with the original version
    });

</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/856404320/?label=BCNkCJzelXAQ4OKumAM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
';

}



