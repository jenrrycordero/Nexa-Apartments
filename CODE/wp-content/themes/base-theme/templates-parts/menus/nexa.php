<?php
    $headerLogoImageId = get_field('footer_logo', 'options');
    $image = _get_image($headerLogoImageId, 'full', 'full');

    $headerLogoDarkImageId = get_field('footer_logo_dark', 'options');
    $imageDark = _get_image($headerLogoDarkImageId, 'full', 'full', false, 'dark');


    $footer_copy = get_field('copyright', 'option');
    $siteBy_label = get_field('site_by_label', 'option');

// Logo Image
$headerLogoImageId = get_field('main_logo', 'options');
$loaderHtml = '
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
                <div class="bounce4"></div>';
?>


<header id="masthead" class="site-header" role="banner">
    <div class="top-navigation-menu">
        <div class="site-branding">
            <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name');?>"  rel="home"><?php echo $image . $imageDark; ?></a>
        </div><!-- .site-branding -->
        <nav id="site-navigation" class="top-navigation" role="navigation">
            <?php wp_nav_menu(array('theme_location' => 'header-menu', 'menu_id' => 'header-menu')); ?>
            <a class="menu-toggler" aria-controls="primary-menu" aria-expanded="false">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </a>
        </nav><!-- #site-navigation -->
    </div>
</header><!-- #masthead -->
<div class="side-navigation">
    <?php

    wp_nav_menu(array('theme_location' => 'sidebar-menu', 'menu_id' => 'sidebar-menu'));
    $socialMedia = array();
    $mediaList = array('facebook', 'twitter', 'spotify', 'google', 'instagram', 'youtube');

    foreach($mediaList as $mediaListItem) {
        $order = get_field($mediaListItem . '_order', 'option');
        $url = get_field($mediaListItem, 'option');

        if ( empty($url) || !$url) continue;
            $socialMedia[$order] = array('url' => $url, 'class' => 'fa-' . $mediaListItem);
    }

    ksort($socialMedia);


    if ( get_field('social_display_on_footer', 'option')) :
        echo '<ul class="social-items">';
        foreach($socialMedia as $socialMediaItem) {
            echo '
                <li class="social-media-item">
                    <a href="' . $socialMediaItem['url'] . '" class="fa ' . $socialMediaItem['class'] . '"></a>
                </li>';
        }
        echo '</ul>';
    endif;

    $footerLinks = get_field('footer_links', 'options');
    if (count($footerLinks)) :
        echo '<div class="footer-partners">';
        foreach ($footerLinks as $footerLink) {
            $target = $footerLink['open_new_tab'] ? 'target="_blank"' : '';
            echo '<a href="' . $footerLink['url'] . '" title="' . $footerLink['name'] . '" ' . $target . '>
              <img src="' . $footerLink['image'] . '" alt="' . $footerLink['name'] . '" />
            </a>';
        }
        echo '</div>';

    endif;

    ?>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="site-info">
            <?php echo  do_shortcode($footer_copy); ?>
        </div>
<!--        <div class="site-by">-->
<!--            --><?php //render_site_by($siteBy_label); ?>
<!--        </div>-->
    </footer><!-- #colophon -->
</div>
<div id="nexa-overlay">
    <div id="loader">
        <?php echo _get_image_as_background($headerLogoImageId, 'medium', 'full', false, 'spinner', false, $loaderHtml); ?>
    </div>
</div>