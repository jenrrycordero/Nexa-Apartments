<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
	<link href="<?php echo get_template_directory_uri(); ?>/assets/img/touch.png" rel="apple-touch-icon-precomposed">
	<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php bloginfo('description'); ?>">

	<?php wp_head(); ?>
	<script>
		<?php
		/*
		 * documentation for conditionizr: https://github.com/conditionizr/conditionizr/blob/master/docs/DOCS.md#conditionizr-v400-docs
		 *
		 * you can use beside class: 'style', 'script' to load the safari.js and safari.css script/style from the asset folder.
		 */

		/*
		 IOs touch library.... investigate.
		 conditionizr.load('//cdnjs.cloudflare.com/ajax/libs/hammer.js/1.0.5/hammer.min.js', ['ios']);
		*/
		?>
		// conditionizr.com
		conditionizr.config({
			assets: '<?php echo get_template_directory_uri(); ?>/assets/conditionizr/',
			tests: {
				safari: ['class'],
				chrome: ['class'],
				firefox: ['class'],
				opera: ['class']
			}
		});
		conditionizr.polyfill('//html5shiv.googlecode.com/svn/trunk/html5.js', ['ie6', 'ie7', 'ie8']);
		conditionizr.polyfill('//oss.maxcdn.com/respond/1.4.2/respond.min.js', ['ie6', 'ie7', 'ie8']);

		//isMobile features.
		var extraClass = "";
		if ( isMobile.phone ) extraClass += " phone";
		if ( isMobile.apple.phone ) extraClass += " iphone";
		if ( isMobile.apple.tablet ) extraClass += " ipad";
		document.getElementsByTagName("html").className += extraClass;
	</script>
	<?php
		the_field('custom_css',             'option');
		the_field('custom_js_header',       'option');
		the_field('custom_gtm_tracking',    'option');
		the_field('typekit_script',         'option');
		the_field('custom_css');
		the_field('custom_js');
	?>

</head>
<body id="page-wrapper" <?php body_class(); ?>>

<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>

<script type="text/javascript">
    (function(a,e,c,f,g,h,b,d){var k={ak:"856404320",cl:"Kxv9CI7clXAQ4OKumAM",autoreplace:"480-900-1466"};a[c]=a[c]||function(){(a[c].q=a[c].q||[]).push(arguments)};a[g]||(a[g]=k.ak);b=e.createElement(h);b.async=1;b.src="//www.gstatic.com/wcm/loader.js";d=e.getElementsByTagName(h)[0];d.parentNode.insertBefore(b,d);a[f]=function(b,d,e){a[c](2,b,k,d,null,new Date,e)};a[f]()})(window,document,"_googWcmImpl","_googWcmGet","_googWcmAk","script");
</script>

<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 856404320;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<div style="display: none">
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion_async.js" charset="utf-8"></script>
</div>

<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/856404320/?guid=ON&amp;script=0"/>
    </div>
</noscript>

	<!-- wrapper -->
	<div id="content-wrapper">
		<?php include( theme_get_menu("nexa") ); ?><!--        Load NEXA Header-->
        <div id="nexa-app-wrapper">
