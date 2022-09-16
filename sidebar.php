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
}  elseif ( is_singular( 'product' ) ) { 

	global $WCFM, $WCFMmp;

	global $product;
	$product_id = $product->get_id();	
	$vendor_id =  wcfm_get_vendor_id_by_post( $product_id );
	$store_user        = wcfmmp_get_store( $vendor_id);	
	$store_info        = $store_user->get_shop_info();


	$store_name = $store_info['store_name'];
	$store_lat =  $store_info['store_lat'];
	$store_lng = $store_info['store_lng'];


	$gravatar = $store_user->get_avatar();
	$email    = $store_user->get_email();
	$phone    = $store_user->get_phone(); 
	$address  = $store_user->get_address_string(); 


	$product_lat = get_post_meta($product_id,'city_store_lat',true);
	$product_lng = get_post_meta($product_id,'city_store_lng',true);

?>
	

		<aside id="primary-sidebar" class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12  product-sidebar">
			<div id="custom_html-8" class="widget_text widget widget_custom_html">
				<div class="textwidget custom-html-widget">					
					<ul class="mf-shipping-info">	
						<li><i class="ion-ios-person"></i>Store : <?php echo $store_name ?></li>
						<li><i class="icon-envelope"></i>  <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a></li>
						<li><i class="icon-phone"></i><?php echo $phone ?></li>
						<li><i class="icon-map-marker"></i><?php echo $address ?></li>							
					</ul>					
		
				</div>

				<div class="widget map">
					<h4>Product Location</h4>	
					<iframe src="https://maps.google.com/maps?q=<?php echo $product_lat ?>,<?php echo $product_lng ?>&hl=es;z=16&amp;output=embed" width="100%" height="400" frameborder="0" style="border:0"></iframe>
				</div>
				<div class="widget faq">	
					<div class="sidebar-widget page-faq">
						<h4><?php _e('FAQs','martfury'); ?></h4>
					
						<div>
								<input type="checkbox" id="shop1" name="q"  class="questions"><div class="plus">+</div>
								<label for="shop1" class="question">What Products Are Advertised On Ethio.com?</label>
								<div class="answers">
									Ethio.com is an online marketplace for literally everything under the sun. From Real estate properties to cars and spare parts, clothing to cosmetics, events & gatherings to construction, education & training to job vacancies, anything. Check out our Home page for more categories.

								</div>
						</div>
						<div>
								<input type="checkbox" id="shop2" name="q"  class="questions"><div class="plus">+</div>
								<label for="shop2" class="question">What Products Are Prohibited On Ethio.com?</label>
								<div class="answers">
									Ethio doesn’t support any illegal or prohibited products and services. Narcotics, controlled substances, weapons, or any other illegal items are banned from the marketplace. Any account advertising illegal or prohibited products will be banned. Check ABout Us page for a more comprehensive list of prohibited items.
								</div>
						</div>
						<div>
							<input type="checkbox" id="shop3" name="q"  class="questions"><div class="plus">+</div>
							<label for="shop3" class="question">There's No Category For My Product</label>
							<div class="answers">
								We always welcome user feedback. If you can think of a way to help us improve our categories, please do feel free to reach out through our contact page or email.
							</div>
						</div>
						<div>
							<input type="checkbox" id="shop4" name="q"  class="questions"><div class="plus">+</div>
							<label for="shop4" class="question">I Think This Product Deserves Its Own Category</label>
							<div class="answers">
								We always welcome user feedback. If you can think of a way to help us improve our categories, please do feel free to reach out through our contact page or email.
							</div>
						</div>
					</div>
				</div>
			</div>
		</aside>
<?php } else { ?>
		<aside id="primary-sidebar" class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12 <?php echo esc_attr( $sidebar_class ) ?>">
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
			<?php 
				get_sidebar( 'store' );
				if ( is_active_sidebar( $sidebar ) ) {
					dynamic_sidebar( 'page-sidebar' );
				}
				$values = rwmb_meta( 'faq_list');
				if (rwmb_meta('faq_list') != '') {
			?>
					<div class="sidebar-widget page-faq">
						<h4><?php _e('FAQs','martfury'); ?></h4>
						<?php $values = rwmb_meta( 'faq_list');
							foreach ( $values as $key=>$value ) { 	?>
								<div>
									<input type="checkbox" id="<?php echo $key ?>" name="q"  class="questions"><div class="plus">+</div>
									<label for="<?php echo $key ?>" class="question"><?php echo $value[0];  ?></label>
									<div class="answers"><?php echo $value[1];  ?></div>
								</div>
						<?php }	?>
					</div>
			<?php } ?>
			<?php if ( is_shop() ||  is_product_category() ) {  ?>
				<?php dynamic_sidebar( 'product-sidebar' ); // Set side bar in product page ?>
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
			<?php } ?>	
		</aside><!-- #secondary -->			
<?php
}
$sidebar_class .= ' ' . $sidebar; 
?>