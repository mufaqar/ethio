<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Martfury
 */
?>

	<?php do_action( 'martfury_before_site_content_close' ); ?>
	<?php /* home page category section start */ ?>
	<?php if( is_front_page() ){ ?>
	
		<?php /*Start With dynamic start*/ ?>
		<div class="martfury-catlist-wrap">
		<div class="martfury-container">
		<?php 
		
		//Get parent category in product strat
		$parent_cat_ids = $get_product_cat_desc_order = array();		
		$product_parent_terms = get_terms( array( 
			'taxonomy' 	=> 'product_cat',
			'parent' 	=> 0
			)
		);
		
		foreach( $product_parent_terms as $product_parent ){
			$parent_cat_ids[$product_parent->term_id] = $product_parent->count;			
		}
		//Get parent category in product strat
		
		// product category shortng
		foreach ( $parent_cat_ids as $key => $row ){
			$get_product_cat_desc_order[$key] = $row;
		}
		arsort( $get_product_cat_desc_order );
		foreach( $get_product_cat_desc_order as $key => $row ){
			$list_sub_category  = array();			
			//Get sub category name start	
			$parent_term_id = $key; // term id of parent term (edited missing semi colon)
			$taxonomies_parent_cat = array( 
				'product_cat'			
			);
			$args_parent_cat = array(
				'parent'	=> $parent_term_id,
				'orderby' 	=> 'name',
				'order'	 	=> 'ASC'
			); 

			$terms_parent_cat = get_terms( $taxonomies_parent_cat, $args_parent_cat );
			
			/*If child category empty then get parent ctaegory start*/
			if( empty ( $terms_parent_cat ) ){
				$parent_category = get_term_by('id', $parent_term_id , 'product_cat');
				$list_sub_category[ $parent_term_id ] = $parent_category->name;
			}
			/*If child category empty then get parent ctaegory end*/
			
			foreach ( $terms_parent_cat as $get_sub_parent_cat ){
				$list_sub_category[$get_sub_parent_cat->term_id] = $get_sub_parent_cat->name;
			}
			
			if( !empty( $list_sub_category ) ){
				//Get sub category name end	
				$term_name = get_term_by( 'id', $parent_term_id, 'product_cat' ); 
				
				//Get Premium Product ID start
				$meta_query  = WC()->query->get_meta_query();
				$tax_query   = WC()->query->get_tax_query();
				$premium_product_id = array();
				$featured_product_id = array();
				$get_general_final = array();
				$meta_query[] = array(
					'key'    => '_premium_product',
					'value'    => '1',
					'operator' => 'IN',
				);
				$tax_query[] = array(
					array(
						'taxonomy'      => 'product_cat',
						'field' 		=> 	'term_id', //This is optional, as it defaults to 'term_id'
						'terms'         =>  $term_name->term_id,
						'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
					),
				);
				$args_get_premium = array(
					'post_type'			=> 'product',
					'post_status'		=> 'publish',					
					'orderby' 			=> 'date',
					'order'  			=> 'DESC',
					'suppress_filters'	=> true,
					//'posts_per_page'	=> 10,
					'posts_per_page'	=> 3,
					'meta_query'		=> $meta_query,
					'tax_query'			=> $tax_query,										
				);
				$get_premium_product = new WP_Query( $args_get_premium );
				//$get_featured_per_page = 6 - $get_premium_product->post_count;
				
				if( $get_premium_product->post_count > 0 ){
					foreach( $get_premium_product->posts as $list_premium_product ){
						$premium_product_id[] = $list_premium_product->ID;			
					}
				}
				//Get Premium Product ID end
				
				//Get Featured Product ID start
				if(  $get_premium_product->post_count > 0 ){
					$meta_query2  = WC()->query->get_meta_query();
					$tax_query2   = WC()->query->get_tax_query();
				
					$tax_query2[] = array (
						array(
							'taxonomy'      => 'product_cat',
							'field' 		=> 	'term_id', //This is optional, as it defaults to 'term_id'
							'terms'         =>  $term_name->term_id,
							'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
						),
						array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => 'featured',
							'operator' => 'IN',
						)
					);
					$args_get_featured = array(
						'post_type'			=> 'product',
						'post_status'		=> 'publish',
						'orderby' 			=> 'date',
						'order'  			=> 'DESC',
						'suppress_filters'	=> true,
						'posts_per_page'	=> 6,
						'tax_query'			=> $tax_query2,
						'post__not_in'		=> $premium_product_id
					);
					$get_featured_product = new WP_Query( $args_get_featured );
					if( $get_featured_product->post_count > 0 ){		
						foreach( $get_featured_product->posts as $list_featured_product ){
							$featured_product_id[] = $list_featured_product->ID;				
						}
					}
				}
				//Get Featured Product ID end
				
				//Get General start
				$get_premium_final = array_unique( array_merge( $premium_product_id, $featured_product_id ) );
				$args_get_general = array(
					'post_type'			=> 'product',
					'post_status'		=> 'publish',
					'orderby' 			=> 'date',
					'order'  			=> 'DESC',
					'suppress_filters'	=> true,
					'posts_per_page'	=> 6,
					'tax_query'			=> $tax_query,
					'post__not_in'		=> $get_premium_final
				);
				$get_general_product = new WP_Query( $args_get_general );
				if( $get_general_product->post_count > 0 ){		
					foreach( $get_general_product->posts as $list_general_product ){
						$get_general_final[] = $list_general_product->ID;				
					}
				}
				//Get General end
				
				$get_category_array = array_merge( $premium_product_id , $featured_product_id, $get_general_final );
				$get_premium_final = array_slice( $get_category_array, 0, 3 );
				$get_featured_final = array_slice( $get_category_array, 3, 6 );				
		?>
		
				
					<div class="mf-products-of-category etho-products woocommerce no-infinite">
						<div class="cats-info">
							<div class="cats-inner">
								<h2><a class="cat-title" href="<?php echo get_category_link( $term_name->term_id ); ?>"><?php echo $term_name->name; ?></a></h2>
								<ul class="extra-links">
								<?php 
									foreach ( $list_sub_category as $key => $row ){										
								?>
										<li><a class="extra-link" target="_blank" rel="nofollow" href="<?php echo get_category_link( $key ); ?>"><?php echo $row; ?></a></li>										
								<?php } ?>
								</ul>
							</div>
							<div class="footer-link">
								<a class="link" href="<?php echo get_category_link( $term_name->term_id ); ?>">View All</a>
							</div>
						</div>
						
						<div class="products-box">
							<div class="woocommerce columns-4">
								<ul class="products columns-4">
									<li class="col-xs-12 col-lg-6">
										<!--div class="ethioco-premium-slider owl-carousel" style="background-image:url('<?php //echo site_url(); ?>/wp-content/themes/ethiopian/images/placeholder.jpg');"-->
										<div class="ethioco-premium-slider owl-carousel">
											<?php 
												$args_get_final_slider = array(
													'post_type'			=> 'product',
													'post_status'		=> 'publish',
													'suppress_filters'	=> true,
													'posts_per_page'	=> 3,		
													'post__in'			=> $get_premium_final,
													'orderby' 			=> 'post__in'
												);
												$get_premium_final_product = new WP_Query( $args_get_final_slider );
												if( $get_premium_final_product->post_count > 0 ){
													foreach( $get_premium_final_product->posts as $list_premium_final_product ){
														$product_url = get_permalink( $list_premium_final_product->ID );
														$product 	 = wc_get_product(  $list_premium_final_product->ID );
											?>
														<div class="ethioco-product-content item">	
															<div class="ethioco-product-content-left">
																<h2><?php echo $list_premium_final_product->post_title; ?></h2>
																<?php if( !empty( $product->get_regular_price() ) && !empty( $product->get_sale_price() ) ){ ?>
																	<p><span class="sale-price"><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_sale_price(), 2, '.', '' ); ?></span> <del class="regular-price"><?php echo number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></del></p>
																<?php }else{ ?>
																	<p><span class="regular-price"><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></span></p>
																<?php } ?>
																<a class="slider-button" href="<?php echo $product_url; ?>"><?php _e('See Detail'); ?></a>
															</div>
															<div class="ethioco-product-content-right">
															<?php
																$image = wp_get_attachment_image_src( get_post_thumbnail_id( $list_premium_final_product->ID ), 'single-post-thumbnail' );
																if( $image[0] != '' ){
																	$image_url = $image[0];
																}else{
																	$image_url = site_url().'/wp-content/themes/ethiopian/images/placeholder.jpg';
																}
															?>
																<img src="<?php  echo $image_url; ?>"  width="100" height="100"  data-id="<?php echo $list_premium_final_product->ID ?>">	
															</div>
														</div>
												<?php } ?>
											<?php } ?>
										</div>
									</li>
									
									<?php 
										if( !empty( $get_featured_final ) ){
											$args_get_final_feature = array(
												'post_type'			=> 'product',
												'post_status'		=> 'publish',
												'suppress_filters'	=> true,
												'posts_per_page'	=> 6,		
												'post__in'			=> $get_featured_final,
												'orderby' 			=> 'post__in'
											);
											$get_feature_final_product = new WP_Query( $args_get_final_feature );
											
											if( $get_feature_final_product->post_count > 0 ){
												foreach( $get_feature_final_product->posts as $list_premium_final_product ){
													$product_url = get_permalink( $list_premium_final_product->ID );
													$product 	 = wc_get_product(  $list_premium_final_product->ID );
													$image_url	 = wp_get_attachment_image_src( get_post_thumbnail_id( $list_premium_final_product->ID  ), 'single-post-thumbnail' );				
													if( empty( $image_url[0] ) ){
														$image_url = site_url().'/wp-content/themes/ethiopian/images/placeholder.png';
													}else{
														$image_url = $image_url[0];
													}
									?>
													<li class="col-xs-6 col-sm-4 col-lg-3 product sale featured">
														<div class="product-inner clearfix">
															<div class="mf-product-thumbnail">
																<a href="<?php echo $product_url; ?>">
																	<img width="300" height="300" src="<?php echo $image_url; ?>" alt="<?php echo $list_premium_final_product->post_title; ?>">
																	<?php /* ?>
																	<span class="ribbons">
																		<span class="onsale ribbon">
																			<span class="onsep">-</span>3<span class="per">%</span>
																		</span>
																	</span>
																	<?php */ ?>
																</a>															
																<div class="footer-button">
																	<a href="<?php echo $product_url; ?>">
																		<i class="p-icon icon-bag2" data-rel="tooltip" title="Select options"></i>
																		<span class="add-to-cart-text">Select options</span>
																	</a>
																	<a href="<?php echo $product_url; ?>" data-id="<?php echo $list_premium_final_product->ID; ?>" class="mf-product-quick-view">
																		<i class="p-icon icon-eye" title="Quick View" data-rel="tooltip"></i>
																	</a>
																	<div class="yith-wcwl-add-to-wishlist add-to-wishlist-2429  wishlist-fragment on-first-load">
																		<!-- ADD TO WISHLIST -->
																		<div class="yith-wcwl-add-button">
																			<a href="#" data-rel="tooltip" data-product-id="2429" data-product-type="variable" data-original-product-id="2429" class="add_to_wishlist single_add_to_wishlist" data-product-title="Marshall Kilburn Portable Wireless Speaker" title="Wishlist">
																				<i class="yith-wcwl-icon fa fa-heart-o"></i>
																				<span>Wishlist</span>
																			</a>
																		</div>
																		<!-- COUNT TEXT -->
																	</div>
																	<div class="compare-button mf-compare-button">
																		<a href="#" class="compare" title="Compare" data-product_id="2429">Compare</a>
																	</div>
																</div>															
															</div>
															<div class="mf-product-details">
																<div class="mf-product-content">
																	<h2><a href="<?php echo $product_url; ?>"><?php echo $list_premium_final_product->post_title; ?></a></h2>
																	<div class="mf-rating">
																		<div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
																			<span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5</span>
																		</div>
																		<span class="count">05</span>
																	</div>
																	<div class="sold-by-meta">
																		<span class="sold-by-label">Sold By: </span>
																		<a href="#">Go Pro</a>
																	</div>
																</div>
																<div class="mf-product-price-box">
																<?php if( !empty( $product->get_regular_price() ) && !empty( $product->get_sale_price() ) ){ ?>
																	<span class="price">
																		<ins>
																			<span class="woocommerce-Price-amount amount"><!--span class="woocommerce-Price-currencySymbol">$</span--><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_sale_price(), 2, '.', '' ); ?></span>
																		</ins>
																		<del>
																			<span class="woocommerce-Price-amount amount"><!--span class="woocommerce-Price-currencySymbol">$</span--><?php echo number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></span>
																		</del>
																	</span>
																<?php }else{ ?>
																		<span class="price"><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></span>
																<?php }  ?>
																</div>
																<?php /*if( !empty( $product->get_regular_price() ) && !empty( $product->get_sale_price() ) ){ ?>
																<div class="mf-product-details-hover">
																	<div class="sold-by-meta">
																		<span class="sold-by-label">Sold By: </span>
																		<a href="#">Go Pro</a>
																	</div>
																	<h2><a href="#"><?php echo $list_premium_final_product->post_title; ?></a></h2>
																	<span class="price">
																		<ins><span class="woocommerce-Price-amount amount"><!--span class="woocommerce-Price-currencySymbol">$</span--><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_sale_price(), 2, '.', '' ); ?></span></ins>
																		<del><span class="woocommerce-Price-amount amount"><!--span class="woocommerce-Price-currencySymbol">$</span--><?php echo number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></span></del>
																	</span>
																</div>
																<?php } */?>
															</div>
														</div>
													</li>
											<?php }  ?>
										<?php }  ?>
									<?php }  ?>
								</ul>
							</div>
						</div>
					</div>
					
			<?php } ?>
		<?php } ?>
		<?php /*Start With dynamic end*/ ?>
		</div>
		</div>
	<?php } ?>
	<?php /* home page category section start */ ?>
	</div><!-- #content -->
	<?php do_action( 'martfury_before_footer' ) ?>
	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
		?>
		<footer id="colophon" class="site-footer">
			<?php do_action( 'martfury_footer' ) ?>
		</footer><!-- #colophon -->
		<?php do_action( 'martfury_after_footer' ) ?>
	<?php } ?>
	</div><!-- #page -->
	<?php wp_footer(); ?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			jQuery( 'body #wcfm_products_cust_location, body #wcfm_products_adv_location').attr('style', 'display: block!important');
			$('.wcfm_datepicker').datepicker({
				minDate: 0
			});
			/*$('#sale_date_from').datepicker({
				minDate: 0
			});*/
			$('input[type=number]').addClass('wcfm_non_negative_input');
		});
		</script>
	</body>
</html>
