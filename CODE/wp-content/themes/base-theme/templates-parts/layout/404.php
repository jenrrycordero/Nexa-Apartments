<?php

// Field definitions.

$bg_image = get_sub_field('background_image');                // Image
$bg_overlay = get_sub_field('background_overlay');            // Number
$extra_class = get_sub_field('extra_class');                  // text


if ($use_video) $extra_class .= " video-bg-wrapper";
$extra_class .= " bg-wrapper ";

// Logo Image
$headerLogoImageId = get_field('main_logo', 'options');
$mainLogoImage = _get_image($headerLogoImageId, 'full', 'full');

// Home Page Main Background
$homeBackgroundImage = _get_image_as_background($bg_image, 'full', 'full', false, 'main-banner');

$address = get_field('address', 'options');
$addressLink = get_field('address_link-to', 'options');
$contactNumber = get_field('contact_number', 'options');


echo $homeBackgroundImage;
?>
<section class="banner-content-wrapper">
    <div class="content-wrapper">
        <div class="home-wrapper">
            <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name');?>" rel="home"><?php echo $mainLogoImage; ?></a>
            <h1>404 - PAGE NOT FOUNDED</h1>
            <div class="contact">
                <?php
                if ($address && $addressLink)
                    echo '<a href="' . $addressLink . '" target="_blank">' . $address . '</a>';
                if ($contactNumber)
                    echo '<a href="tel:' . $contactNumber . '">' . $contactNumber . '</a>';
                ?>
            </div><!--  contact-->
        </div>
    </div>
</section>
