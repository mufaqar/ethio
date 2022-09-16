<?php
/* Template Name: Testing Page  */

get_header(); ?>


<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { ?>
	<div id="primary" class="content-area <?php martfury_content_columns(); ?>">


	<div class="page-header page-header-catalog">
			<div class="page-breadcrumbs">
				<div class="row">
				<div class="col-md-9">
				<?php martfury_get_breadcrumbs(); ?>
				</div>
				<div class="col-md-3">
				<?php show_page_counter(); ?>
				</div>
			</div>
			</div>		
		</div>


		<?php 

global $woocommerce;
$countries_obj   = new WC_Countries();
$countries   = $countries_obj->__get('countries');
$default_country = $countries_obj->get_base_country();

echo $default_country;
$default_county_states = $countries_obj->get_states( $default_country );

print_r($default_county_states);


?>




 

	
		<main id="main" class="site-main">	

		<?php
	/*

		global $WCFM, $WCFMmp, $post;


				$stores = $WCFMmp->wcfmmp_vendor->wcfmmp_search_vendor_list( true, $offset, $length, $search_term, $search_category, $search_data, $has_product, $includes );

				$template_args = apply_filters( 'wcfmmp_stores_args', array(
						'stores'          => $stores,
						'limit'           => $length,
						'offset'          => $offset,
						'paged'           => $paged,
						'includes'        => $includes,
						'excludes'        => $excludes,
						'image_size'      => 'full',
						'filter'          => 'yes',
						'search'          => 'yes',
						'category'        => 'yes',
						'country'         => 'yes',
						'state'           => 'yes',
						'has_product'     => $has_product,
						'search_query'    => $search_term,
						'search_category' => $search_category,
						'search'          => $search_term,
						'pagination_base' => $pagination_base,
						'orderby'         => $orderby,
						'has_orderby'     => $has_orderby,
						'per_row'         => $per_row,
						'sidebar'         => $sidebar,
						'theme'           => $theme,
						'search_data'     => $search_data
				), $_REQUEST, $search_data );


				print "<pre>";	
				print_r($template_args);
				print "</pre>";	


			//	$WCFMmp->template->get_template( 'store-lists/wcfmmp-view-store-lists-loop.php', $template_args );

  */
			?>




	

		</main><!-- #main -->
	</div><!-- #primary -->
    <?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>



