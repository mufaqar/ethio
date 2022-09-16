<?php
if ( ! intval( martfury_get_option( 'product_breadcrumb' ) ) ) {
	return;
}

$container_class = apply_filters( 'martfury_catalog_page_header_container', 'container' );
?>


<div class="page-header page-header-catalog">
 
			<div class="page-breadcrumbs">
            <div class="martfury-container">
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
        </div>

        <!-- Single Product Breadcrumbs -->
