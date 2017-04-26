var $tabletPortrait = 860;
var $safariMenuHeaderHeight = 68;

jQuery(document).ready(function ($) {

  jQuery('#page-content-wrapper #loader').fadeOut();

  var RAZZ_APP = function() {};
  // this array store loaded posts, to improve speed of loaded pages
  var AJAXCache = {};

  RAZZ_APP.prototype = {

    name: '',

    $page_wrapper:            $('#page-wrapper'),
    $content_wrapper:         $('#content-wrapper'),
    $page_content_wrapper:    $('#page-content-wrapper'),
    $banner_content_wrapper:  $('.banner-content-wrapper'),
    $ajax_content_wrapper:    $('.ajax-content-wrapper'),

    /*######  BUTTONS  ###########*/
    $nav_menu_toggle:     $('.menu-toggler'),
    $main_menu_toggle:    $('.main-menu-toggler'),
    $close_page_toggle:   $('.close-page-toggler'),
    $contact_page_toggle: $('.contact-form-toggler'),

    /*######  METHODS ###########*/
    _initApp: function() {

      this.name = 'NEXA Apartments';

      // Header Menu Toggle
      this._initSideMenuToggle();
      // Side Menu Items
      this._initSideMenuItems();
      // Home Page Main Menu Toggle
      this._initMainMenuToggle();

      this._initFadeInElements();

      this._initContactPageToggle();

      // Home Page Main Menu
      this._initMainMenuItems();
      // Close Page Button Toggle
      this._initClosePageButton();
      // Header Logo Toggle
      this._initHeaderLogoButton();

      this.initNBHDToggle();

      this.initGallery();

    },
    _initSideMenuToggle: function() {
      var RazzApp = this;
      RazzApp.$nav_menu_toggle.click(function() {
        RazzApp.$page_wrapper.toggleClass('toggled');
      });
    },
    _initSideMenuItems: function() {
      var RazzApp = this;

      // Main Menu Item Click Event
      $('#sidebar-menu .menu-item-object-page a').on('click', function () {

        var $menuItem = $(this);
        var pathname = $menuItem[0].pathname;
        var slug = pathname.replace(/\/+/g, '').replace(/\/+$/, '');

        if (isMobile()){
          jQuery('#page-content-wrapper #loader').fadeIn(500);
          RazzApp.$content_wrapper.addClass('visible loading');
          RazzApp.$ajax_content_wrapper.html('');
        }

        if (slug !== '') {
          RazzApp.formatPageParameters($menuItem);
        }
        else {
          RazzApp.$page_wrapper.removeClass('toggled');

            RazzApp.resetPageUrl();
            RazzApp.$page_wrapper.css({overflow: ''});

          RazzApp.showHomePage(false);
        }
      });

    },
    _initMainMenuToggle: function() {
      var RazzApp = this;

      RazzApp.$main_menu_toggle.click(function() {

        RazzApp.$banner_content_wrapper
            .animate({scrollTop: 0}, 0)
            .toggleClass('toggled')
            .trigger('classToggled');

        RazzApp.$ajax_content_wrapper.html('');
      });

    },

    _initFadeInElements: function() {

      var razzApp = this;

      razzApp.$banner_content_wrapper.bind('classToggled', function(){

        var isToggled = razzApp.$banner_content_wrapper.hasClass('toggled');
        var width     = isToggled ? ($(window).width() /2) + 'px' : 0;

        if (isMobile() && width !== 0) {
          width = $(window).width();

          if (isFirefox() && isWindows()) {
            width -= 17;
          }
          width = width  + 'px';
        }


        TweenLite.to($('.main-menu.fadeInLeft'), 0.6, {"transform" : "translate(" + width + ",0)"});

        TweenLite.to($('.main-menu.fadeInRight'), 0.6, {"transform" : "translate(-" + width + ",0)"});

      });

      razzApp.$content_wrapper.bind('classLoaded', function() {

        var isToggled = razzApp.$content_wrapper.hasClass('loaded');
        var $currentWindowScroll = $(window).scrollTop();
        var $windowHeight = $(window).height();

        var windowsScrollBar = isWindows() ? window.innerWidth - $(window).width() : 0;
        var width     = isToggled ? ($(window).width() /2) + (windowsScrollBar / 2) + 'px' : 0;
        var height = ($currentWindowScroll < $windowHeight) ? $windowHeight : $currentWindowScroll + $windowHeight;
        var fadeOutDelay = 0;

        var isContactPage = $('.ajax-content-wrapper .fadeInBottom').hasClass('contact');

        height    = !isToggled ? height+ 'px' : 0;

        if (isContactPage) {
          height = isToggled ? '-100vh' : 0;
          fadeOutDelay = 0.6;
        }

        $('.contact-form-toggler').toggle(!isContactPage);

        if (isMobile() && width !== 0)
          width = $(window).width() - windowsScrollBar + 'px';

        TweenLite.to($('.ajax-content-wrapper .gallery .fadeInLeft'), 0.6, {"transform" : "translate(" + width + ",0)"});

        TweenLite.to($('.ajax-content-wrapper .gallery .fadeInRight'), 0.6, {"transform" : "translate(-" + width + ",0)"});

        TweenLite.to($('.ajax-content-wrapper .fadeInBottom'), 0.8, {"delay" : fadeOutDelay, "transform" : "translate(0," + height + ")"});

      });

      razzApp.$ajax_content_wrapper.bind('galleryPreview', function() {

        var isToggled = !razzApp.$ajax_content_wrapper.hasClass('gallery-preview');
        var $currentWindowScroll = $(window).scrollTop();
        var $windowHeight = $(window).height();

        var windowsScrollBar = isWindows() ? window.innerWidth - $(window).width() : 0;
        var width     = isToggled ? ($(window).width() /2) + (windowsScrollBar / 2) + 'px' : 0;
        var height = ($currentWindowScroll < $windowHeight) ? $windowHeight : $currentWindowScroll + $windowHeight;

        height    = !isToggled ? height + 'px' : 0;

        if (isMobile() && width !== 0)
          width = $(window).width() - windowsScrollBar + 'px';


        if ($('.ajax-content-wrapper .fadeInBottom').hasClass('contact')) {
          height = !isToggled ? -$windowHeight + 'px' : 0;
        }


        TweenLite.to($('.ajax-content-wrapper .gallery .fadeInLeft'), 0.6, {"transform" : "translate(" + width + ",0)"});

        TweenLite.to($('.ajax-content-wrapper .gallery .fadeInRight'), 0.6, {"transform" : "translate(-" + width + ",0)"});

        TweenLite.to($('.ajax-content-wrapper .fadeInBottom'), 0.8, {"transform" : "translate(0," + height + ")"});

      });

      razzApp.$page_wrapper.bind('homeFadeInContent', function() {

        var tl = new TimelineLite();
        tl.staggerFrom([
          '.home-wrapper .logo > a',
          '.home-wrapper .logo > h1',
          '.home-wrapper .logo .contact',
          '.main-menu-toggler',
          '.top-navigation-menu',
          '.contact-form-toggler'
        ], 1, {top : 30+'vh', autoAlpha:0}, 0.3, "stagger");

        tl.play();

        TweenMax.fromTo($('.home-wrapper .scroll-page-toggle'), 0.3, {opacity: 0}, {opacity: 1, delay: 2});
        TweenMax.fromTo($('#nexa-app-wrapper .main-banner'), 4, {scale: 1.2}, {scale: 1});

      });

    },

    _initContactPageToggle: function() {

      var RazzApp = this;

      RazzApp.$contact_page_toggle.on('click', function() {
        RazzApp.getPageContent($(this).data());
      });

      InitContactPage();

    },

    _initClosePageButton: function() {

      var RazzApp = this;

      RazzApp.$close_page_toggle.on('click', function() {

        RazzApp.resetPageUrl();
        RazzApp.showHomePage(true);

      });
    },

    _initHeaderLogoButton: function() {

      var RazzApp = this;

      $('.site-branding > a').on('click', function() {

        RazzApp.$page_wrapper.css({overflow: ''});
        RazzApp.resetPageUrl();
        RazzApp.showHomePage(false);

      });

    },

    _initMainMenuItems: function() {

      var RazzApp = this;

      $('.half-wrapper.main-menu a.inner-wrapper').on('click', function () {
        RazzApp.getPageContent($(this).data());
      });
    },

    showHomePage: function(hideMainMenu) {

      var RazzApp = this;

      RazzApp.$content_wrapper.css({overflow: ''});
      RazzApp.$content_wrapper.animate({scrollTop: 0}, 0);

      RazzApp.$banner_content_wrapper.find('.main-menu').toggle(true);

      // Reset Gallery Preview

      RazzApp.$banner_content_wrapper
        .animate({scrollTop: 0}, 0)
        .toggleClass('toggled', hideMainMenu)
        .trigger('classToggled');

      setTimeout(function () {
        RazzApp.$ajax_content_wrapper
          .html('')
          .removeClass('loaded')
          .trigger('classLoaded');

        RazzApp.$content_wrapper.removeClass('visible loading');
        jQuery('#page-content-wrapper #loader').fadeOut(500);
      }, 800);
    },

    resetPageUrl: function() {

      this.$content_wrapper
        .removeClass('loaded')
        .trigger('classLoaded');

      this.$ajax_content_wrapper
        .removeClass('nbhd-toggled gallery-preview');

      History.replaceState(null, this.name, '/');
    },

    formatPageParameters: function($element) {

      var RazzApp = this;

      var pathname = $element[0].pathname;
      var slug = pathname.replace(/\/+/g, '').replace(/\/+$/, '');
      var href = $element.attr('href');

      var elData = {
        action: 'get_page_content',
        post: -1,
        slug: slug,
        title: $element.html()
      };


      RazzApp.$content_wrapper
        .css({overflow: ''});

      RazzApp.$page_wrapper
        .removeClass('toggled');

      jQuery('#page-content-wrapper #loader').fadeIn(500);
      setTimeout(function () {
        RazzApp.$content_wrapper
          .addClass('loading visible')
          .removeClass('loaded')
          .trigger('classLoaded');
      }, 800);


      setTimeout(function () {
        RazzApp.$content_wrapper
          .removeClass('visible');

        RazzApp.$ajax_content_wrapper
          .removeClass('nbhd-toggled gallery-preview');

        RazzApp.$banner_content_wrapper
          .css({overflow: ''});

        RazzApp.$page_wrapper.css({overflow: ''});
        RazzApp.getPageContent(elData);
      }, 600);
    },

    getPageContent: function(elData) {

      var RazzApp = this;
      var ajax_data = {
        action: "get_page_content",
        post: elData.post,
        slug: elData.slug
      };

      RazzApp.$ajax_content_wrapper
        .removeClass('switched');

      if (_.contains(['neighborhood', 'blog'], elData.slug)) {
        RazzApp.$ajax_content_wrapper
          .addClass('switched');
      }

      jQuery('#page-content-wrapper #loader').fadeIn(500);



      RazzApp.$content_wrapper
        .addClass('loading visible');

      setTimeout(function () {

        RazzApp.$banner_content_wrapper
          .toggleClass('toggled', false)
          .trigger('classToggled')
          .animate({scrollTop: 0}, 0);
      }, 800);

      $('html, body').animate({scrollTop: 0}, 0);
      RazzApp.$content_wrapper.animate({scrollTop: 0}, 0);

      // AJAX REQUEST
      $.ajax({
          url: ajax_object.ajax_url,
          data: ajax_data
      })
      .done(function (data) {
        try {
          data = JSON.parse(data);
        }
        catch (e) {
          return false;
        }

        if (data.status == 1) {
          AJAXCache[ajax_data.post] = {data: data, elData: elData};
          RazzApp.setPageContent(data, elData);
        }
      })
      .fail(function (data) {
        console.log('failed to load data');
      });
      // AJAX REQUEST END
    },

    setPageContent: function(data, elData) {

      var RazzApp = this;

      RazzApp.$ajax_content_wrapper.html(data.html);

      RazzApp.$page_content_wrapper
        .toggleClass('scrollable', data.pages > 1);

      replace_images();


      RazzApp.initGallery();
      RazzApp.initNBHDToggle();

      $('.gfield.hasDatepicker .datepicker').datepicker();


      setTimeout(function () {

        RazzApp.$content_wrapper
          .addClass('loaded')
          .trigger('classLoaded');

        setTimeout(function () {
          RazzApp.$content_wrapper.removeClass('loading');

          jQuery('#page-content-wrapper #loader').fadeOut(500);
          RazzApp.$banner_content_wrapper.find('.main-menu').toggle(false);
          InitFadeInContent();
        }, 800);
        History.replaceState(null, RazzApp.name, '/');
        History.pushState({postId: elData.post}, elData.title + ': ' + RazzApp.name, elData.slug);
      }, 500);
    },

    /*######  GALLERY METHODS ###########*/

    initGallery: function() {

      var RazzApp = this;
      /*######  GALLERY WRAPPERS ###########*/
      RazzApp.$galleryCarousel = $('.gallery-carousel');
      RazzApp.$galleryGridItem = $('.grid-item-wrap');
      RazzApp.$currentFlPlanId = 0;

        /*######  GALLERY BUTTONS  ###########*/
      RazzApp.$galleryButton  = $('.gallery-preview-controls .primary-btn.gallery');
      RazzApp.$prevButton     = $('.gallery-preview-controls .primary-btn.prev');
      RazzApp.$nextButton     = $('.gallery-preview-controls .primary-btn.next');
      RazzApp.$filtersButton  = $('.gallery-preview-controls .primary-btn.gallery-filter');
      RazzApp.$printButton    = $('.gallery-preview-controls .primary-btn.print');
      RazzApp.$contactButton  = $('.gallery-preview-controls .primary-btn.contact');

      RazzApp.$carousel = jQuery('.my-slider').owlCarousel({
        items:1,
        loop:true,
        nav: false
      });

      RazzApp.initGalleryControls();

      RazzApp.$galleryGridItem.on('click', function(){

        var $windowHeight         = $(window).height();
        var $currentWindowScroll  = $(window).scrollTop();
        var gridIndex             = $(this).data('index');
        var floorPlan             = $(this).find('.goto-model');
        var floorPlanId           = floorPlan.data('id');

        RazzApp.$printButton.attr('data-id', floorPlanId);
        RazzApp.$currentFlPlanId = floorPlanId;

        // Close Side Menu if is opened
        RazzApp.$content_wrapper.removeClass('toggled');

        if (isMobile()) {

          var $additionalHeight = 0;
          $currentWindowScroll = RazzApp.$content_wrapper.scrollTop();
          if ($currentWindowScroll < $windowHeight) {
            var containerHeight = jQuery('#nexa-overlay').height();
            RazzApp.$content_wrapper.animate({scrollTop: containerHeight}, 300);
            $windowHeight = containerHeight;
          }

          RazzApp.$content_wrapper
            .css({overflow: 'hidden'});

          RazzApp.$galleryCarousel.css({
            top: ($currentWindowScroll < $windowHeight) ? $windowHeight : $currentWindowScroll,
            opacity: 1
          });

        }
        else {
          $('html, body').animate({scrollTop: ($currentWindowScroll < $windowHeight) ? $windowHeight : $currentWindowScroll}, 300);

          RazzApp.$galleryCarousel.css({
            top: ($currentWindowScroll < $windowHeight) ? 0 : $currentWindowScroll - $windowHeight,
            opacity: 1
          });

          $('body').css({overflow: 'hidden'});
        }


        RazzApp.$carousel.trigger('to.owl.carousel',[gridIndex,0]);
        replace_images();

        setTimeout(function () {
          RazzApp.$ajax_content_wrapper
            .addClass('gallery-preview')
            .trigger('galleryPreview');

          RazzApp.$carousel.trigger('refresh.owl.carousel');
        }, 100);
      });

    },
    initGalleryControls: function() {

      var RazzApp = this;

      // init gallery filter button
      RazzApp.$filtersButton.on('click', function () {
        jQuery('#gallery-controls').toggleClass('toggled');
        jQuery(this).toggleClass('close gallery-filter');
      });

      RazzApp.$prevButton.on('click', function () {
        RazzApp.$carousel.trigger('prev.owl.carousel',[500]);
        RazzApp.updateFloorPlanId();
      });

      RazzApp.$nextButton.on('click', function () {
        RazzApp.$carousel.trigger('next.owl.carousel',[500]);
        RazzApp.updateFloorPlanId();
      });


      RazzApp.$galleryButton.on('click', function () {

        RazzApp.$banner_content_wrapper.css({overflow: ''});
        RazzApp.$content_wrapper.css({overflow: ''});

        if (!isMobile())
          jQuery('body').css({overflow: ''});

        //jQuery('.gallery .fadeInBottom').css({top: 0});

        RazzApp.$ajax_content_wrapper
          .removeClass('gallery-preview')
          .trigger('galleryPreview');
      });

      $('.gallery-preview-controls .print').on('click', function (e) {

        e.preventDefault();

        var floorPlanId = RazzApp.$currentFlPlanId;

        if (floorPlanId > 0) {
          var href = '/wp-admin/admin-ajax.php?action=model_print_pdf&model=' + floorPlanId;
          window.open(href, '_blank'); // <- This is what makes it open in a new window.
        }
      });

      RazzApp.$contactButton.on('click', function(e){

        var $element = $(this);

        e.preventDefault();

        if (isMobile())
          RazzApp.$ajax_content_wrapper.animate({scrollTop: 0}, 300);
        else
          $("html, body").animate({scrollTop: 0}, 300);

        setTimeout(function () {
          RazzApp.$page_wrapper.removeClass('toggled');
          RazzApp.formatPageParameters($element);
        },400);
      });
    },

    updateFloorPlanId: function() {

      var RazzApp = this;
      var currentSlide  = RazzApp.$carousel.find('.owl-item.active .item')[0];
      var floorPlanId   = $(currentSlide).attr('data-id');


      console.log(floorPlanId);

      if (floorPlanId > 0)
        RazzApp.$currentFlPlanId = floorPlanId;

    },

    initNBHDToggle: function() {
      var RazzApp = this;

      // NBHD Toogle Click
      RazzApp.$ajax_content_wrapper.find('.arrow-toggler .btn-arrow').on('click', function () {

        RazzApp.$ajax_content_wrapper
          .toggleClass('nbhd-toggled');

        if (!isMobile()) {
          RazzApp.$nav_menu_toggle.toggle();
        }
      });
    }
  };

  /* ####################### Initialization of NEXA APP #################### */

  var NEXA = new RAZZ_APP();
  NEXA._initApp();

  jQuery(window).load(function () {
    replace_images();

    setTimeout(function() {
      jQuery('#nexa-overlay').fadeOut(500);

      if (!jQuery('#page-wrapper').hasClass('home'))
        InitPageTemplateLayout();
      else
        NEXA.$page_wrapper.trigger('homeFadeInContent');

      InitFadeInContent();
    }, 800);

    setTimeout(function() {
      $videoBg = jQuery("#video-background");
      if ( $videoBg.length > 0 )
        $videoBg.YTPlayer();
    }, 100);
  });

  if (isSafariBrowser()) {
    jQuery('#page-wrapper').addClass('safari-browser');
  }

  // AJAX Scroll Button Click Event
  jQuery('.scroll-page-toggle .primary-btn').on('click', function () {
    var windowHeight = jQuery(window).height();

    var index = $(this).data('index');

    if ( index > 0 )
      windowHeight = index * windowHeight;

    jQuery('html body').animate({scrollTop: windowHeight}, 800);
  });

  // bind Contact Form Rendering
  jQuery(document).bind('gform_post_render', function () {
    // code to trigger on AJAX form render
    InitContactPage();
    return false;
  });

  // Disable all Clicks
  jQuery('#page-wrapper a').on("click", function (e) {
    e.preventDefault();

    var href = jQuery(this).attr('href');
    if (is_external(this)) {
      if (href !== undefined){
        window.open(href, '_blank'); // <- This is what makes it open in a new window.
      }
    }
    else
      return false;
  });
});



jQuery(window).resize(function() {

  jQuery('.ajax-content-wrapper')
    .removeClass('nbhd-toggled gallery-preview');

  jQuery('#content-wrapper')
    .trigger('classLoaded');

  jQuery('.banner-content-wrapper')
      .css({overflow: ''})
      .trigger('classToggled');

  jQuery('body').css({overflow: ''});

  // Init Parallax Containers
  jQuery('.gallery-carousel').css({top: 0});

  if (isMobile()) {
    jQuery('#content-wrapper.loaded .menu-toggler').toggle(true);
  }
});

function InitPageTemplateLayout() {

  jQuery('.ajax-content-wrapper').removeClass('switched');
  var pathname = location.pathname.replace(/\/+/g, '').replace(/\/+$/, '');
  if (_.contains(['neighborhood', 'blog'], pathname))
    jQuery('.ajax-content-wrapper').addClass('switched');

  jQuery('#content-wrapper').addClass('visible');
  jQuery('.banner-content-wrapper .main-menu').toggle(false);
  replace_images();
  setTimeout(function () {

    jQuery('#content-wrapper')
      .addClass('loaded')
      .trigger('classLoaded');
  }, 500);
}


function InitContactPage() {
  select_count = 0;
  jQuery('.hide-select').each(function (event) {
    select_count++;
    var source = jQuery(this).find(".gfield_select");
    var selected = source.find("option[selected]");  // get selected <option>
    var options = jQuery("option", source);  // get all <option> elements
    jQuery(this).find(".ginput_container").append('<dl id="sample' + select_count + '" class="dropdown"></dl>');
    jQuery("#sample" + select_count).append('<dt><a href="#">' + selected.text() + '<span class="value">' + selected.val() + '</span></a><span class="fa fa-angle-down"></span></dt>');
    jQuery("#sample" + select_count).append('<dd><ul></ul></dd>');

    // iterate through all the <option> elements and create UL
    options.each(function () {
      jQuery("#sample" + select_count + " dd ul").append('<li><a href="#">' + jQuery(this).text() + '<span class="value">' + jQuery(this).val() + '</span></a></li>');
    });

    jQuery(".dropdown img.flag").addClass("flagvisibility");

    jQuery('.hasDatepicker .ginput_container').append('<span class="fa fa-angle-down"></span>');
  });

  jQuery(".dropdown dt a").click(function () {
    var dropdown = jQuery(this).closest('.dropdown');

    jQuery(dropdown).find("dd ul").toggle();
    return false;
  });

  jQuery(".dropdown dd ul li a").click(function () {
    var text = jQuery(this).html();
    var dropdown = jQuery(this).closest('.dropdown');

    jQuery(dropdown).find('dt a').html(text);
    jQuery(dropdown).find("dd ul").hide();
    var source = jQuery(dropdown).closest(".hide-select .gfield_select");
    source.val(jQuery(this).find("span.value").html());
    return false;
  });

  jQuery(document).bind('click', function (e) {
    var $clicked = jQuery(e.target);
    if (!$clicked.parents().hasClass("dropdown"))
      jQuery(".dropdown dd ul").hide();
  });


  jQuery("#flagSwitcher").click(function () {
    jQuery(this).find(".dropdown img.flag").toggleClass("flagvisibility");
  });
}

/**
 * Function to replace images on the background or on the image itself.
 */
function replace_images() {

  /** IMG tag. Replace the image sd with the one with hd quality       **/
  jQuery(".img-lazy").each(function () {
    var $this = jQuery(this);
    var img_hd = $this.data("img-hd");

    if (img_hd && !$this.is(".img-lazy-loaded")) {
      jQuery("<img/>", {
        src: img_hd
      })
        .data("target", $this)
        .load(function () {
          $imgAssociated = jQuery(this).data("target");
          $imgAssociated.attr('src', jQuery(this).attr("src")).addClass("img-lazy-loaded");
        });
    }
  });

  /** replace the background image sd with the one with hd quality **/
  jQuery(".img-bg-lazy").each(function () {
    var $this = jQuery(this);
    var img_hd = $this.data("img-hd");

    if (img_hd && !$this.is(".img-lazy-loaded")) {
      jQuery("<img/>", {
        src: img_hd
      })
        .data("target", $this)
        .load(function () {
          $imgAssociated = jQuery(this).data("target");
          $imgAssociated.css({
            'backgroundImage': "url('" + jQuery(this).attr("src") + "')"
          }).addClass("img-lazy-loaded");
        });
    }
  });
}

function is_external(link_element) {
  var linkHost = link_element.host;
  linkHost = linkHost.replace(':80', '');
  return (linkHost !== window.location.host);
}

function switchWindowWidth() {
  return 0.9;
}

function isMobile() {
  return jQuery(window).width() < $tabletPortrait || jQuery(window).height() < 540;
}

// detect only Mobile Safari browser
function isSafariBrowser() {
  var userBrowser = navigator.userAgent;
  return (userBrowser.indexOf("Chrome") < 0 && userBrowser.indexOf("Safari") > -1);
}

function isWindows() {
  var platform = navigator.platform.toUpperCase();
  return platform.indexOf('WIN32') > -1;
}

function isFirefox() {
  var userBrowser = navigator.userAgent;
  return userBrowser.indexOf("Firefox") > -1;
}

function InitFadeInContent() {
  var GeneralController = new ScrollMagic.Controller();
  var winDiv3neg = (jQuery(window).height() / 4);


  if (!isMobile()) {
    // build scene
    var scenes = [];
    jQuery('.page .half-wrapper').each(function () {

      var el = jQuery(this);
      var sceneActions = [
        TweenMax.staggerFromTo([
          el.find('.wrapper-cell h2'),
          el.find('.wrapper-cell h1'),
          el.find('.wrapper-cell h4'),
          el.find('.wrapper-cell h3'),
          el.find('.wrapper-cell div.to-appear')
        ], 1, {opacity: 0, y: 300}, {opacity: 1, y: 0}, 0.15),
        TweenLite.to(el, 1, {className: "+=on-screen"})
      ];


      var scene = new ScrollMagic.Scene({
        triggerElement: this,
        duration: 0,  // the scne should last for a scroll distance of 100px
        offset: -winDiv3neg   // start this scene after scrolling for 50px
      });
      scene.setTween(sceneActions);

      scenes.push(scene);
    });

  }

    var contactFormTimeline = new TimelineLite();
    contactFormTimeline.staggerFromTo([
      '#contact-form-wrapper .logo',
      '#contact-form-wrapper h1',
      '#contact-form-wrapper .contact',
      '#contact-form-wrapper .gform_wrapper'
    ], 1, {autoAlpha: 0, top: 30 + 'vh'}, {autoAlpha: 1, top: 0}, 0.2, "stagger");


    var contactFormScene = new ScrollMagic.Scene({
      triggerElement: jQuery('#contact-form-wrapper'),
      duration: winDiv3neg,  // the scne should last for a scroll distance of 100px
      offset: -winDiv3neg   // start this scene after scrolling for 50px
    }).on('start', function () {
      contactFormTimeline.play();
    });

    scenes.push(contactFormScene);

    GeneralController.addScene(scenes);
}

