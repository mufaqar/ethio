<?php

/**
 * Class for all Vendor template modification
 *
 * @version 1.0
 */
class Martfury_WCFMVendors {

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Martfury_Vendor
	 */
	function __construct() {
		// Check if Woocomerce plugin is actived
		if ( ! class_exists( 'WCFMmp' ) ) {
			return;
		}

		//remove display vendor by plugin
		add_filter( 'wcfmmp_is_allow_archive_product_sold_by', '__return_false' );

		switch ( martfury_get_option( 'catalog_vendor_name' ) ) {
			case 'display':
				// Always Display sold by
				add_action( 'woocommerce_shop_loop_item_title', array( $this, 'product_loop_display_sold_by' ), 6 );

				// Display sold by in product list
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_loop_sold_by' ), 7 );

				// Display sold by on hover
				add_action( 'martfury_product_loop_details_hover', array( $this, 'product_loop_sold_by' ), 15 );

				// Display sold by in product deals
				add_action( 'martfury_woo_after_shop_loop_item_title', array( $this, 'product_loop_sold_by' ), 20 );
				break;

			case 'hover':

				if ( martfury_get_option( 'product_loop_hover' ) == '3' ) {
					// Always Display sold by
					add_action( 'woocommerce_shop_loop_item_title', array(
						$this,
						'product_loop_display_sold_by'
					), 6 );
				}

				// Display sold by in product list
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_loop_sold_by' ), 7 );

				// Display sold by on hover
				add_action( 'martfury_product_loop_details_hover', array( $this, 'product_loop_sold_by' ), 15 );

				// Display sold by in product deals
				add_action( 'martfury_woo_after_shop_loop_item_title', array( $this, 'product_loop_sold_by' ), 20 );
				break;
			case 'profile':

				// Always Display sold by
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'display_vendor_profile' ), 10 );

				// Display sold by on hover
				add_action( 'martfury_product_loop_details_hover', array( $this, 'display_vendor_profile' ), 45 );

				// Display sold by in product deals
				add_action( 'martfury_woo_after_shop_loop_item', array( $this, 'display_vendor_profile' ), 20 );
				break;
		}


		if ( martfury_get_option( 'wcfm_single_sold_by_template' ) == 'theme' ) {
			add_filter( 'wcfmmp_is_allow_single_product_sold_by', '__return_false' );

			add_action( 'martfury_single_product_header', array(
				$this,
				'product_loop_sold_by',
			) );
		}

		add_filter( 'body_class', array(
			$this,
			'wcfm_body_classes',
		) );

		if ( martfury_get_option( 'wcfm_store_header_layout' ) == 'theme' ) {

			add_filter( 'wcfm_is_allow_store_name_on_header', '__return_true' );
			add_filter( 'wcfm_is_allow_store_name_on_banner', '__return_false' );
		}

		add_filter( 'martfury_site_content_container_class', array( $this, 'vendor_dashboard_container_class' ) );
		add_filter( 'martfury_page_header_container_class', array( $this, 'vendor_dashboard_container_class' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 30 );

		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'catalog_mode_loop_add_to_cart' ) );

		add_filter( 'woocommerce_get_price_html', array( $this, 'catalog_mode_loop_price' ), 20, 2 );


		// Settings
		if ( class_exists( 'TAWC_Deals' ) ) {
			add_filter( 'wcfm_product_manage_fields_pricing', array( $this, 'product_manage_fields_pricing' ), 20, 2 );
		}

		add_filter( 'wcfm_product_manage_fields_linked', array( $this, 'products_custom_fields_linked' ), 100, 3 );

		add_action( 'after_wcfm_products_manage_meta_save', array( $this, 'product_meta_save' ), 500, 2 );

		add_filter( 'wcfmmp_stores_default_args', array( $this, 'stores_list_default_args' ) );

		add_action( 'after_wcfm_products_manage_linked', array( $this, 'products_custom_fields' ), 20, 2 );

	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'martfury-wcfm', get_template_directory_uri() . '/css/vendors/wcfm-vendor.css', array(), '20201126' );
	}


	function product_loop_display_sold_by() {
		echo '<div class="mf-vendor-name">';
		$this->product_loop_sold_by();
		echo '</div>';
	}


	function product_loop_sold_by() {

		if ( ! class_exists( 'WCFM' ) ) {
			return;
		}

		global $WCFM, $post, $WCFMmp;

		if( ! $post ) {
			return;
        }

		$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $post->ID );

		if ( ! $vendor_id ) {
			return;
		}

		$sold_by_text = apply_filters( 'wcfmmp_sold_by_label', esc_html__( 'Sold By:', 'martfury' ) );
		if ( $WCFMmp ) {
			$sold_by_text = $WCFMmp->wcfmmp_vendor->sold_by_label( absint( $vendor_id ) );
		}
		$store_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint( $vendor_id ) );

		echo '<div class="sold-by-meta">';
		echo '<span class="sold-by-label">' . $sold_by_text . ': ' . '</span>';
		echo wp_kses_post( $store_name );
		echo '</div>';
	}

	function display_vendor_profile() {
		global $WCFM, $WCFMmp, $product;

		if ( function_exists( 'wcfm_is_store_page' ) && wcfm_is_store_page() ) {
			return;
		}
		if ( ! $product ) {
			return;
		}
		if ( ! method_exists( $product, 'get_id' ) ) {
			return;
		}

		if ( $WCFMmp->wcfmmp_vendor->is_vendor_sold_by() ) {
			$product_id = $product->get_id();

			$vendor_id = wcfm_get_vendor_id_by_post( $product_id );

			if ( apply_filters( 'wcfmmp_is_allow_archive_sold_by_advanced', false ) ) {
				$WCFMmp->template->get_template( 'sold-by/wcfmmp-view-sold-by-advanced.php', array(
					'product_id' => $product_id,
					'vendor_id'  => $vendor_id
				) );
			} else {
				$WCFMmp->template->get_template( 'sold-by/wcfmmp-view-sold-by-simple.php', array(
					'product_id' => $product_id,
					'vendor_id'  => $vendor_id
				) );
			}
		}
	}

	function wcfm_body_classes( $classes ) {
		if ( function_exists( 'wcfm_is_store_page' ) && wcfm_is_store_page() && martfury_get_option( 'wcfm_store_header_layout' ) == 'theme' ) {
			$classes[] = 'wcfm-template-themes';
		}

		if ( martfury_get_option( 'catalog_vendor_name' ) == 'profile' ) {
			$classes[] = 'mf-vendor-profile';
		}

		return $classes;
	}

	function vendor_dashboard_container_class( $container ) {

		if ( ! function_exists( 'is_wcfm_page' ) ) {
			return $container;
		}

		if ( is_wcfm_page() ) {
			if ( intval( martfury_get_option( 'vendor_dashboard_full_width' ) ) ) {
				$container = 'martfury-container';
			}
		}

		return $container;
	}

	function catalog_mode_loop_add_to_cart( $html ) {

		global $product;

		if ( get_post_meta( $product->get_id(), '_catalog', true ) == 'yes' ) {
			if ( get_post_meta( $product->get_id(), 'disable_add_to_cart', true ) == 'yes' ) {
				return false;
			}
		}

		return $html;

	}

	function catalog_mode_loop_price( $html, $product ) {

		if ( get_post_meta( $product->get_id(), '_catalog', true ) == 'yes' ) {
			if ( get_post_meta( $product->get_id(), 'disable_price', true ) == 'yes' ) {
				return false;
			}
		}

		return $html;
	}

	function product_manage_fields_pricing( $fields, $product_id ) {
		$quantity                 = get_post_meta( $product_id, '_deal_quantity', true );
		$sales_counts             = get_post_meta( $product_id, '_deal_sales_counts', true );
		$sales_counts             = intval( $sales_counts );
		$fields["_deal_quantity"] = array(
			'label'       => esc_html__( 'Sale quantity', 'martfury' ),
			'type'        => 'number',
			'class'       => 'wcfm-text wcfm_ele wcfm_half_ele sales_schedule_ele simple external non-variable-subscription non-auction non-redq_rental non-accommodation-booking',
			'label_class' => 'wcfm_ele wcfm_half_ele_title sales_schedule_ele wcfm_title simple external non-variable-subscription non-auction non-redq_rental non-accommodation-booking',
			'hints'       => esc_html__( 'Set this quantity will make the product to be a deal. The sale will end when this quantity is sold out.', 'martfury' ),
			'value'       => $quantity
		);

		$fields["_deal_sales_counts"] = array(
			'label'       => esc_html__( 'Sold Items', 'martfury' ),
			'type'        => 'number',
			'class'       => 'wcfm-text wcfm_ele wcfm_half_ele sales_schedule_ele simple external non-variable-subscription non-auction non-redq_rental non-accommodation-booking',
			'label_class' => 'wcfm_ele wcfm_half_ele_title sales_schedule_ele wcfm_title simple external non-variable-subscription non-auction non-redq_rental non-accommodation-booking',
			'hints'       => esc_html__( 'Set this sold items should be less than the sale quantity.', 'martfury' ),
			'value'       => $sales_counts
		);

		return $fields;
	}

	function product_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $WCFM;

		if ( class_exists( 'TAWC_Deals' ) ) {
			$_deal_quantity     = ( isset( $wcfm_products_manage_form_data['_deal_quantity'] ) ) ? intval( $wcfm_products_manage_form_data['_deal_quantity'] ) : 0;
			$_deal_sales_counts = ( isset( $wcfm_products_manage_form_data['_deal_sales_counts'] ) ) ? intval( $wcfm_products_manage_form_data['_deal_sales_counts'] ) : 0;
			update_post_meta( $new_product_id, '_deal_quantity', $_deal_quantity );
			if ( $_deal_quantity >= $_deal_sales_counts ) {
				update_post_meta( $new_product_id, '_deal_sales_counts', $_deal_sales_counts );
			}
		}

		$pbt_product_ids = ( isset( $wcfm_products_manage_form_data['mf_pbt_product_ids'] ) ) ? array_map( 'intval', (array) $wcfm_products_manage_form_data['mf_pbt_product_ids'] ) : array();
		update_post_meta( $new_product_id, 'mf_pbt_product_ids', $pbt_product_ids );

		// Video
		$video_url = ( isset( $wcfm_products_manage_form_data['video_url'] ) ) ? $wcfm_products_manage_form_data['video_url'] : '';
		update_post_meta( $new_product_id, 'video_url', $video_url );

		$video_thumbnail_src = ( isset( $wcfm_products_manage_form_data['video_thumbnail_src'] ) ) ? $wcfm_products_manage_form_data['video_thumbnail_src'] : '';

		$video_thumbnail_id = $WCFM->wcfm_get_attachment_id( $video_thumbnail_src );

		update_post_meta( $new_product_id, 'video_thumbnail', $video_thumbnail_id );

		$video_position = ( isset( $wcfm_products_manage_form_data['video_position'] ) ) ? $wcfm_products_manage_form_data['video_position'] : '';
		update_post_meta( $new_product_id, 'video_position', $video_position );

		$product_360_ids = array();
		if ( isset( $wcfm_products_manage_form_data['product_360_view_src'] ) ) {
			foreach ( $wcfm_products_manage_form_data['product_360_view_src'] as $gallery_imgs ) {
				$product_360_src = isset( $gallery_imgs['image'] ) ? $gallery_imgs['image'] : '';
				if ( $product_360_src ) {
					$product_360_ids[] = $WCFM->wcfm_get_attachment_id( $product_360_src );
				}

			}
		}

		if ( ! empty( $product_360_ids ) ) {
			update_post_meta( $new_product_id, 'wcfm_product_360_view', implode( ',', $product_360_ids ) );
		} else {
			update_post_meta( $new_product_id, 'wcfm_product_360_view', '' );
		}

		$custom_badges_text = ( isset( $wcfm_products_manage_form_data['custom_badges_text'] ) ) ? $wcfm_products_manage_form_data['custom_badges_text'] : '';
		$_is_new            = ( isset( $wcfm_products_manage_form_data['_is_new'] ) ) ? 'yes' : 'no';
		update_post_meta( $new_product_id, 'custom_badges_text', $custom_badges_text );
		update_post_meta( $new_product_id, '_is_new', $_is_new );

		// Product Physical Location
		$country_value = ( isset( $wcfm_products_manage_form_data['country_value'] ) ) ? $wcfm_products_manage_form_data['country_value'] : '';
		update_post_meta( $new_product_id, 'country_value', $country_value );

		$state_value = ( isset( $wcfm_products_manage_form_data['state_value'] ) ) ? $wcfm_products_manage_form_data['state_value'] : '';
		update_post_meta( $new_product_id, 'state_value', $state_value );

		$city_value = ( isset( $wcfm_products_manage_form_data['city_value'] ) ) ? $wcfm_products_manage_form_data['city_value'] : '';
		update_post_meta( $new_product_id, 'city_value', $city_value );

		/*$stateid_no = ( isset( $wcfm_products_manage_form_data['stateid_no'] ) ) ? $wcfm_products_manage_form_data['stateid_no'] : '';
		update_post_meta( $new_product_id, 'stateid_no', $stateid_no );*/

		/*$city_name_select = ( isset( $wcfm_products_manage_form_data['city_name_select'] ) ) ? $wcfm_products_manage_form_data['city_name_select'] : '';
		update_post_meta( $new_product_id, 'city_name_select', $city_name_select );*/

		$city_store_lat = ( isset( $wcfm_products_manage_form_data['city_store_lat'] ) ) ? $wcfm_products_manage_form_data['city_store_lat'] : '';
		update_post_meta( $new_product_id, 'city_store_lat', $city_store_lat );

		$city_store_lng = ( isset( $wcfm_products_manage_form_data['city_store_lng'] ) ) ? $wcfm_products_manage_form_data['city_store_lng'] : '';
		update_post_meta( $new_product_id, 'city_store_lng', $city_store_lng );

		// auto fill location

		$find_address = ( isset( $wcfm_products_manage_form_data['geolocation']['store_location'] ) ) ? $wcfm_products_manage_form_data['geolocation']['store_location'] : '';
		update_post_meta( $new_product_id, 'find_address', $find_address );

		$store_location = ( isset( $wcfm_products_manage_form_data['geolocation']['store_location'] ) ) ? $wcfm_products_manage_form_data['geolocation']['store_location'] : '';
		update_post_meta( $new_product_id, 'store_location', $store_location );

		$store_lat = ( isset( $wcfm_products_manage_form_data['geolocation']['store_lat'] ) ) ? $wcfm_products_manage_form_data['geolocation']['store_lat'] : '';
		update_post_meta( $new_product_id, 'store_lat', $store_lat );

		$store_lng = ( isset( $wcfm_products_manage_form_data['geolocation']['store_lng'] ) ) ? $wcfm_products_manage_form_data['geolocation']['store_lng'] : '';
		update_post_meta( $new_product_id, 'store_lng', $store_lng );




		if( isset( $wcfm_products_manage_form_data['wcfm_rep_adv_location'] ) ) {
			$wcfm_store_rep_location_options = $wcfm_products_manage_form_data['wcfm_rep_adv_location'];
			update_post_meta( $new_product_id, 'wcfm_store_rep_location_options', $wcfm_store_rep_location_options );
		}


	}

	function products_custom_fields_linked( $fields, $product_id, $products_array ) {

		if ( ! intval( martfury_get_option( 'product_fbt' ) ) ) {
			return $fields;
		}

		if ( ! in_array( 'fbt', martfury_get_option( 'wcfm_dashboard_custom_fields' ) ) ) {
			return $fields;
		}

		$pbt_product_ids = get_post_meta( $product_id, 'mf_pbt_product_ids', true );
		$pbt_product_ids = $pbt_product_ids ? $pbt_product_ids : array();
		if ( ! empty( $pbt_product_ids ) ) {
			foreach ( $pbt_product_ids as $pbt_product_id ) {
				$products_array[ $pbt_product_id ] = get_post( absint( $pbt_product_id ) )->post_title;
			}
		}
		$fields["mf_pbt_product_ids"] = array(
			'label'       => esc_html__( 'Frequently Bought Together', 'martfury' ),
			'type'        => 'select',
			'attributes'  => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ),
			'class'       => 'wcfm-select wcfm_ele simple variable',
			'label_class' => 'wcfm_title',
			'options'     => $products_array,
			'value'       => $pbt_product_ids,
		);

		return $fields;

	}

	function products_custom_fields( $product_id, $product_type ) {
		global $WCFM, $WCFMmp;
		if ( in_array( 'video', martfury_get_option( 'wcfm_dashboard_custom_fields' ) ) ) {
			?>
			<!-- collapsible 8 - Product Video -->
			<div class="page_collapsible products_manage_video <?php echo apply_filters( 'wcfm_pm_block_class_linked', 'simple variable external grouped' ); ?> <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>"
			     id="wcfm_products_manage_form_linked_head"><label
					class="wcfmfa fa-video"></label><?php esc_html_e( 'Product Video', 'martfury' ); ?><span></span>
			</div>
			<div class="wcfm-container simple variable external grouped <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>">
				<div id="wcfm_products_manage_form_linked_expander" class="wcfm-content">
					<?php

					$video_url           = get_post_meta( $product_id, 'video_url', true );
					$video_thumbnail_id  = get_post_meta( $product_id, 'video_thumbnail', true );
					$image_thumbnail     = wp_get_attachment_image_src( $video_thumbnail_id, 'full' );
					$video_thumbnail_src = $image_thumbnail ? $image_thumbnail[0] : '';
					$video_position      = get_post_meta( $product_id, 'video_position', true );
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_video', array(
						"video_url"           => array(
							'label'       => esc_html__( 'Video URL', 'martfury' ),
							'type'        => 'text',
							'class'       => 'wcfm-text wcfm_ele simple variable external grouped booking',
							'label_class' => 'wcfm_title',
							'value'       => $video_url
						),
						"video_thumbnail_src" => array(
							'label'       => esc_html__( 'Video Thumbnail', 'martfury' ),
							'type'        => 'upload',
							'class'       => 'wcfm-upload wcfm_ele simple variable external grouped booking',
							'label_class' => 'wcfm_title',
							'value'       => $video_thumbnail_src
						),
						"video_position"      => array(
							'label'       => esc_html__( 'Video Position', 'martfury' ),
							'type'        => 'select',
							'class'       => 'wcfm-select wcfm_ele simple variable external grouped booking',
							'label_class' => 'wcfm_title',
							'options'     => array(
								'1' => esc_html__( 'The last product gallery', 'martfury' ),
								'2' => esc_html__( 'The first product gallery', 'martfury' ),
							),
							'value'       => $video_position
						),

					), $product_id ) );
					?>
				</div>
			</div>
			<!-- end collapsible -->
			<div class="wcfm_clearfix"></div>
		<?php } ?>
		<?php
		if ( in_array( '360', martfury_get_option( 'wcfm_dashboard_custom_fields' ) ) ) {

			?>
			<!-- collapsible 8 - Product 360 -->
			<div class="page_collapsible products_manage_360_view <?php echo apply_filters( 'wcfm_pm_block_class_linked', 'simple variable external grouped' ); ?> <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>"
			     id="wcfm_products_manage_form_linked_head"><label
					class="wcfmfa fa-film"></label><?php esc_html_e( 'Product 360 View', 'martfury' ); ?>
				<span></span>
			</div>
			<div class="wcfm-container simple variable external grouped <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>">
				<div id="wcfm_products_manage_form_linked_expander" class="wcfm-content">
					<?php
					$images_meta = get_post_meta( $product_id, 'wcfm_product_360_view', true );
					$images_meta = $images_meta ? explode( ',', $images_meta ) : array();
					$images_360  = array();
					if ( $images_meta ) {
						foreach ( $images_meta as $image_id ) {
							$image                 = wp_get_attachment_image_src( $image_id, 'full' );
							$images_360[]['image'] = $image ? $image[0] : '';
						}
					}

					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_video', array(
						"product_360_view_src" => array(
							'label'       => esc_html__( 'Images', 'martfury' ),
							'type'        => 'multiinput',
							'class'       => 'wcfm-text wcfm-gallery_image_upload wcfm_ele simple variable external grouped booking',
							'label_class' => 'wcfm_title',
							'value'       => $images_360,
							'options'     => array(
								"image" => array(
									'type'    => 'upload',
									'class'   => 'wcfm_gallery_upload',
									'prwidth' => 75
								),
							),
						),

					), $product_id ) );
					?>
				</div>
			</div>
			<!-- end collapsible -->
			<div class="wcfm_clearfix"></div>
			<?php
		}

		?>

		<!-- Product Location Start -->
		<?php if ( in_array( 'product_cust_location', martfury_get_option( 'wcfm_dashboard_custom_fields' ) ) ) { ?>
			<!-- Product Custom Location -->
			<div class="page_collapsible products_cust_location" id="wcfm_products_cust_location" <?php echo apply_filters( 'wcfm_pm_block_class_linked', 'simple variable external grouped' ); ?> <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>>
				<label class="wcfmfa fa-street-view"></label><?php esc_html_e( 'Product Location', 'martfury' ); ?><span></span>
				<input type="hidden" id="vendor_site_url" name="vendor_site_url" value="<?php echo get_template_directory_uri(); ?>" />
			</div>

			<div class="wcfm-container products_cust_location_wrap simple variable external grouped <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>">
				<div id="wcfm_products_manage_form_linked_expander" class="wcfm-content">
					<p><strong><?php esc_html_e( 'Product Location', 'martfury' ); ?></strong></p>
					<?php
						global $wpdb;						
						if( !current_user_can( 'administrator' ) ){
							$vendor_data = get_user_meta( get_current_user_id(), 'wcfmmp_profile_settings', true );
							$vendor_country	= $vendor_data['address']['country'];
							$vendor_state	= $vendor_data['address']['state'];
							$vendor_city	= $vendor_data['address']['city'];
							
							/****get data from db start**/
							$country_value		= get_post_meta( $product_id, 'country_value', true );
							$state_value		= get_post_meta( $product_id, 'state_value', true );
							$city_value			= get_post_meta( $product_id, 'city_value', true );
							$stateid_no			= get_post_meta( $product_id, 'stateid_no', true );
							//$city_name_select	= get_post_meta( $product_id, 'city_name_select', true );
							$city_store_lat		= get_post_meta( $product_id, 'city_store_lat', true );
							$city_store_lng		= get_post_meta( $product_id, 'city_store_lng', true );
							/*get data from db end*/
							
							if( empty( $country_value ) && empty( $state_value ) && empty( $city_value ) ){
								$country_value	= $vendor_country;
								$state_value	= $vendor_state;
								$city_value		= $vendor_city;
							}else{
								$country_value	= $country_value;
								$state_value	= $state_value;
								$city_value		= $city_value;
							}
							
							/*******Get Country, State and City from DB start**********/
							$county_selected = $wpdb->get_results( "SELECT name, shortcode FROM country where isactive = 1 ORDER BY name ASC", ARRAY_A  );
							$county_meta = arrangeArrayPair( $county_selected, 'shortcode', 'name' );
							array_unshift( $county_meta, 'Select Country' );
							
							$country_id = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $country_value ."' AND isactive = 1", ARRAY_A  );
							
							//$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
							$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where country_id = '". $country_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
							$state_meta = arrangeArrayPair( $state_selected, 'shortcode', 'name' );
							array_unshift( $state_meta, 'Select State' );
							
							$state_id = $wpdb->get_results( "SELECT id FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1", ARRAY_A  );
							
							$city_selected = $wpdb->get_results( "SELECT name FROM city where country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
							$city_meta = arrangeArrayPair( $city_selected, 'name', 'name' );
							array_unshift( $city_meta, 'Select City' );
							
							$city_data = $wpdb->get_results( "SELECT latitude, longitude FROM city where name = '". $city_value ."' AND country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1", ARRAY_A  );
							
							if( !empty ( $city_data ) ){
								$city_store_lat	= $city_data['0']['latitude'];
								$city_store_lng = $city_data['0']['longitude'];
							}						
							/*******Get Country, State and City from DB end**********/
							
							/*echo "<br> country_value => ".$country_value;
							echo "<br> state_value => ".$state_value;
							echo "<br> city_value => ".$city_value;						
							echo "<br> stateid_no => ".$stateid_no;						
							echo "<br> city_store_lat => ".$city_store_lat;
							echo "<br> city_store_lng => ".$city_store_lng;	*/							
							
						}else{								
							/****get data from db start**/
							$country_value		= get_post_meta( $product_id, 'country_value', true );
							$state_value		= get_post_meta( $product_id, 'state_value', true );
							$city_value			= get_post_meta( $product_id, 'city_value', true );
							
							$city_store_lat		= get_post_meta( $product_id, 'city_store_lat', true );
							$city_store_lng		= get_post_meta( $product_id, 'city_store_lng', true );
							
							/*get data from db end*/
							if( !empty( $country_value ) && !empty( $state_value ) && !empty( $city_value )  ){
								$country_value	= $country_value;
								$state_value	= $state_value;
								$city_value		= $city_value;
								//$city_store_lat	= $city_store_lat;
								//$city_store_lng	= $city_store_lng;	
	
								$county_selected = $wpdb->get_results( "SELECT name, shortcode FROM country where isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$county_meta = arrangeArrayPair( $county_selected, 'shortcode', 'name' );
								array_unshift( $county_meta, 'Select Country' );
								
								$country_id = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $country_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								
								//$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where shortcode = '". $state_value ."' AND country_id = '". $country_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where country_id = '". $country_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$state_meta = arrangeArrayPair( $state_selected, 'shortcode', 'name' );
								array_unshift( $state_meta, 'Select State' );
								
								$state_id = $wpdb->get_results( "SELECT id FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								
								$city_selected = $wpdb->get_results( "SELECT name FROM city where country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$city_meta = arrangeArrayPair( $city_selected, 'name', 'name' );
								array_unshift( $city_meta, 'Select City' );
								
								$city_data = $wpdb->get_results( "SELECT latitude, longitude FROM city where name = '". $city_value ."' AND country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1", ARRAY_A  );
								
								if( !empty ( $city_data ) ){
									$city_store_lat	= $city_data['0']['latitude'];
									$city_store_lng = $city_data['0']['longitude'];
								}								
							}else{
								$get_country_state = get_option( 'woocommerce_default_country' );
								$city_value		   = get_option( 'woocommerce_store_city' );
								$country_state_array = explode( ':', $get_country_state );
								
								$country_value = $country_state_array['0'];
								$state_value = $country_state_array['1'];
								
								$county_selected = $wpdb->get_results( "SELECT name, shortcode FROM country where isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$county_meta = arrangeArrayPair( $county_selected, 'shortcode', 'name' );
								array_unshift( $county_meta, 'Select Country' );
								
								$country_id = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $country_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								
								//$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where shortcode = '". $state_value ."' AND country_id = '". $country_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where country_id = '". $country_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$state_meta = arrangeArrayPair( $state_selected, 'shortcode', 'name' );
								array_unshift( $state_meta, 'Select State' );
								
								$state_id = $wpdb->get_results( "SELECT id FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								
								$city_selected = $wpdb->get_results( "SELECT name FROM city where country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$city_meta = arrangeArrayPair( $city_selected, 'name', 'name' );
								array_unshift( $city_meta, 'Select City' );
								
								$city_data = $wpdb->get_results( "SELECT latitude, longitude FROM city where name = '". $city_value ."' AND country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1", ARRAY_A  );
								
								if( !empty ( $city_data ) ){
									$city_store_lat	= $city_data['0']['latitude'];
									$city_store_lng = $city_data['0']['longitude'];
								}
							}
						?>
							<input type="hidden" class="admin_state" name="admin_state" value="<?php echo $state_value; ?>" />
							<input type="hidden" class="admin_city" name="admin_city" value="<?php echo $city_value; ?>" />
						<?php							
						}
						/*date 22-02-2022 end*/
						
						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_physical_location', array(
							"country_value" => array(
								'label'       => esc_html__( 'Select Country', 'martfury' ),
								'type'        => 'select',
								'class'       => 'wcfm-select wcfm_ele simple variable external grouped booking',
								'label_class' => 'wcfm_cust_country',
								'options'     => $county_meta,
								'value' 	  => $country_value
							),
							"state_value" => array(
								'label'       => esc_html__( 'Select State', 'martfury' ),
								'type'        => 'select',
								'class'       => 'wcfm-select wcfm_ele simple variable external grouped booking',
								'label_class' => 'wcfm_cust_state',
								'options'     => $state_meta,
								'value' 	  => $state_value
							),
							"city_value" => array(
								'label'       => esc_html__( 'Select City', 'martfury' ),
								'type'        => 'select',
								'class'       => 'wcfm-select wcfm_ele simple variable external grouped booking',
								//'label_class' => 'wcfm_cust_state',
								'label_class' => 'wcfm_cust_city',
								'options'     => $city_meta,
								'value' 	  => $city_value
							),
							"city_store_lat" => array(
								'type' => 'hidden',
								'name' => 'city_store_lat',
								'value' => $city_store_lat
							),
							"city_store_lng" => array(
								'type' => 'hidden',
								'name' => 'city_store_lng',
								'value' => $city_store_lng
							),
						), $product_id ) );
					?>

					<?php
						global $WCFMmp;
						// Store Location	
						if( !current_user_can( 'administrator' ) ){						
							$find_address	= get_post_meta( $product_id, 'find_address', true );
							$store_location	= get_post_meta( $product_id, 'store_location', true );
							$store_lat		= get_post_meta( $product_id, 'store_lat', true );
							$store_lng		= get_post_meta( $product_id, 'store_lng', true );
							if( $find_address == '' ){
								$find_address = $vendor_data['geolocation']['store_location'];
								$store_location = $vendor_data['geolocation']['store_location'];
								$store_lat = $vendor_data['geolocation']['store_lat'];
								$store_lng = $vendor_data['geolocation']['store_lng'];
							}
						}else{

							/****get data from db start**/
							$country_value		= get_post_meta( $product_id, 'country_value', true );
							$state_value		= get_post_meta( $product_id, 'state_value', true );
							$city_value			= get_post_meta( $product_id, 'city_value', true );
							
							$city_store_lat		= get_post_meta( $product_id, 'city_store_lat', true );
							$city_store_lng		= get_post_meta( $product_id, 'city_store_lng', true );
							
							/*get data from db end*/
							if( !empty( $country_value ) && !empty( $state_value ) && !empty( $city_value )  ){
								$country_value	= $country_value;
								$state_value	= $state_value;
								$city_value		= $city_value;
								//$city_store_lat	= $city_store_lat;
								//$city_store_lng	= $city_store_lng;	
	
								$county_selected = $wpdb->get_results( "SELECT name, shortcode FROM country where isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$county_meta = arrangeArrayPair( $county_selected, 'shortcode', 'name' );
								array_unshift( $county_meta, 'Select Country' );
								
								$country_id = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $country_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								
								//$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where shortcode = '". $state_value ."' AND country_id = '". $country_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where country_id = '". $country_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$state_meta = arrangeArrayPair( $state_selected, 'shortcode', 'name' );
								array_unshift( $state_meta, 'Select State' );
								
								$state_id = $wpdb->get_results( "SELECT id FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								
								$city_selected = $wpdb->get_results( "SELECT name FROM city where country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								$city_meta = arrangeArrayPair( $city_selected, 'name', 'name' );
								array_unshift( $city_meta, 'Select City' );
								
								$city_data = $wpdb->get_results( "SELECT latitude, longitude FROM city where name = '". $city_value ."' AND country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1", ARRAY_A  );
								
								if( !empty ( $city_data ) ){
									$store_lat	= $city_data['0']['latitude'];
									$store_lng = $city_data['0']['longitude'];
								}
								//$find_address = $city_data['0']['name'] .' '. $state_id['0']['name'].' '. $country_id['0']['name'];
								//$store_location = $city_data['0']['name'] .' '. $state_id['0']['name'].' '. $country_id['0']['name'];
								
							}else{
								$get_country_state = get_option( 'woocommerce_default_country' );
								$city_value		   = get_option( 'woocommerce_store_city' );
								$country_state_array = explode( ':', $get_country_state );
								
								$country_value = $country_state_array['0'];
								$state_value = $country_state_array['1'];
								
								$country_id = $wpdb->get_results( "SELECT id, name FROM country where shortcode = '". $country_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								
								$state_id = $wpdb->get_results( "SELECT id, name FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
								
								$city_data = $wpdb->get_results( "SELECT name, latitude, longitude FROM city where name = '". $city_value ."' AND country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1", ARRAY_A  );
								
								if( !empty ( $city_data ) ){
									$store_lat	= $city_data['0']['latitude'];
									$store_lng = $city_data['0']['longitude'];
								}							
								$find_address = $city_data['0']['name'] .' '. $state_id['0']['name'].' '. $country_id['0']['name'];
								$store_location = $city_data['0']['name'] .' '. $state_id['0']['name'].' '. $country_id['0']['name'];
							}
						}

						$api_key = isset( $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] ) ? $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] : '';
						$wcfm_map_lib = isset( $WCFMmp->wcfmmp_marketplace_options['wcfm_map_lib'] ) ? $WCFMmp->wcfmmp_marketplace_options['wcfm_map_lib'] : '';

						//echo "<br> API KEY => ".$api_key;
						//echo "<br> wcfm_map_lib => ".$wcfm_map_lib;
						
						if( !$wcfm_map_lib && $api_key ) { $wcfm_map_lib = 'google'; } elseif( !$wcfm_map_lib && !$api_key ) { $wcfm_map_lib = 'leaftlet'; }
						if ( apply_filters( 'wcfm_is_allow_store_map_location', true ) && ( ( ($wcfm_map_lib == 'google') && !empty( $api_key ) ) || ($wcfm_map_lib == 'leaflet') ) ) {
							?>
							<div class="wcfm_clearfix"></div><br />
							<div class="wcfm_vendor_settings_heading"><h2><?php _e( 'Product Map Location', 'wc-frontend-manager' ); ?></h2></div>
							<div class="wcfm_clearfix"></div>
							<div class="store_address store_location_wrap">
								<?php
									//$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_wcmarketplace_settings_fields_location', array(
									$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_badges', array(
										"find_address" => array( 'label' => __( 'Find Location', 'wc-frontend-manager' ),
											'placeholder' => __( 'Type an address to find', 'wc-frontend-manager' ),
											'name' => 'geolocation[find_address]',
											'type' => 'text',
											'class' => 'wcfm-text wcfm_ele',
											'label_class' => 'wcfm_title wcfm_ele',
											'value' => $find_address
										),
										"store_location" => array(
											'type' => 'hidden',
											'name' => 'geolocation[store_location]',
											'value' => $store_location
										),
										"store_lat" => array(
											'type' => 'hidden',
											'name' => 'geolocation[store_lat]',
											'value' => $store_lat
										),
										"store_lng" => array(
											'type' => 'hidden',
											'name' => 'geolocation[store_lng]',
											'value' => $store_lng
										),
									), $product_id ) );
								?>
								<div class="wcfm_clearfix"></div><br />
								<div class="wcfm-marketplace-google-map" id="wcfm-marketplace-map"></div>
								<div class="wcfm_clearfix"></div><br />
							</div>

					<?php } ?>
				</div>
			</div>
			<!-- end collapsible -->
			<div class="wcfm_clearfix"></div>
		<?php } ?>
		<!-- Product Location End -->

		<!-- Product Advertisement Location start -->	
		<?php if ( in_array( 'product_adv_location', martfury_get_option( 'wcfm_dashboard_custom_fields' ) ) ) { ?>		
			<div class="page_collapsible products_cust_location" id="wcfm_products_adv_location" <?php echo apply_filters( 'wcfm_pm_block_class_linked', 'simple variable external grouped' ); ?> <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>>
				<label class="wcfmfa fa-street-view"></label><?php esc_html_e( 'Product Advertisement Location', 'martfury' ); ?><span></span>
			</div>

			<div class="wcfm-container products_adv_location_wrap simple variable external grouped <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>">
				<!--div id="wcfm_products_adv_manage_form_linked_expander" class="wcfm-content">
					<p><strong><?php //esc_html_e( 'Product Advertisement Location', 'martfury' ); ?></strong></p>
				</div-->

				<!-- repeater fields strat -->
				<div class="product_location_rep_wrap">
					<div id="wcfm_settings_form_store_hours_expander" class="wcfm-content">
						  <div class="wcfm_clearfix"></div><br />
						  <p class="adv-product-label"><strong><?php _e( 'Advertisement Location', 'wc-multivendor-marketplace' ); ?></strong></p>
							<div class="wcfm_clearfix"></div>
							<div class="store_address">
							<?php
								//date 01-03-2022 start	
								$wcfm_store_rep_location = get_post_meta( $product_id, 'wcfm_store_rep_location_options', true );
								if( !empty ( $wcfm_store_rep_location ) ){					
									foreach( $wcfm_store_rep_location as $wcfm_store_rep_location_list ){
										foreach( $wcfm_store_rep_location_list as $wcfm_store_rep_location_list_array ){
											$wcfm_store_avd_rep_location =  $wcfm_store_rep_location_list_array;
										}
									}
								}else{									
									$wcfm_store_avd_rep_location = array();								
									if( !current_user_can( 'administrator' ) ){	
										$find_address_prod	= get_post_meta( $product_id, 'find_address', true );
										if( empty( $find_address_prod ) ){
											$rep_country_value	= $vendor_data['address']['country'];
											$rep_state_value	= $vendor_data['address']['state'];
											$rep_city_value	= $vendor_data['address']['city'];
											
											$find_address = $vendor_data['geolocation']['store_location'];
											$store_location = $vendor_data['geolocation']['store_location'];
											
										}else{
											$rep_country_value	= get_post_meta( $product_id, 'country_value', true );
											$rep_state_value	= get_post_meta( $product_id, 'state_value', true );
											$rep_city_value		= get_post_meta( $product_id, 'city_value', true );
							
											$find_address 	= $find_address_prod;
											$store_location	= get_post_meta( $product_id, 'store_location', true );										
										}	
										
										//Get Country, State and City from DB start
										$county_selected = $wpdb->get_results( "SELECT name, shortcode FROM country where isactive = 1 ORDER BY name ASC", ARRAY_A  );
										$county_meta = arrangeArrayPair( $county_selected, 'shortcode', 'name' );
										array_unshift( $county_meta, 'Select Country' );
										
										$country_id = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $country_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
							
										$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
										$state_meta = arrangeArrayPair( $state_selected, 'shortcode', 'name' );
										array_unshift( $state_meta, 'Select State' );
										
										$state_id = $wpdb->get_results( "SELECT id FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
										
										$city_selected = $wpdb->get_results( "SELECT name FROM city where country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
										$city_meta = arrangeArrayPair( $city_selected, 'name', 'name' );
										array_unshift( $city_meta, 'Select City' );
										
										$city_data = $wpdb->get_results( "SELECT latitude, longitude FROM city where name = '". $city_value ."' AND country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1", ARRAY_A  );
										
										if( !empty ( $city_data ) ){
											$city_store_lat	= $city_data['0']['latitude'];
											$city_store_lng = $city_data['0']['longitude'];
										}										
										//Get Country, State and City from DB end											
									}else{	
										$get_country_state = get_option( 'woocommerce_default_country' );
										$rep_city_value	   = get_option( 'woocommerce_store_city' );
										$country_state_array = explode( ':', $get_country_state );
										
										$rep_country_value = $country_state_array['0'];
										$rep_state_value = $country_state_array['1'];
										
										$county_selected = $wpdb->get_results( "SELECT name, shortcode FROM country where isactive = 1 ORDER BY name ASC", ARRAY_A  );
										$county_meta = arrangeArrayPair( $county_selected, 'shortcode', 'name' );
										array_unshift( $county_meta, 'Select Country' );
										
										$country_id = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $country_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
							
										$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where country_id = '". $country_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
										$state_meta = arrangeArrayPair( $state_selected, 'shortcode', 'name' );
										array_unshift( $state_meta, 'Select State' );
										
										$state_id = $wpdb->get_results( "SELECT id FROM state where country_id = '". $country_id['0']['id'] ."' AND shortcode = '". $state_value ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
										
										$city_selected = $wpdb->get_results( "SELECT name FROM city where country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
										$city_meta = arrangeArrayPair( $city_selected, 'name', 'name' );
										array_unshift( $city_meta, 'Select City' );
										
										$city_data = $wpdb->get_results( "SELECT latitude, longitude FROM city where name = '". $city_value ."' AND country_id = '". $country_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1", ARRAY_A  );
										
										if( !empty ( $city_data ) ){
											$city_store_lat	= $city_data['0']['latitude'];
											$city_store_lng = $city_data['0']['longitude'];
										}
									}
								}
							?>							
							<?php if( !current_user_can( 'administrator' ) ){ ?>
								<div class="product_rep_country_hide" style="display:none;">
									<ul>
										<?php if( is_array( $wcfm_store_avd_rep_location ) ){ ?>
											<?php foreach( $wcfm_store_avd_rep_location as $store_avd_rep_location_data ){ ?>
												<li data-country="<?php echo $store_avd_rep_location_data['rep_country_value']; ?>" data-state="<?php echo $store_avd_rep_location_data['rep_state_value']; ?>" data-city="<?php echo $store_avd_rep_location_data['rep_city_value']; ?>"><?php echo $store_avd_rep_location_data['rep_country_value']; ?></li>

											<?php } ?>
										<?php }else{
											$str_arr = explode (",", $wcfm_store_avd_rep_location);
										?>
											<li data-country="<?php echo $vendor_data['address']['country']; ?>" data-state="<?php echo $vendor_data['address']['state']; ?>" data-city="<?php echo $vendor_data['address']['city_name_select_ls']; ?>" ><?php echo $vendor_data['address']['country']; ?></li>
										<?php } ?>
									</ul>
									<!-- for check product location -->
									<div class="check_product_loc_default">										
										<ul>
										<?php
											$rep_country_value_hide	= get_post_meta( $product_id, 'country_value', true );
											$rep_state_value_hide	= get_post_meta( $product_id, 'state_value', true );
											$rep_city_value_hide	= get_post_meta( $product_id, 'city_value', true );
											if( $rep_country_value_hide == '' ){
												$rep_country_value_hide	= $vendor_data['address']['country'];
												$rep_state_value_hide	= $vendor_data['address']['state'];
												$rep_city_value_hide	= $vendor_data['address']['city'];												
											}										
										?>
											<li class="country" data-country="<?php echo $rep_country_value_hide; ?>" data-state="<?php echo $rep_state_value_hide; ?>" data-city="<?php echo $rep_city_value_hide; ?>">country</li>
										</ul>
									</div>
								</div>
							<?php }else{ ?>
								<div class="product_rep_country_hide" style="display:none;">
									<ul>
										<?php if( is_array( $wcfm_store_avd_rep_location ) ){ ?>
											<?php foreach( $wcfm_store_avd_rep_location as $store_avd_rep_location_data ){ ?>
												<li data-country="<?php echo $store_avd_rep_location_data['rep_country_value']; ?>" data-state="<?php echo $store_avd_rep_location_data['rep_state_value']; ?>" data-city="<?php echo $store_avd_rep_location_data['rep_city_value']; ?>"><?php echo $store_avd_rep_location_data['rep_country_value']; ?></li>

											<?php } ?>
										<?php }else{
											$str_arr = explode (",", $wcfm_store_avd_rep_location);
										?>
											<li data-country="<?php echo $vendor_data['address']['country']; ?>" data-state="<?php echo $vendor_data['address']['state']; ?>" data-city="<?php echo $vendor_data['address']['city_name_select_ls']; ?>" ><?php echo $vendor_data['address']['country']; ?></li>
										<?php } ?>
									</ul>
									<!-- for check product location -->
									<div class="check_product_loc_default">										
										<ul>
										<?php
											$rep_country_value_hide	= get_post_meta( $product_id, 'country_value', true );
											$rep_state_value_hide	= get_post_meta( $product_id, 'state_value', true );
											$rep_city_value_hide	= get_post_meta( $product_id, 'city_value', true );
											if( $rep_country_value_hide == '' ){
												$get_country_state	 = get_option( 'woocommerce_default_country' );
												$rep_city_value_hide = get_option( 'woocommerce_store_city' );
												$country_state_array = explode( ':', $get_country_state );
												
												$rep_country_value_hide = $country_state_array['0'];
												$rep_state_value_hide 	= $country_state_array['1'];
											}										
										?>
											<li class="country" data-country="<?php echo $rep_country_value_hide; ?>" data-state="<?php echo $rep_state_value_hide; ?>" data-city="<?php echo $rep_city_value_hide; ?>">country</li>
										</ul>
									</div>
								</div>								
							<?php } ?>								
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_advertisemen_location', array(
									"wcfm_store_avd_rep_location" => array(
										'label' => __( '', 'wc-multivendor-marketplace'),
										'name' => 'wcfm_rep_adv_location[adv_location][0]',
										'type' => 'multiinput',
										'class' => 'wcfm_store_adv_location_fields wcfm_store_adv_location_fields_0',
										'label_class' => 'wcfm_title wcfm_store_adv_location_fields wcfm_store_adv_location_fields_0',
										'value' => $wcfm_store_avd_rep_location,
										'options' => array(
											"rep_country_value" => array(
												'label'		  => __('Select Country', 'wc-multivendor-marketplace'),
												'type' 		  => 'select',
												'class' 	  => 'wcfm-select wcfm_store_country_field',
												'label_class' => 'wcfm_title wcfm_store_country_label',
												'options'     => $county_meta,
												'value' 	  => $rep_country_value
											),
											"rep_state_value" => array(
												'label'		  => __( 'Select State', 'wc-multivendor-marketplace' ),
												'type'		  => 'select',
												'class' 	  => 'wcfm-select wcfm_store_state_field',
												'label_class' => 'wcfm_title wcfm_store_state_label',
												'options'     => $state_meta,
												'value' 	  => $rep_state_value
											),
											"rep_city_value" => array(
												'label'		  => __( 'Select City', 'wc-multivendor-marketplace' ),
												'type'		  => 'select',
												'class' 	  => 'wcfm-select wcfm_store_city_field',
												'label_class' => 'wcfm_title wcfm_store_city_label',
												'options'     => $city_meta,
												'value' 	  => $rep_city_value
											),											
										)
									),
								), $product_id ) );
							?>
						  </div>
					</div>
				</div>
				<div class="wcfm_clearfix"></div><br/>
				<!-- repeater fields end -->
			</div>
		<?php } ?>		
		<!-- Product Advertisement Location end -->

		<!-- collapsible 8 - Custom  Badges -->
		<div class="page_collapsible products_badges_view <?php echo apply_filters( 'wcfm_pm_block_class_linked', 'simple variable external grouped' ); ?> <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>"
		     id="wcfm_products_manage_form_linked_head"><label
				class="wcfmfa fa-globe"></label><?php esc_html_e( 'Badges', 'martfury' ); ?>
			<span></span>
		</div>
		<div class="wcfm-container simple variable external grouped <?php echo apply_filters( 'wcfm_pm_block_custom_class_linked', '' ); ?>">
			<div id="wcfm_products_manage_form_linked_expander" class="wcfm-content">
				<?php
				$custom_badges_text = get_post_meta( $product_id, 'custom_badges_text', true );
				$is_new             = get_post_meta( $product_id, '_is_new', true );
				$_is_new_enable     = $is_new === 'yes' ? 'enable' : '';
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_badges', array(
					"custom_badges_text" => array(
						'label'       => esc_html__( 'Custom Badge Text', 'martfury' ),
						'type'        => 'text',
						'class'       => 'wcfm-text wcfm_ele simple variable external grouped booking',
						'label_class' => 'wcfm_title',
						'value'       => $custom_badges_text
					),
					"_is_new"            => array(
						'label'       => esc_html__( 'New product?', 'martfury' ),
						'type'        => 'checkbox',
						'class'       => 'wcfm-checkbox wcfm_ele simple variable external grouped booking',
						'label_class' => 'wcfm_title',
						'hints'       => esc_html__( 'Enable to set this product as a new product. A "New" badge will be added to this product.', 'martfury' ),
						'value'       => 'enable',
						'dfvalue'     => $_is_new_enable
					),
				), $product_id ) );
				?>
			</div>
		</div>
		<!-- end collapsible -->
		<div class="wcfm_clearfix"></div>
		<?php
	}

	function stores_list_default_args( $default ) {
		$default['per_row']  = 2;
		$default['per_page'] = 8;
		$default['theme']    = '';

		return $default;
	}

}

