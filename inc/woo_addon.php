<?php



/**
 * Add a custom product data tab
 */
add_filter( 'woocommerce_product_tabs', 'woo_new_product_location_tab' );
function woo_new_product_location_tab( $tabs ) {
	
	// Adds the new tab
	
	/*$tabs['loc_tab'] = array(
		'title' 	=> __( 'Product Location', 'woocommerce' ),
		'priority'  => 120,
		'callback' 	=> 'woo_tab_product_location'
	);
	*/

	$tabs['vendor_tab'] = array(
		'title' 	=> __( 'Vendor Products', 'woocommerce' ),
		'priority'  => 130,
		'callback' 	=> 'woo_tab_vendor_products'
	);

	return $tabs;

}
/*
function woo_tab_product_location() {

    echo '<h4>Product Location</h4>';

 // The new tab content
 $prod_id = get_the_ID();
 echo'<p>'.get_post_meta($prod_id,'store_location',true).'</p>';
	
}
*/


function woo_tab_vendor_products() {

	global $product;
				$product_id = $product->get_id();	
				$vendor_id =  wcfm_get_vendor_id_by_post( $product_id );
				$store_user        = wcfmmp_get_store( $vendor_id);	
				$store_info        = $store_user->get_shop_info();


				$store_name = $store_info['store_name'];

    echo '<h4>'.$store_name.' Products</h4>';


 $prod_id = get_the_ID();
 echo  do_shortcode('[products store="'.$vendor_id.'"]');
	
}



