<?php
/**
 * DrFuri Core functions and definitions
 *
 * @package Martfury
 */


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function martfury_setup() {
	// Sets the content width in pixels, based on the theme's design and stylesheet.
	$GLOBALS['content_width'] = apply_filters( 'martfury_content_width', 840 );

	// Make theme available for translation.
	load_theme_textdomain( 'martfury', get_template_directory() . '/lang' );

	// Theme supports
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'audio', 'gallery', 'video', 'quote', 'link' ) );
	add_theme_support(
		'html5', array(
			'comment-list',
			'search-form',
			'comment-form',
			'gallery',
		)
	);

	if ( martfury_fonts_url() ) {
		add_editor_style( array( 'css/editor-style.css', martfury_fonts_url() ) );
	} else {
		add_editor_style( 'css/editor-style.css' );
	}

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	add_theme_support( 'align-wide' );

	add_theme_support( 'align-full' );

	// Register theme nav menu
	$nav_menu = array(
		'primary'         => esc_html__( 'Primary Menu', 'martfury' ),
		'shop_department' => esc_html__( 'Shop By Department Menu', 'martfury' ),
		'mobile'          => esc_html__( 'Mobile Header Menu', 'martfury' ),
		'category_mobile' => esc_html__( 'Mobile Category Menu', 'martfury' ),
		'user_logged'     => esc_html__( 'User Logged Menu', 'martfury' ),
		'store'           => esc_html__( 'Store Menu', 'martfury' ),
	);
	if ( martfury_has_vendor() ) {
		$nav_menu['vendor_logged'] = esc_html__( 'Vendor Logged Menu', 'martfury' );
	}
	register_nav_menus( $nav_menu );

	add_image_size( 'martfury-blog-grid', 380, 300, true );
	add_image_size( 'martfury-blog-list', 790, 510, true );
	add_image_size( 'martfury-blog-masonry', 370, 588, false );

	global $martfury_woocommerce;
	$martfury_woocommerce = new Martfury_WooCommerce;

	global $martfury_mobile;
	$martfury_mobile = new Martfury_Mobile;

}

add_action( 'after_setup_theme', 'martfury_setup', 100 );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function martfury_register_sidebar() {
	// Register primary sidebar
	$sidebars = array(
		'blog-sidebar'    => esc_html__( 'Blog Sidebar', 'martfury' ),
		//'topbar-left'     => esc_html__( 'Topbar Left', 'martfury' ),
		//'topbar-right'    => esc_html__( 'Topbar Right', 'martfury' ),
		//'topbar-mobile'   => esc_html__( 'Topbar on Mobile', 'martfury' ),
		'header-bar'      => esc_html__( 'Header Bar', 'martfury' ),
		'post-sidebar'    => esc_html__( 'Single Post Sidebar', 'martfury' ),
		'page-sidebar'    => esc_html__( 'Page Sidebar', 'martfury' ),
		'catalog-sidebar' => esc_html__( 'Catalog Sidebar', 'martfury' ),
		'product-sidebar' => esc_html__( 'Single Product Sidebar', 'martfury' ),
		'footer-links'    => esc_html__( 'Footer Links', 'martfury' ),
	);

	if ( class_exists( 'WC_Vendors' ) || class_exists( 'WCMp' ) ) {
		$sidebars['vendor_sidebar'] = esc_html( 'Vendor Sidebar', 'martfury' );
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 2; $i ++ ) {
		$sidebars["footer-sidebar-$i"] = esc_html__( 'Footer', 'martfury' ) . " $i";
	}

	$custom_sidebar = martfury_get_option( 'custom_product_cat_sidebars' );
	if ( $custom_sidebar ) {
		foreach ( $custom_sidebar as $sidebar ) {
			if ( ! isset( $sidebar['title'] ) || empty( $sidebar['title'] ) ) {
				continue;
			}
			$title                                = $sidebar['title'];
			$sidebars[ sanitize_title( $title ) ] = $title;
		}
	}

	// Register sidebars
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

}

add_action( 'widgets_init', 'martfury_register_sidebar' );

/**
 * Load theme
 */

// customizer hooks

require get_template_directory() . '/inc/mobile/theme-options.php';
require get_template_directory() . '/inc/vendors/theme-options.php';
require get_template_directory() . '/inc/backend/customizer.php';

// layout
require get_template_directory() . '/inc/functions/layout.php';

require get_template_directory() . '/inc/functions/entry.php';


// Woocommerce
require get_template_directory() . '/inc/frontend/woocommerce.php';

require get_template_directory() . '/inc/woo_addon.php';

// Vendor
require get_template_directory() . '/inc/vendors/vendors.php';

// Mobile
require get_template_directory() . '/inc/libs/mobile_detect.php';
require get_template_directory() . '/inc/mobile/layout.php';

require get_template_directory() . '/inc/functions/media.php';

require get_template_directory() . '/inc/functions/header.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/backend/plugins.php';
	require get_template_directory() . '/inc/backend/meta-boxes.php';
	require get_template_directory() . '/inc/backend/product-cat.php';
	require get_template_directory() . '/inc/backend/product-meta-box-data.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu.php';
	require get_template_directory() . '/inc/backend/editor.php';

} else {
	// Frontend functions and shortcodes
	require get_template_directory() . '/inc/functions/nav.php';
	require get_template_directory() . '/inc/functions/breadcrumbs.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu-walker.php';
	require get_template_directory() . '/inc/mega-menu/class-mobile-walker.php';
	require get_template_directory() . '/inc/functions/comments.php';
	require get_template_directory() . '/inc/functions/footer.php';

	// Frontend hooks
	require get_template_directory() . '/inc/frontend/layout.php';
	require get_template_directory() . '/inc/frontend/nav.php';
	require get_template_directory() . '/inc/frontend/entry.php';
	require get_template_directory() . '/inc/frontend/footer.php';
}

require get_template_directory() . '/inc/frontend/header.php';
require get_template_directory() . '/inc/meta-box/meta-box.php';




add_action('wp', 'product_view_counter');
function product_view_counter() {

     global $post;
     if ( is_product()  || is_page() || is_single() || is_shop() ){
         $meta = get_post_meta( $post->ID, '_total_views_count', TRUE );
         $meta = ($meta) ? $meta + 1 : 1;
         update_post_meta( $post->ID, '_total_views_count', $meta );
     }

	 if ( is_category() || is_product_category() ) {
        $views = intval( get_term_meta( get_queried_object_id(), '_total_views_count', true ) );
        update_term_meta( get_queried_object_id(), '_total_views_count', $views + 1 );
    }


}






function show_page_counter() {
   if ( is_category() || is_product_category() ) {
		$views = intval( get_term_meta( get_queried_object_id(), '_total_views_count', true ) );
		echo '<div class="custom-visitor-count"><i class="fa fa-eye"></i><span class="counter-value"> '. $views.'</span></div>';
  	 }
   else {
		global $wp_query;
		$id = $wp_query->post->ID;
		$meta = get_post_meta( $id, '_total_views_count', true);
		if(!$meta) {
			$count = 0;
		} else {
			$count = $meta;
		}
		echo '<div class="custom-visitor-count"><i class="fa fa-eye"></i><span class="counter-value"> '. $count.'</span></div>';
  	 }
}



class My_Walker_Category extends Walker_Category {

	/**
	* @see Walker::start_el()
	* @since 2.1.0
	*
	* @param string $output Passed by reference. Used to append additional content.
	* @param object $category Category data object.
	* @param int $depth Depth of category in reference to parents.
	* @param array $args
	*/
	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

	  global $wp_query;
	extract($args);

	$cat_name = esc_attr( $category->name );
	$cat_name = apply_filters( 'list_cats', $cat_name, $category );
	$link = '<a href="' . esc_url( get_term_link($category) ) . '" ';
			if ( $use_desc_for_title == 0 || empty($category->description) )
				$link .= 'title="' . esc_attr( sprintf(__( 'View all posts filed under %s' ), $cat_name) ) . '"';
			else
				$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
			$link .= '>';
			$link .= $cat_name . '</a>';

			if ( !empty($feed_image) || !empty($feed) ) {
				$link .= ' ';

				if ( empty($feed_image) )
					$link .= '(';

				$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $feed_type ) ) . '"';

				if ( empty($feed) ) {
					$alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
				} else {
					$title = ' title="' . $feed . '"';
					$alt = ' alt="' . $feed . '"';
					$name = $feed;
					$link .= $title;
				}

				$link .= '>';

				if ( empty($feed_image) )
					$link .= $name;
				else
					$link .= "<img src='$feed_image'$alt$title" . ' />';

				$link .= '</a>';

				if ( empty($feed_image) )
					$link .= ')';
			}

			if ( !empty($show_count) )
				$link .= ' (' . intval($category->count) . ')';

			if ( 'list' == $args['style'] ) {
				$output .= "\t<li";

				$class = 'cat-item cat-item-' . $category->term_id;

				/** If the current category is a top level element and it has children, add a parent class */
				if($category->category_parent == 0 && $category->hasChildren == true )
					$class .= ' parent-item';
				if ( !empty($current_category) ) {
					$_current_category = get_term( $current_category, $category->taxonomy );
					if ( $category->term_id == $current_category )
						$class .=  ' current-cat';
					elseif ( $category->term_id == $_current_category->parent )
						$class .=  ' current-cat-parent';
				}
				$output .=  ' class="' . $class . '"';
				$output .= ">$link\n";
			} else {
				$output .= "\t$link<br />\n";
			}
		}

		function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
		{
			// check, whether there are children for the given ID and append it to the element with a (new) ID
			$element->hasChildren =  !empty($children_elements[$element->term_id]);

			return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
		}
	}





	add_action('wp_ajax_delete_album_entry', 'delete_album_entry', 0);
	add_action('wp_ajax_nopriv_delete_album_entry', 'delete_album_entry');
	function delete_album_entry() {

		$selected_country  = $_POST['postID'];
		global $woocommerce;
		$countries_obj   = new WC_Countries();
		$countries   = $countries_obj->__get('countries');
		$default_country = $countries_obj->get_base_country();
		$default_country = $selected_country;
		$default_county_states = $countries_obj->get_states( $default_country );




				echo '<div id="country_box test">';

				woocommerce_form_field('ethio_country', array(
				'type'       => 'country',
				'class'      => array( 'chzn-drop' ),
				'label'      => __('Country'),
				'placeholder'    => __('Select a Country'),
				'options'    => $countries,
				'default' => $default_country,
				'required'    => true
					)
				);
			echo '</div>';

			echo '<div id="state_box">';

				woocommerce_form_field('ethio_state', array(
					'type'       => 'select',
					'class'      => array( 'chzn-drop' ),
					'label'      => __('State'),
					'placeholder'    => __('Select a State'),
					'options'    => $default_county_states,
					'required'    => true
					)
				);
			echo '</div>';



			die;
	}



	function wcfm_profile_custom_fields_phone( $profile_fileds ) {
		global $WCFM;
		if( isset( $profile_fileds['phone'] ) ) {
			$profile_fileds['phone']['type'] = 'number';
		}
		return $profile_fileds;
	}
	add_filter( 'wcfm_profile_fields_phone', 'wcfm_profile_custom_fields_phone' );

	
		
	function wcfm_profile_custom_fields_bphone( $profile_fileds ) {
		global $WCFM;
		if( isset( $profile_fileds['bphone'] ) ) {
			$profile_fileds['bphone']['type'] = 'number';
		}
		return $profile_fileds;
	}
	add_filter( 'wcfm_customer_fields_billing', 'wcfm_profile_custom_fields_bphone' );

	function settings_fields_customer_support( $profile_fileds ) {
		global $WCFM;
		if( isset( $profile_fileds['vendor_customer_phone'] ) ) {
			$profile_fileds['vendor_customer_phone']['type'] = 'number';
		}
		return $profile_fileds;
	}
	add_filter( 'wcfm_wcmarketplace_settings_fields_customer_support', 'settings_fields_customer_support' );

	function settings_wcfm_marketplace_settings_fields_general( $profile_fileds ) {
		global $WCFM;
		if( isset( $profile_fileds['phone'] ) ) {
			$profile_fileds['phone']['type'] = 'number';
		}
		return $profile_fileds;
	}
	add_filter( 'wcfm_marketplace_settings_fields_general', 'settings_wcfm_marketplace_settings_fields_general' );


	// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
	$fields['billing']['billing_phone']['type'] = 'number';
     $fields['billing']['billing_postcode']['type'] = 'number';
    
     return $fields;
}




// Add term and conditions check box on registration form
add_action( 'woocommerce_register_form', 'add_terms_and_conditions_to_registration', 20 );
function add_terms_and_conditions_to_registration() {
    if ( wc_get_page_id( 'terms' ) > 0 && is_account_page() ) {   ?>
        <p class="form-row terms wc-terms-and-conditions">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms"  /><span>
				<?php printf( __( 'I&rsquo;ve read and agree with <a href="%s" target="_blank" class="woocommerce-terms-and-conditions-link">Privacy Policy</a>', 'woocommerce' ), esc_url( get_privacy_policy_url( 'privacy-policy' ) ) ); ?>
				<?php printf( __( '&<a href="%s" target="_blank" class="woocommerce-terms-and-conditions-link"> Terms of Use</a>', 'woocommerce' ), esc_url( wc_get_page_permalink( 'terms' ) ) ); ?> </span>   </label>
			
				<input type="hidden" name="terms-field" value="1" />
        </p>
    <?php
    }
}

// Validate required term and conditions check box
add_action( 'woocommerce_register_post', 'terms_and_conditions_validation', 20, 3 );
function terms_and_conditions_validation( $username, $email, $validation_errors ) {
    if ( ! isset( $_POST['terms'] ) )
        $validation_errors->add( 'terms_error', __( 'Privacy Policy and Terms of Use are not checked!', 'woocommerce' ) );

    return $validation_errors;
}

/* 01-02-2022 */
//add_action( 'init', 'get_add_from_ip' );

add_action( 'woocommerce_product_query', 'ethio_action_woocommerce_product_query', 10, 2 );
function ethio_action_woocommerce_product_query($query, $other){
	global $WCFM, $WCFMmp, $post;
	$repeater_product_id_filter = $premium_product_id = $featured_product_id = $final_product_id = array();
	session_start();
	
	if( isset ( $_GET['ethio_country'] )){
		$filter_country	= $_GET['ethio_country'];
		$filter_state	= $_GET['ethio_state'];
		$filter_city	= $_GET['wcfmmp_city'];			
	}else{
		$filter_country = '';		
	}
	if( $filter_country != '' ){
		// In url get filter value
		$ip_country = $filter_country;
		$ip_state	= $filter_state;
		$ip_city	= $filter_city;
		
		$_SESSION['sess_ip_country'] = $ip_country;
		$_SESSION['sess_ip_state']	 = $ip_state;
		$_SESSION['sess_ip_city']	 = $ip_city;		
	}else{
		// In url get not filter value
		if( isset( $_SESSION['sess_ip_country'] ) ){
			if( is_shop() ){
				$ip_country	= '0';
				$ip_state	= '0';
				$ip_city	= '0';
			}else{
				$ip_country	= $_SESSION['sess_ip_country'];
				$ip_state	= $_SESSION['sess_ip_state'];
				$ip_city	= $_SESSION['sess_ip_city'];
			}			
		}else{
			if( is_shop() ){	
				$ip_country	= '0';
				$ip_state	= '0';
				$ip_city	= '0';
			}else{
				$ip_array	= ethio_get_ip_detail( $_SERVER['REMOTE_ADDR'] );
				$ip_country	= $ip_array->countryCode;
				$ip_state	= $ip_array->region;
				$ip_city	= $ip_array->city;				
			}
		}		
	}
	
	if( isset( $_GET['s'] ) && isset( $_GET['allcountry'] )  ){
		$ip_country	= $ip_state	= $ip_city	= '';
	}
	
	/* Side bar filter query add start */
	if( ( isset( $_GET['min_price'] ) &&  isset( $_GET['max_price'] ) ) ){
		$meta_query1  = WC()->query->get_meta_query();
		$meta_query1[] = array(
			'key'		=> '_price', //_regular_price , _sale_price
			'value'		=> array( $_GET['min_price'], $_GET['max_price']),
            'compare'	=> 'BETWEEN',
            'type'		=> 'NUMERIC'
		);
	}else{
		$meta_query1 = '';
	}
	
	if( isset( $_GET['product_brand'] ) ){
		$tax_query1   = WC()->query->get_tax_query();
		$tax_query1[] = array(
			array(
				'taxonomy'	=> 'product_brand',
				'field'		=> 	'slug',
				'terms'		=> array( $_GET['product_brand'] ),
				'operator'	=> 'IN',
			)
		);
	}else{
		$tax_query1 = '';
	}
	/* Side bar filter query add end */
	
	//Get Custom location and filter product start
	if( is_search() ) {
		$custom_args = array(
			'post_type'			=> 'product',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'meta_query'		=> $meta_query1,
			's' 				=> $_GET['s'],
			//'tax_query'			=> $tax_query1,
			//'suppress_filters'	=> 0
		);
	}else{
		$custom_args = array(
			'post_type'			=> 'product',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'meta_query'		=> $meta_query1,
			//'tax_query'			=> $tax_query1,
			//'suppress_filters'	=> 0
		);
	}
	$custom_ps = get_posts( $custom_args );

	//Get city
	foreach( $custom_ps as $ps_detail ) {		
		$wcfm_store_rep_location = get_post_meta( $ps_detail->ID, 'wcfm_store_rep_location_options', true );
		if( !empty( $wcfm_store_rep_location ) ){
			foreach( $wcfm_store_rep_location as $wcfm_store_rep_location_list ){
				foreach( $wcfm_store_rep_location_list as $wcfm_store_rep_location_list_array ){											
					foreach( $wcfm_store_rep_location_list_array as $custom_rep_location_list_array ){	
						if( ( $custom_rep_location_list_array['rep_city_value'] == $ip_city && $custom_rep_location_list_array['rep_city_value'] != '0' ) && $custom_rep_location_list_array['rep_country_value'] == $ip_country ) {
							$repeater_product_id_filter[] = $ps_detail->ID;								
						}						
					}
				}
			}									
		}			
	}
	//Get state
	if( empty( $repeater_product_id_filter ) ){
		foreach( $custom_ps as $ps_detail ) {		
			$wcfm_store_rep_location = get_post_meta( $ps_detail->ID, 'wcfm_store_rep_location_options', true );		
				foreach( $wcfm_store_rep_location as $wcfm_store_rep_location_list ){
					foreach( $wcfm_store_rep_location_list as $wcfm_store_rep_location_list_array ){											
						foreach( $wcfm_store_rep_location_list_array as $custom_rep_location_list_array ){
						if( ( $custom_rep_location_list_array['rep_state_value'] == $ip_state && $custom_rep_location_list_array['rep_state_value'] != '0' ) && $custom_rep_location_list_array['rep_country_value'] == $ip_country ) {
							$repeater_product_id_filter[] = $ps_detail->ID;							
						}							
					}
				}
			}
		}
	}
	
	//Get country
	if( empty( $repeater_product_id_filter ) ){
		foreach( $custom_ps as $ps_detail ) {		
			$wcfm_store_rep_location = get_post_meta( $ps_detail->ID, 'wcfm_store_rep_location_options', true );			
			foreach( $wcfm_store_rep_location as $wcfm_store_rep_location_list ){
				foreach( $wcfm_store_rep_location_list as $wcfm_store_rep_location_list_array ){											
					foreach( $wcfm_store_rep_location_list_array as $custom_rep_location_list_array ){	
						if( $custom_rep_location_list_array['rep_country_value'] == $ip_country && $custom_rep_location_list_array['rep_country_value'] != '0' ) {
							$repeater_product_id_filter[] = $ps_detail->ID;								
						}							
					}
				}				
			}
		}
	}
	
	if( !isset( $_GET['ethio_state'] ) ){
		if ( count( $repeater_product_id_filter ) < 5 ){
			foreach( $custom_ps as $ps_detail ) {		
				$wcfm_store_rep_location = get_post_meta( $ps_detail->ID, 'wcfm_store_rep_location_options', true );
				if( !empty( $wcfm_store_rep_location ) ){				
					foreach( $wcfm_store_rep_location as $wcfm_store_rep_location_list ){
						foreach( $wcfm_store_rep_location_list as $wcfm_store_rep_location_list_array ){											
							foreach( $wcfm_store_rep_location_list_array as $custom_rep_location_list_array ){						
								if(  $custom_rep_location_list_array['rep_state_value'] == $ip_state ) {
									$repeater_product_id_filter[] = $ps_detail->ID;	
								}
								
							}
						}
					}
				}
			}
		}
	}
	
	//search product by all county start
	if( isset( $_GET['s'] ) && isset( $_GET['allcountry'] )  ){
		$repeater_product_id_filter = array();
		foreach( $custom_ps as $ps_detail ) {
			$repeater_product_id_filter[] = $ps_detail->ID;			
		}
	}
	//search product by all county end
	
	if( !isset( $_GET['ethio_country'] ) ){	
		if ( count( $repeater_product_id_filter ) < 5 ){
			foreach( $custom_ps as $ps_detail ) {		
				$wcfm_store_rep_location = get_post_meta( $ps_detail->ID, 'wcfm_store_rep_location_options', true );
				if( !empty( $wcfm_store_rep_location ) ){				
					foreach( $wcfm_store_rep_location as $wcfm_store_rep_location_list ){
						foreach( $wcfm_store_rep_location_list as $wcfm_store_rep_location_list_array ){											
							foreach( $wcfm_store_rep_location_list_array as $custom_rep_location_list_array ){						
								if(  $custom_rep_location_list_array['rep_country_value'] == $ip_country ) {
									$repeater_product_id_filter[] = $ps_detail->ID;	
								}
								
							}
						}
					}
				}
			}
		}
	}
	$repeater_product_id_filter = array_unique( $repeater_product_id_filter );
	$meta_query[] = array(
		'key'    => '_premium_product',
		'value'    => '1',
		'operator' => 'IN',
	);
	$tax_query[] = array(
		'taxonomy' => 'product_visibility',
		'field'    => 'name',
		'terms'    => 'featured',
		'operator' => 'IN',
	);	 
	$args_get_premium = array(
		'post_type'			=> 'product',
		'post_status'		=> 'publish',
		'orderby' 			=> 'date',
		'order'  			=> 'DESC',
		'suppress_filters'	=> true,
		'posts_per_page'	=> 10,
		'meta_query'		=> $meta_query,	
		'post__in'			=> $repeater_product_id_filter,		
	);
	$get_premium_product = new WP_Query( $args_get_premium );
	if( $get_premium_product->post_count > 0 ){
		foreach( $get_premium_product->posts as $list_premium_product ){
			$premium_product_id[] = $list_premium_product->ID;			
		}
	}
	$args_get_featured = array(
		'post_type'			=> 'product',
		'post_status'		=> 'publish',
		'orderby' 			=> 'date',
		'order'  			=> 'DESC',
		'suppress_filters'	=> true,
		//'posts_per_page'	=> $get_featured_per_page,		
		'tax_query'			=> $tax_query,
		'post__in'			=> $repeater_product_id_filter,
		'post__not_in'		=> $premium_product_id
	);
	$get_featured_product = new WP_Query( $args_get_featured );
	if( $get_featured_product->post_count > 0 ){		
		foreach( $get_featured_product->posts as $list_featured_product ){
			$featured_product_id[] = $list_featured_product->ID;				
		}
	}
	$find_deffer_product = array_diff( $repeater_product_id_filter, $premium_product_id, $featured_product_id );
	$repeater_product_id_filter =  array_merge( $premium_product_id, $featured_product_id, $find_deffer_product );
	// Removed Product filer serach data 
	//$product_id_filter_final	= array_unique( array_merge( $product_id_filter_arr, $repeater_product_id_filter ) );
	
	$args_final_product = array(
		'post_type'			=> 'product',
		'post_status'		=> 'publish',
		'orderby' 			=> 'date',
		'order'  			=> 'DESC',
		'suppress_filters'	=> true,
		'posts_per_page'	=> -1,	
		'post__in'			=> $repeater_product_id_filter,	
		'orderby' 			=> 'post__in'		
	);
	$get_final_product = new WP_Query( $args_final_product );
	if( $get_final_product->post_count > 0 ){		
		foreach( $get_final_product->posts as $list_final_product ){
			$final_product_id[] = $list_final_product->ID;				
		}
	}
	if( $ip_country == 0 && $ip_state == 0 && $ip_city == 0  ){		
		//return $query;
		$query->set( 'post__in', '' );
		$query->set( 'suppress_filters', true );
	}else{
		$final_product_id = $final_product_id;
		$query->set( 'post__in', $final_product_id );		
		$query->set( 'orderby', 'post__in' );
		$query->set( 'suppress_filters', true );
		
		if( is_product_category() ) {
			if( $query->post_count == 0 ){
				$current_term 	= get_queried_object()->term_id;
				$tax_query		= WC()->query->get_tax_query();
				$tax_query[] = array(
					'taxonomy'			=> 'product_cat',
					'terms'				=> $current_term,
					'include_children'	=> false		// Remove if you need posts from term 7 child terms
				);
				$all_cat_product = array(
					'post_type'			=> 'product',
					'post_status'		=> 'publish',
					'posts_per_page'	=> -1,
					'tax_query'			=> $tax_query				
				);	
				$get_category_product = new WP_Query( $all_cat_product );
				if( $get_category_product->post_count > 0 ){		
					foreach( $get_category_product->posts as $list_final_product ){
						$final_product_id[] = $list_final_product->ID;				
					}
				}
			}
			$query->set( 'post__in', $final_product_id );		
			$query->set( 'orderby', 'post__in' );
			$query->set( 'suppress_filters', true );
		}
	}		
	return $query;
}
function ethio_get_ip_detail( $ip ){
	$ip_response = file_get_contents( 'http://ip-api.com/json/'.$ip );
	$ip_array = json_decode( $ip_response );
	return $ip_array;
}

/**
 * Enqueue scripts and styles.
 */
function ethio_scripts() {
	//wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'carousel', get_template_directory_uri() . '/css/owl.carousel.min.css' );
	
	wp_enqueue_script( 'carousel-js', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), '2.3.4', true);
	wp_enqueue_script( 'custom-script', get_template_directory_uri(). '/js/custom.js', array(), '1.0', true );
	wp_localize_script( 'custom-script', 'myjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );		
}
add_action( 'wp_enqueue_scripts', 'ethio_scripts' );

/**
 * Enqueue scripts and styles.
 */
function custom_scripts() {
	global $WCFM, $WCFMmp, $WCFM_Query;
	
	$WCFM_Query = new WCFM_Query();
	$GLOBALS['WCFM_Query'] = $WCFM_Query;
	$endpoint			= $WCFM_Query->get_current_endpoint();
	$endpoint_title		= $WCFM_Query->get_endpoint_title( $endpoint );
	$check_product_page	= explode( '-', $endpoint_title );
	
	$get_url  = $check_product_page[0];
	$comp_url = 'Product Manager';
	if ( strcasecmp( $get_url, $comp_url ) == 1 || $get_url == 'Product Manager' ) {
		
		$default_geolocation = isset( $WCFMmp->wcfmmp_marketplace_options['default_geolocation'] ) ? $WCFMmp->wcfmmp_marketplace_options['default_geolocation'] : array();
		$default_lat         = isset( $default_geolocation['lat'] ) ? esc_attr( $default_geolocation['lat'] ) : apply_filters( 'wcfmmp_map_default_lat', 30.0599153 );
		$default_lng         = isset( $default_geolocation['lng'] ) ? esc_attr( $default_geolocation['lng'] ) : apply_filters( 'wcfmmp_map_default_lng', 31.2620199 );
		$default_zoom        =  apply_filters( 'wcfmmp_map_default_zoom_level', 15 );
		$api_key = isset( $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] ) ? $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] : '';
		$wcfm_map_lib = isset( $WCFMmp->wcfmmp_marketplace_options['wcfm_map_lib'] ) ? $WCFMmp->wcfmmp_marketplace_options['wcfm_map_lib'] : '';
		
		//if( $api_key ) {
		if( $wcfm_map_lib ) {
			$scheme  = is_ssl() ? 'https' : 'https';
			//wp_enqueue_script( 'wcfm-google-maps', apply_filters( 'wcfm_google_map_api_url', $scheme . '://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places', $api_key ) );
			//wp_localize_script( 'wcfm-google-maps', 'wcfm_maps', array( 'lib' => 'google', 'map_type' => apply_filters( 'wcfm_google_map_type', 'roadmap' ) ) );
			
			wp_enqueue_script( 'wcfm-wcfmmarketplace-jquery-ui', $WCFM->plugin_url . 'includes/libs/jquery-progress/jquery-progress.js' );
			wp_enqueue_script( 'wcfm_marketplace_settings_js',  $WCFM->plugin_url . 'assets/js/settings/wcfm-script-wcfmmarketplace-settings.js', array('jquery'), $WCFM->version, true );
			
			wp_enqueue_script( 'wcfm-leaflet-map-js', $WCFM->plugin_url . 'includes/libs/leaflet/leaflet.js', array('jquery'), $WCFM->version, true );
			wp_enqueue_style( 'wcfm-leaflet-map-style', $WCFM->plugin_url . 'includes/libs/leaflet/leaflet.css', array(), $WCFM->version );
			wp_localize_script( 'wcfm-leaflet-map-js', 'wcfm_maps', array( 'lib' => 'leaflet', 'map_type' => apply_filters( 'wcfm_google_map_type', 'roadmap' ) ) );
			
			wp_enqueue_script( 'wcfm-leaflet-search-js', $WCFM->plugin_url . 'includes/libs/leaflet/leaflet-search.js', array('jquery'), $WCFM->version, true );
			wp_enqueue_style( 'wcfm-leaflet-search-style', $WCFM->plugin_url . 'includes/libs/leaflet/leaflet-search.css', array(), $WCFM->version );
			wp_enqueue_style( 'wcfm_settings_css',  $WCFM->plugin_url . 'assets/css/settings/wcfm-style-settings.css', array(), $WCFM->version );
			
			wp_localize_script( 'wcfm_marketplace_settings_js', 'wcfm_marketplace_setting_map_options', array( 'search_location' => __( 'Insert your address ..', 'wc-multivendor-marketplace' ), 'locate_svg' => $WCFMmp->plugin_url. 'assets/images/locate.svg', 'is_geolocate' => apply_filters( 'wcfmmp_is_allow_store_list_by_user_location', true ), 'default_lat' => $default_lat, 'default_lng' => $default_lng, 'default_zoom' => absint( $default_zoom ), 'store_icon' => '', 'icon_width' => apply_filters( 'wcfmmp_map_icon_width', 40 ), 'icon_height' => apply_filters( 'wcfmmp_map_icon_height', 57 ), 'is_rtl' => is_rtl() ) );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'custom_scripts' );

add_action( 'after_wcfm_products_manage_meta_save', 'fn_custom_product_data_save',10,2 );
function fn_custom_product_data_save($new_product_id, $wcfm_products_manage_form_data) {		
    update_post_meta( $new_product_id, 'frequency_types', $wcfm_products_manage_form_data['frequency_types'] ); 
    update_post_meta( $new_product_id, 'duration_postings', $wcfm_products_manage_form_data['duration_postings'] ); 
}



add_filter( 'woocommerce_get_price_html', 'custom_single_price_html', 100, 2 );
function custom_single_price_html( $price, $product ) {
    $custom_field = get_post_meta( $product->get_id(), 'frequency_types', true );
    if ( is_product() && ! empty($custom_field) )
       if( $custom_field != 'once' ){
			$price =  $price .' <span class="frequency_text"> - (' . $custom_field . ')</span>';
		}else{
			$price =  $price;
		}
    return $price;
}




function featured_clauses( $clauses, $query ) {
	global $wpdb;

	$feature_product_ids = wc_get_featured_product_ids();
	if ( is_array( $feature_product_ids ) && ! empty( $feature_product_ids ) ) {
		if ( empty( $clauses['orderby'] ) ) {
			$clauses['orderby'] = 'FIELD(' . $wpdb->posts . ".ID,'" . implode(
					"','",
					array_map( 'absint', $feature_product_ids )
				) . "') DESC ";
		} else {
			$clauses['orderby'] = 'FIELD(' . $wpdb->posts . ".ID,'" . implode(
					"','",
					array_map( 'absint', $feature_product_ids )
				) . "') DESC, " . $clauses['orderby'];
		}
	}
	return $clauses;
}
add_filter( 'posts_clauses', 'featured_clauses', 10, 2 );


/*Date 01-03-2022*/
add_action( 'wp_ajax_get_state_slocation', 'get_state_slocation' );
add_action( 'wp_ajax_nopriv_get_state_slocation', 'get_state_slocation' );
function get_state_slocation(){ 
	global $WCFM, $WCFMmp, $wpdb;
	
	$user_id = get_current_user_id();
	$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );
	
	$country  = isset( $vendor_data['address']['country'] ) ? $vendor_data['address']['country'] : '';
	$state    = isset( $vendor_data['address']['state'] ) ? $vendor_data['address']['state'] : '';
	$city     = isset( $vendor_data['address']['city'] ) ? $vendor_data['address']['city'] : '';
	
    $country_selected = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $_POST['selected_country'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
	$state_selected = $wpdb->get_results( "SELECT * FROM state where country_id = '".  $country_selected['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );	
	
	if( !empty ( $state_selected ) ){ ?>		
	<?php
		foreach( $state_selected as $save_state ){ 
			if( $save_state['shortcode'] == $state ){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
		?>
			<?php if( isset( $_POST['get_category_id'] ) ){ ?>
				<option value="<?php echo $save_state['shortcode']; ?>" <?php echo $selected; ?>> <?php echo $save_state['name'].'(0)'; ?></option>
			<?php }else{ ?>
				<option value="<?php echo $save_state['shortcode']; ?>" <?php echo $selected; ?>> <?php echo $save_state['name']; ?></option>
			<?php } ?>
	<?php }		
	}
	die();
}

add_action( 'wp_ajax_get_selected_city_slocation', 'get_selected_city_slocation' );
add_action( 'wp_ajax_nopriv_get_selected_city_slocation', 'get_selected_city_slocation' );
function get_selected_city_slocation(){ 
	global $WCFM, $WCFMmp, $wpdb;
	$user_id = get_current_user_id();
	$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );
	
	$country  = isset( $vendor_data['address']['country'] ) ? $vendor_data['address']['country'] : '';
	$state    = isset( $vendor_data['address']['state'] ) ? $vendor_data['address']['state'] : '';
	$city     = isset( $vendor_data['address']['city'] ) ? $vendor_data['address']['city'] : '';
	
	$country_selected = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $_POST['selected_country'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
	$state_selected = $wpdb->get_results( "SELECT * FROM state where shortcode = '".  $_POST['selected_state']  ."' AND country_id = '". $country_selected['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );	
	$city_selected = $wpdb->get_results( "SELECT * FROM city where country_id = '". $country_selected['0']['id'] ."' AND state_id = '". $state_selected['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
	
	if( !empty ( $city_selected ) ){ ?>		
	<?php
		foreach( $city_selected as $save_city ){
			if( $save_city['name'] == $city ){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
	?>
			<option value="<?php echo $save_city['name']; ?>" <?php echo $selected; ?> data-latitude="<?php echo $save_city['latitude']; ?>" data-longitude="<?php echo $save_city['longitude']; ?>" ><?php echo $save_city['name']; ?></option>
	<?php		
		}
	}
	die();
}

add_action( 'wp_ajax_get_city_slocation', 'get_city_slocation' );
add_action( 'wp_ajax_nopriv_get_city_slocation', 'get_city_slocation' );
function get_city_slocation(){ 
	global $WCFM, $WCFMmp, $wpdb;
	$user_id = get_current_user_id();
	$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );
	
	$country  = isset( $vendor_data['address']['country'] ) ? $vendor_data['address']['country'] : '';
	$state    = isset( $vendor_data['address']['state'] ) ? $vendor_data['address']['state'] : '';
	$city     = isset( $vendor_data['address']['city'] ) ? $vendor_data['address']['city'] : '';
	
	$country_selected = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $_POST['selected_country'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
	$state_selected = $wpdb->get_results( "SELECT * FROM state where shortcode = '".  $_POST['selected_state']  ."' AND country_id = '". $country_selected['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );	
	$city_selected = $wpdb->get_results( "SELECT * FROM city where country_id = '". $country_selected['0']['id'] ."' AND state_id = '". $state_selected['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
	
	if( !empty ( $city_selected ) ){ ?>		
	<?php
		foreach( $city_selected as $save_city ){			
	?>
	
			<?php if( isset( $_POST['get_category_id'] ) ){ ?>
				<option value="<?php echo $save_city['name']; ?>" <?php echo $selected; ?> data-latitude="<?php echo $save_city['latitude']; ?>" data-longitude="<?php echo $save_city['longitude']; ?>" ><?php echo $save_city['name'].'(0)'; ?></option>
			<?php }else{ ?>
				<option value="<?php echo $save_city['name']; ?>" <?php echo $selected; ?> data-latitude="<?php echo $save_city['latitude']; ?>" data-longitude="<?php echo $save_city['longitude']; ?>" ><?php echo $save_city['name']; ?></option>
			<?php } ?>	
	<?php		
		}
	}
	die();
}

/*select state for repeater*/
add_action( 'wp_ajax_get_state_replocation', 'get_state_replocation' );
add_action( 'wp_ajax_nopriv_get_state_replocation', 'get_state_replocation' );
function get_state_replocation(){ 
	global $WCFM, $WCFMmp, $wpdb;
	$html = '';	
	$index_value = $_POST['index_value'];
	
	$country_selected = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $_POST['selected_country'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
	$state_selected = $wpdb->get_results( "SELECT * FROM state where country_id = '".  $country_selected['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );	
	
	if( !empty ( $state_selected ) ){	
		foreach( $state_selected as $save_state ){ 
			$html .= '<option value="'.$save_state['shortcode'].'"> '. $save_state['name'].'</option>';			
		}		
	}
	$response_array = array(
		'html' 			=> $html,
		'index_value' 	=> $index_value
	);
	echo json_encode( $response_array );
	die();
}

/*select city for repeater*/
add_action( 'wp_ajax_get_city_replocation', 'get_city_replocation' );
add_action( 'wp_ajax_nopriv_get_city_replocation', 'get_city_replocation' );
function get_city_replocation(){ 
	global $WCFM, $WCFMmp, $wpdb;
	$html = '';	
	$index_value = $_POST['index_value'];
	
	
	$country_selected = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $_POST['selected_country'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
	$state_selected = $wpdb->get_results( "SELECT * FROM state where shortcode = '".  $_POST['selected_state']  ."' AND country_id = '". $country_selected['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );	
	$city_selected = $wpdb->get_results( "SELECT * FROM city where country_id = '". $country_selected['0']['id'] ."' AND state_id = '". $state_selected['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
	
	if( !empty ( $city_selected ) ){	
		foreach( $city_selected as $save_city ){ 
			$html .= '<option value="'.$save_city['name'].'"> '. $save_city['name'].'</option>';			
		}		
	}
	$response_array = array(
		'html' 			=> $html,
		'index_value' 	=> $index_value
	);
	echo json_encode( $response_array );
	die();
}


/* multi array to singal*/
if( !function_exists( 'arrangeArrayPair' ) ) { 
	function arrangeArrayPair( $mainArray, $keyLabel, $valueLabel ) { 
		$newArray = array_combine( 
		array_map( function($value) use($keyLabel){
			return $value[$keyLabel]; 
		}, $mainArray )
		, array_map( function($value) use($valueLabel){ 
			return $value[$valueLabel]; 
		}, $mainArray ) ); 
		return $newArray; 
	}
}

add_filter( 'wcfm_login_redirect','custom_wcfm_login_redirect', 10, 2 );
function custom_wcfm_login_redirect($redirect_to, $user){	
	if ( in_array( 'administrator', (array) $user->roles ) ) {
		return $redirect_to.'/dashboard';
	}
	if ( in_array( 'wcfm_vendor', (array) $user->roles ) ) {
		return $redirect_to.'/dashboard';
	}
}

// disable update option of plugin
function remove_update_notifications( $value ) {
    if ( isset( $value ) && is_object( $value ) ) {
        unset( $value->response[ 'wc-frontend-manager/wc_frontend_manager.php' ] );
        unset( $value->response[ 'wc-frontend-manager-delivery/wc-frontend-manager-delivery.php' ] );
        unset( $value->response[ 'wc-frontend-manager-groups-staffs/wc_frontend_manager_groups_staffs.php' ] );
        unset( $value->response[ 'wc-frontend-manager-product-hub/wc_frontend_manager_product_hub.php' ] );
        unset( $value->response[ 'wc-frontend-manager-ultimate/wc_frontend_manager_ultimate.php' ] );
        unset( $value->response[ 'wc-multivendor-marketplace/wc-multivendor-marketplace.php' ] );
        unset( $value->response[ 'wc-multivendor-membership/wc-multivendor-membership.php' ] );
    }
    return $value;
}
add_filter( 'site_transient_update_plugins', 'remove_update_notifications' );

/*Date 10-03-2022*/
add_action( 'wp_ajax_set_premium_product', 'set_premium_product' );
add_action( 'wp_ajax_nopriv_set_premium_product', 'set_premium_product' );
function set_premium_product(){ 
	global $wpdb;	
	if( $_POST ['product_status'] == '0' ){
		update_post_meta( $_POST['product_id'], '_premium_product', '1' );
		$status = '1';
	}else{
		update_post_meta( $_POST['product_id'], '_premium_product', '0' );
		$status = '0';
	}
	echo $status;
	die();
}

/*Date 11-03-2022 start*/

/*Home Page premium product Slider1 start*/
function premium_large_slider() {
	ob_start();
	global $WCFM, $WCFMmp, $wp, $WCFM_Query, $post, $wpdb, $woocommerce;	
?>
	<div class="ethioco-hero-section">
<?php	
		//Get Premium Product ID start
		$meta_query  = WC()->query->get_meta_query();
		$premium_product_id = array();
		$featured_product_id = array();
		$meta_query[] = array(
			'key'    => '_premium_product',
			'value'    => '1',
			'operator' => 'IN',
		);
		$args_get_premium = array(
			'post_type'			=> 'product',
			'post_status'		=> 'publish',
			'lang' 				=> 'am',
			'orderby' 			=> 'date',
			'order'  			=> 'DESC',
			//'suppress_filters'	=> true,
			'posts_per_page'	=> 10,
			'meta_query'		=> $meta_query,			
		);
		$get_premium_product = new WP_Query( $args_get_premium );
		
		$get_featured_per_page = 10 - $get_premium_product->post_count;
		if( $get_premium_product->post_count > 0 ){
			foreach( $get_premium_product->posts as $list_premium_product ){
				$premium_product_id[] = $list_premium_product->ID;			
			}
		}
		//Get Premium Product ID end
		
		//Get Featured Product ID start
		if( $get_featured_per_page > 0 ){	
			$tax_query   = WC()->query->get_tax_query();
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);	 
			$args_get_featured = array(
				'post_type'			=> 'product',
				'post_status'		=> 'publish',
				'lang' 				=> 'am',
				'orderby' 			=> 'date',
				'order'  			=> 'DESC',
				//'suppress_filters'	=> true,
				'posts_per_page'	=> $get_featured_per_page,		
				'tax_query'			=> $tax_query,
				'post__not_in'		=> $premium_product_id,				
			);
			$get_featured_product = new WP_Query( $args_get_featured );
			if( $get_featured_product->post_count > 0 ){		
				foreach( $get_featured_product->posts as $list_featured_product ){
					$featured_product_id[] = $list_featured_product->ID;				
				}
			}
		}
		//Get Featured Product ID end
		
		//Get Premium Final larege start
		$get_premium_final = array_unique( array_merge( $premium_product_id, $featured_product_id ) );
		$args_get_final1 = array(
			'post_type'			=> 'product',
			'post_status'		=> 'publish',
			'lang' 				=> 'am',
			//'suppress_filters'	=> true,
			'posts_per_page'	=> 10,		
			'post__in'			=> $get_premium_final,
			'orderby' 			=> 'post__in',
				
		);
		$get_premium_final_product = new WP_Query( $args_get_final1 );
		
		
		
		if( $get_premium_final_product->post_count > 0 ){			
			global $product;			
?>
			<div class="ethioco-premium-large-slider">
				<div class="ethioco-premium-slider owl-carousel">
<?php
				foreach( $get_premium_final_product->posts as $list_premium_final_product ){
					$get_attribute_key = $get_meta_array_value = array();
					
					$product_url = get_permalink( $list_premium_final_product->ID );
					$product 	 = wc_get_product(  $list_premium_final_product->ID );
					$image_url	 = wp_get_attachment_image_src( get_post_thumbnail_id( $list_premium_final_product->ID  ), 'single-post-thumbnail' );				
					if( empty( $image_url[0] ) ){
						$image_url = site_url().'/wp-content/themes/ethiopian/images/placeholder.jpg';
					}else{
						$image_url = $image_url[0];
					}
					/*Get Product attributes start*/
					$product = wc_get_product( $list_premium_final_product->ID  );
					$product_attributes = $product->get_attributes(); // Get the product attributes		
	
					foreach ( $product_attributes  as $key => $value ){
						$get_attribute_key[] = $key;						
					}
					$get_attribute_key_data = array_slice( $get_attribute_key, 0, 2 );
					
					foreach( $get_attribute_key_data as $attr_meta ){	

						$get_attr_name = substr( $attr_meta, strpos( $attr_meta, '_' ) + 1 );
						$get_meta_array_value[][$get_attr_name] = wc_get_product_terms( $product->id, $attr_meta , array( 'fields' => 'names' ) );				
					}
						
					
					
					/*Get Product attributes end*/
					
					
	?>
					<div class="ethioco-product-content item">	
						<div class="ethioco-product-content-left">						
							<h2><?php echo $list_premium_final_product->post_title; ?></h2>							
							<?php
							if( !empty ( $get_meta_array_value ) ){
								foreach( $get_meta_array_value as $attr_list ){
									foreach( $attr_list as $key => $value ){ 
							?>	
										<p><?php echo ucfirst( $key )." : "; ?><span class="attr-name"><?php echo implode( ', ', $value ); ?></span></p>
							<?php	}
								}
							}?>							
							<?php if( !empty( $product->get_regular_price() ) && !empty( $product->get_sale_price() ) ){ ?>
								<p><span class="sale-price"><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_sale_price(), 2, '.', '' ); ?></span> <del class="regular-price"><?php echo number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></del></p>
							<?php }else{ ?>
								<p><span class="regular-price"><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></span></p>
							<?php } ?>
							<a class="slider-button" href="<?php echo $product_url; ?>"><?php _e('See Detail'); ?></a>
						</div>						
						<div class="ethioco-product-content-right">
							<img src="<?php  echo $image_url; ?>"  width="100" height="100"  data-id="<?php echo $list_premium_final_product->ID ?>">	
						</div>	
					</div>
		<?php } ?>
				</div>
			</div>
<?php 
		}	
		//Get Premium Final large end	
		//***********Second Slider***************
		//Get Premium Product ID start
		$meta_query2  = WC()->query->get_meta_query();
		$premium_product_id2 = array();
		$featured_product_id2 = array();
		$meta_query2[] = array(
			'key'    => '_premium_product',
			'value'    => '1',
			'operator' => 'IN',
		);
		$args_get_premium2 = array(
			'post_type'			=> 'product',
			'post_status'		=> 'publish',
			'orderby' 			=> 'date',
			'order'  			=> 'DESC',
			//'suppress_filters'	=> true,
			'posts_per_page'	=> 10,
			'meta_query'		=> $meta_query2,	
			'post__not_in'		=> $premium_product_id			
		);
		$get_premium_product2 = new WP_Query( $args_get_premium2 );
		$get_featured_per_page2 = 10 - $get_premium_product2->post_count;
		if( $get_premium_product2->post_count > 0 ){
			foreach( $get_premium_product2->posts as $list_premium_product ){
				$premium_product_id2[] = $list_premium_product->ID;			
			}
		}
		//Get Premium Product ID end
		//Get Featured Product ID start
		if( $get_featured_per_page2 > 0 ){	
			$tax_query   = WC()->query->get_tax_query();
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);	 
			$args_get_featured2 = array(
				'post_type'			=> 'product',
				'post_status'		=> 'publish',
				'orderby' 			=> 'date',
				'order'  			=> 'DESC',
				//'suppress_filters'	=> true,
				'posts_per_page'	=> $get_featured_per_page,		
				'tax_query'			=> $tax_query,
				'post__not_in'		=> array_merge( $premium_product_id2, $get_premium_final )
			);
			$get_featured_product2 = new WP_Query( $args_get_featured2 );
			if( $get_featured_product2->post_count > 0 ){		
				foreach( $get_featured_product2->posts as $list_featured_product ){
					$featured_product_id2[] = $list_featured_product->ID;				
				}
			}
		}
		//Get Featured Product ID end
		
		//Get Premium Final larege start
		$get_premium_final2 = array_unique( array_merge( $premium_product_id2, $featured_product_id2 ) );
		$args_get_final2 = array(
			'post_type'			=> 'product',
			'post_status'		=> 'publish',
			//'suppress_filters'	=> true,
			'posts_per_page'	=> 10,		
			'post__in'			=> $get_premium_final2,
			'orderby' 			=> 'post__in'			
		);
		$get_premium_final_product2 = new WP_Query( $args_get_final2 );
		if( $get_premium_final_product2->post_count > 0 ){		
?>
			<div class="ethioco-premium-small-slider">
				<div class="ethioco-premium-slider owl-carousel">
<?php			
				foreach( $get_premium_final_product2->posts as $list_premium_final_product ){
					$product_url = get_permalink( $list_premium_final_product->ID );
					$product 	 = wc_get_product(  $list_premium_final_product->ID );
					$image_url	 = wp_get_attachment_image_src( get_post_thumbnail_id( $list_premium_final_product->ID  ), 'single-post-thumbnail' );				
					if( empty( $image_url[0] ) ){
						$image_url = site_url().'/wp-content/themes/ethiopian/images/placeholder.jpg';
					}else{
						$image_url = $image_url[0];
					}
?>						
					<div class="ethioco-product-content">	
						<div class="ethioco-product-content-left">					
							<h2><?php echo $list_premium_final_product->post_title; ?></h2>
							<?php /* ?><h2><?php echo do_shortcode( '[wpml-string]' . $list_premium_final_product->post_title . '[/wpml-string]' ); ?></h2><?php */ ?>
							<?php if( !empty( $product->get_regular_price() ) && !empty( $product->get_sale_price() ) ){ ?>
								<p><span class="sale-price"><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_sale_price(), 2, '.', '' ); ?></span> <del class="regular-price"><?php echo number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></del></p>
							<?php }else{ ?>
								<p><span class="regular-price"><?php echo get_woocommerce_currency_symbol().number_format( (float)$product->get_regular_price(), 2, '.', '' ); ?></span></p>
							<?php } ?>
							<a class="slider-button" href="<?php echo $product_url ?>"><?php _e('See Detail'); ?></a>
						</div>
						<div class="ethioco-product-content-right">
							<img src="<?php  echo $image_url; ?>"  width="100" height="100"  data-id="<?php echo $list_premium_final_product->ID ?>">	
						</div>
					</div>						
		<?php } ?>
				</div>
			</div>
<?php
		}	
		//Get Premium Final large end	
?>
	</div>
<?php	
	return ob_get_clean();
}
add_shortcode( 'home_premium_large', 'premium_large_slider' );
/*Home Page premium product Slider2 start*/

/*Date 11-03-2022 end*/

/*Date 15-03-2022 start*/
add_filter( 'woocommerce_catalog_orderby', 'ethio_rename_default_sorting_options' );
function ethio_rename_default_sorting_options( $options ){   
  $options[ 'menu_order' ] 	= 'Default latest'; // rename   
  $options[ 'popularity' ] 	= 'Popularity'; // rename 
  $options[ 'rating' ] 		= 'Average rating'; // rename 
  $options[ 'date' ] 		= 'Latest'; // rename 
  $options[ 'price' ] 		= 'Price: low to high'; // rename   
  $options[ 'price-desc' ] 	= 'Price: high to low'; // rename 
  return $options;   
}
/*Date 15-03-2022 end*/