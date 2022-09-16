<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Martfury
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="icon" type="image/x-icon" href="<?php bloginfo('template_directory'); ?>/images/logo-f.ico">
	



	<?php wp_head(); ?>

	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/custom.css" />
	<!--script type='text/javascript' src='http://box2462.temp.domains/~ethioco3/staging/3059/wp-content/plugins/wc-frontend-manager/includes/libs/leaflet/leaflet-search.js' id='wcfm-leaflet-search-js-js'></script>
<script type='text/javascript' src='http://box2462.temp.domains/~ethioco3/staging/3059/wp-content/plugins/wc-frontend-manager/includes/libs/multi-input/wcfm-script-multiinput.js' id='wcfm_multiinput_js-js'></script>
<script type='text/javascript' src='http://box2462.temp.domains/~ethioco3/staging/3059/wp-content/plugins/wc-frontend-manager/includes/libs/multi-input/wcfm-script-multiinput.js?ver=6.6.1' id='wcfm_multiinput_js-js'></script>

<script type="text/javascript" src="http://box2462.temp.domains/~ethioco3/staging/3059/wp-content/plugins/wc-frontend-manager/assets/js/settings/wcfm-script-wcfmmarketplace-settings.js" id="wcfm_multiinput_test" /></script-->

</head>

<body <?php body_class(); ?>>
<?php martfury_body_open(); ?>

<div id="page" class="hfeed site">
	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
		?>
		<?php do_action( 'martfury_before_header' ); ?>
        <header id="site-header" class="site-header <?php martfury_header_class(); ?>">
			<?php do_action( 'martfury_header' ); 	?>
        </header>
	<?php } ?>
	<?php do_action( 'martfury_after_header' ); ?>

    <div id="content" class="site-content">
		<?php do_action( 'martfury_after_site_content_open' ); ?>
	
