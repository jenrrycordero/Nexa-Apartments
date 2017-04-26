<?php

// Use Home Page Fields for all pages that don't have content
$postId = get_option('page_on_front');

$layouts = get_field('layouts', $postId);

$mainLayout = array_shift($layouts);

// Field definitions.

$bg_image   = $mainLayout['background_image'];                // Image
$use_video  = $mainLayout['use_video'];                      // True / False
$video_url  = $mainLayout['video_url'];                      // Url
$subtitle   = $mainLayout['subtitle'];                       // Text


$optionFields = get_fields('options');
$headerLogoImageId  = $optionFields['main_logo'];
$address            = $optionFields['address'];
$addressLink        = $optionFields['address_link-to'];
$contactNumber      = $optionFields['contact_number'];

// Logo Image
$mainLogoImage = _get_image($headerLogoImageId, 'medium', 'full');

if ($use_video) {
    wp_enqueue_script('theme-ytb-player', get_template_directory_uri() . '/assets/lib/youtubeBkg/jquery.mb.YTPlayer.js', null, THEME_VERSION);
    $videoHtml = '<div id="video-background" class="player" data-property="{videoURL:\'' . $video_url . '\',containment:\'.main-banner\',startAt:5,mute:true,autoPlay:true,loop:true,opacity:1,optimizeDisplay:true,showYTLogo:false,showControls:false}" ></div>';
}

// Home Page Main Background
$homeBackgroundImage = _get_image_as_background($bg_image, 'medium', 'full', false, 'main-banner', false, $videoHtml);



$contactPagePost = get_page_by_path('contact');


echo $homeBackgroundImage;
?>
<section class="banner-content-wrapper">
    <div class="content-wrapper">
        <div class="home-wrapper">
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name');?>" rel="home"><?php echo $mainLogoImage; ?></a>
                <h1><?php echo $subtitle; ?></h1>
                <div class="contact">
                <?php
                    if ($address && $addressLink)
                        echo '<a href="' . $addressLink . '" target="_blank">' . $address . '</a>';
                    if ($contactNumber)
                        echo '<a href="tel:' . $contactNumber . '">' . $contactNumber . '</a>';
                ?>
                </div><!--  contact-->
            </div>
            <div class="main-menu-toggler">
                <a class="primary-btn">
                    <span class="bar"></span>
                    <span class="bar"></span>
                </a>
            </div>
            <div class="scroll-page-toggle">
                <a class="primary-btn scroll">
                    <span class="mouse"></span>
                </a>
            </div>
            <a class="contact-form-toggler" href="/<?php echo $contactPagePost->post_name; ?>" data-post="<?php echo $contactPagePost->ID; ?>" data-slug="<?php echo $contactPagePost->post_name; ?>" data-title="<?php echo $contactPagePost->post_title; ?>">
                <div class="primary-btn btn-arrow">
                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                </div>
            </a>
        </div>
    </div>
    <div id="home-page-content">
        <?php
        $homePost = get_post($postId);
        echo renderPageContentByPostID($layouts, $homePost, true);
        ?>
    </div>
    <?php
    renderContentWrapper();
    renderMainMenuSection();
?>
</section>
<?php



