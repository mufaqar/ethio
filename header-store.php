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

	


</head>

<body <?php body_class(); ?>>
<?php martfury_body_open();

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

$wcfm_store_url    = wcfm_get_option( 'wcfm_store_url', 'store' );
$wcfm_store_name   = apply_filters( 'wcfmmp_store_query_var', get_query_var( $wcfm_store_url ) );
if ( empty( $wcfm_store_name ) ) return;
$seller_info       = get_user_by( 'slug', $wcfm_store_name );
if( !$seller_info ) return;

$store_user        = wcfmmp_get_store( $seller_info->ID );
$store_info        = $store_user->get_shop_info();

$store_sidebar_pos = isset( $WCFMmp->wcfmmp_marketplace_options['store_sidebar_pos'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_sidebar_pos'] : 'left';

$wcfm_store_wrapper_class = apply_filters( 'wcfm_store_wrapper_class', '' );

$wcfm_store_color_settings = get_option( 'wcfm_store_color_settings', array() );
$mob_wcfmmp_header_background_color = ( isset($wcfm_store_color_settings['header_background']) ) ? $wcfm_store_color_settings['header_background'] : '#3e3e3e';

//print "<pre>";
//print_r($store_info);
//print "</pre>";

//echo $wcfm_store_url;

?>


<div id="page" class="hfeed site">
	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
		?>
		<?php do_action( 'martfury_before_header' ); ?>
        <header id="site-header" class="site-header <?php martfury_header_class(); ?>">
			


<div class="main-menu hidden-xs hidden-sm">
    <div class="<?php echo martfury_header_container_classes(); ?>">
        <div class="row header-row">
			<div class="col-md-12 col-sm-12 mr-header-menu">
                <div class="col-header-menu">
					<div class="primary-nav nav">							
						<a href="<?php bloginfo('url'); ?>" class="backstore"> Back to ethio.com<a>
						</div>
				

				<div class="header-bar topbar">
					<div id="block-4" class="widget widget_block store_reg"><p><a href="<?php echo home_url('/'); ?>become-a-vendor">
						<?php _e('Create your Own Store','martfury'); ?></a></p>
					</div> 
						
					</div>                   
                </div>
				</div>

            </div>
        </div>
    </div>
</div>



 
        </header>
	<?php } ?>
	<?php //do_action( 'martfury_after_header' ); ?>

    <div id="content" class="site-content pt-0" style="padding-top:0px">
		<?php do_action( 'martfury_after_site_content_open' ); ?>
	
