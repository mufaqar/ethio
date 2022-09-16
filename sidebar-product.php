<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Martfury
 */

if ( ! in_array( martfury_get_layout(), array( 'sidebar-content', 'content-sidebar', 'small-thumb' ) ) ) {
	return;
}

$sidebar       = 'blog-sidebar';
$sidebar_class = '';
if ( is_singular( 'post' ) ) {
	$sidebar = 'post-sidebar';
	if ( ! is_active_sidebar( $sidebar ) ) {
		$sidebar = 'blog-sidebar';
	}
} elseif ( martfury_is_vendor_page() ) {
	$sidebar = 'vendor_sidebar';
} elseif ( martfury_is_catalog() ) {
	$sidebar = 'catalog-sidebar';
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$cat_sidebar    = '';
		$queried_object = get_queried_object();
		$term_id        = $queried_object->term_id;
		$cat_ancestors  = get_ancestors( $term_id, 'product_cat' );
		$cat_sidebar    = get_term_meta( $term_id, 'mf_cat_sidebar', true );

		if ( empty( $cat_sidebar ) && count( $cat_ancestors ) > 0 ) {
			$parent_id   = $cat_ancestors[0];
			$cat_sidebar = get_term_meta( $parent_id, 'mf_cat_sidebar', true );
		}


		$sidebar_class = 'catalog-sidebar';

		$sidebar = ! empty( $cat_sidebar ) ? $cat_sidebar : $sidebar;

	}

} elseif ( is_singular( 'product' ) ) {  ?>

			<aside id="primary-sidebar" class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12  product-sidebar">
				<div id="custom_html-8" class="widget_text widget widget_custom_html"><div class="textwidget custom-html-widget">
					<ul class="mf-shipping-info">
						<li><i class="icon-network"></i>Shipping worldwide</li>
						<li><i class="icon-3d-rotate"></i>Free 7-day return if eligible, so easy </li>
						<li><i class="icon-receipt"></i>Supplier give bills for this product.</li>
						<li><i class="icon-credit-card"></i>Pay online or when receiving goods</li>
					</ul>
				</div>
			</aside>


	<?php
} elseif ( is_page() ) {
	$sidebar = 'page-sidebar';
}



$sidebar_class .= ' ' . $sidebar;

?>


<aside id="primary-sidebar"
       class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12 <?php echo esc_attr( $sidebar_class ) ?>">
		<div class="leftmenu">
			<h4><?php _e('Categories','martfury'); ?> </h4>
	 	  <div class="toggle-product-cats nav">
				<?php
				global $martfury_department_menu;
				if ( empty( $martfury_department_menu ) ) {
					$options = array(
					//	'theme_location' => $location,
						'container'      => false,
						'echo'           => false,
						'walker'         => new Martfury_Mega_Menu_Walker()
					);
					$martfury_department_menu = wp_nav_menu( $options );
				}

				echo ! empty( $martfury_department_menu ) ? $martfury_department_menu : '';			


				?>
            </div>
			</div>


			<?php get_sidebar( 'store' ); ?>


			<?php
						if ( is_active_sidebar( $sidebar ) ) {
						
							dynamic_sidebar( 'page-sidebar' );
						} 
	
						$values = rwmb_meta( 'faq_list');
						
						if (rwmb_meta('faq_list') != '') {  ?>
								<div class="sidebar-widget page-faq">
									<h4><?php _e('FAQs','martfury'); ?></h4>
									<?php 	$values = rwmb_meta( 'faq_list');

										foreach ( $values as $key=>$value ) { 	?>
													<div>
															<input type="checkbox" id="<?php echo $key ?>" name="q"  class="questions"><div class="plus">+</div>
															<label for="<?php echo $key ?>" class="question"><?php echo $value[0];  ?></label>
															<div class="answers"><?php echo $value[1];  ?></div>
													</div>

											<?php }	?>

									</div>
						<?php } ?>

	<?php if ( is_shop() ||  is_product_category() )
				{
					?>

	<div class="sidebar-widget page-faq">
	<h4><?php _e('FAQs','martfury'); ?></h4>
				
								<div>
										<input type="checkbox" id="shop1" name="q"  class="questions"><div class="plus">+</div>
										<label for="shop1" class="question">How Do I View My Product List?</label>
										<div class="answers">Log into your account. Click My Products on the right-side panel to open the Product list. It shows all the products you have advertised on the platform.</div>
								</div>

								<div>
										<input type="checkbox" id="shop2" name="q"  class="questions"><div class="plus">+</div>
										<label for="shop2" class="question">Is There A Limit To The Number Of Products I Can Post?</label>
										<div class="answers">
										You can post as many products as you wish on Ethio Marketplace
										</div>
								</div>


								<div>
										<input type="checkbox" id="shop3" name="q"  class="questions"><div class="plus">+</div>
										<label for="shop3" class="question">Can I Have Different Products On My Product List?</label>
										<div class="answers">
											You can have a diverse collection of products on the marketplace. Just be sure to list each product in the proper category.
										</div>
								</div>



								<div>
										<input type="checkbox" id="shop4" name="q"  class="questions"><div class="plus">+</div>
										<label for="shop4" class="question">Do I Need A License To Sell The Products On My List?</label>
										<div class="answers">
										It’s advisable that sellers follow the full spirit of the law when listing products on the platform including having a license to sell regulated products.


										</div>
								</div>

					

				</div>


					<?php

				}

				?>	
</aside><!-- #secondary -->



