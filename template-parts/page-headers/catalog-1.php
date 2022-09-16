<?php
global $martfury_woocommerce;
$show_title     = true;
$container_class = 'container';
if ( ! empty( $martfury_woocommerce ) && method_exists( $martfury_woocommerce, 'get_catalog_elements' ) ) {
	$elements = $martfury_woocommerce->get_catalog_elements();
	if ( empty( $elements ) || ! in_array( 'title', $elements ) ) {
		$show_title = false;
	}

}

$show_title =  apply_filters( 'martfury_catalog_page_title', $show_title );

$container_class = apply_filters( 'martfury_catalog_page_header_container', 'container' );

?>

