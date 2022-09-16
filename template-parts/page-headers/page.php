<?php
$page_header = martfury_get_page_header();
$classes = 'container';
if ( is_page_template( 'template-large-container.php' ) ) {
	$classes = 'martfury-container';
}
$classes = apply_filters('martfury_page_header_container_class', $classes);
?>

