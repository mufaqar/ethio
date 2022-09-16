<?php
/**
 * The Template for displaying product list radius search form.
 *
 * @package WCfM Markeplace Views Product List Search Form
 *
 * For edit coping this to yourtheme//product-geolocatewcfm
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp, $post, $wp, $wpdb;
//session_start();

// $res = file_get_contents('https://www.iplocate.io/api/lookup');
// $res = json_decode($res);
// $ip_country = $res->country_code; 
// $ip_state = $res->subdivision; 
// $ip_city =  $res->city; 

//$ip_response = file_get_contents( 'http://ip-api.com/json/'.$ip );
$ip_response = file_get_contents( 'http://ip-api.com/json/'.$_SERVER['REMOTE_ADDR']);
$ip_array = json_decode( $ip_response );

if( isset ( $_GET['ethio_country'] )){
	$filter_country = $_GET['ethio_country'];
	$filter_state 	= $_GET['ethio_state'];
	$filter_city 	= $_GET['wcfmmp_city'];
}else{
	$filter_country = '';
}

if( $filter_country ){	
	$ip_country = $filter_country;
	$ip_state 	= $filter_state;
	$ip_city 	= $filter_city;		
}else{
	$ip_array= ethio_get_ip_detail( $_SERVER['REMOTE_ADDR'] );
	if( isset( $_SESSION['sess_ip_country'] ) ){
		if( is_shop() ){
			$ip_country	= '0';
			$ip_state	= '0';
			$ip_city	= '0';
		}else{
			$ip_country = $_SESSION['sess_ip_country'];
			$ip_state	= $_SESSION['sess_ip_state'];
			$ip_city	= $_SESSION['sess_ip_city'];
		}
	}else{
		if( is_shop() ){	
			$ip_country	= '0';
			$ip_state	= '0';
			$ip_city	= '0';
		}else{
			$ip_country = $ip_array->countryCode;
			$ip_state 	= $ip_array->region;	
			$ip_city 	= $ip_array->city;	
		}
	}
}
	if( isset( $_GET['s'] ) && isset( $_GET['allcountry'] )  ){
		$ip_country	= $ip_state	= $ip_city	= '';
	}

if(  class_exists( 'WC_Geolocation' )  ) {
        $user_location = WC_Geolocation::geolocate_ip();
		$search_country = $user_location['country'];
		$search_state   = $user_location['state'];
}else{
   // echo "not exist";
}

if ( '' === get_option( 'permalink_structure' ) ) {
	$form_action = remove_query_arg( array( 'page', 'paged', 'product-page, search' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
} else {
	$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
}
if( isset( $_GET['s'] ) ){
	$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ).'shop/' );
}

$max_radius_to_search = isset( $WCFMmp->wcfmmp_marketplace_options['max_radius_to_search'] ) ? $WCFMmp->wcfmmp_marketplace_options['max_radius_to_search'] : '100';
$radius_unit = isset( $WCFMmp->wcfmmp_marketplace_options['radius_unit'] ) ? $WCFMmp->wcfmmp_marketplace_options['radius_unit'] : 'km';
$radius_addr = isset( $_GET['radius_addr'] ) ? wc_clean( $_GET['radius_addr'] ) : '';
$radius_range = isset( $_GET['radius_range'] ) ? wc_clean( $_GET['radius_range'] ) : (absint(apply_filters( 'wcfmmp_radius_filter_max_distance', $max_radius_to_search ))/apply_filters( 'wcfmmp_radius_filter_start_distance', 10));
$radius_lat = isset( $_GET['radius_lat'] ) ? wc_clean( $_GET['radius_lat'] ) : '';
$radius_lng = isset( $_GET['radius_lng'] ) ? wc_clean( $_GET['radius_lng'] ) : '';
?>

<form role="search" method="get"  id="geo_searchfrom"  class="wcfmmp-product-geolocate-search-form" action="<?php echo esc_url( $form_action ); ?>">
	<div id="country_noajax" class="wcfm_radius_filter_container">
		<?php
		global $wpdb, $woocommerce;
		$countries_obj = new WC_Countries();
		
		$county_selected = $wpdb->get_results( "SELECT name, shortcode FROM country where isactive = 1 ORDER BY name ASC", ARRAY_A  );
		$county_meta = arrangeArrayPair( $county_selected, 'shortcode', 'name' );
		array_unshift( $county_meta, 'All Country' );
		
		$county_id = $wpdb->get_results( "SELECT id FROM country where shortcode = '". $ip_country ."' AND isactive = 1", ARRAY_A  );
		
		$state_selected = $wpdb->get_results( "SELECT name, shortcode FROM state where country_id = '". $county_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
		$state_meta = arrangeArrayPair( $state_selected, 'shortcode', 'name' );
		array_unshift( $state_meta, 'All State' );
		
		$state_id = $wpdb->get_results( "SELECT id FROM state where shortcode = '". $ip_state ."' AND country_id = '". $county_id['0']['id'] ."' AND isactive = 1", ARRAY_A  );
		
		$city_selected = $wpdb->get_results( "SELECT name FROM city where country_id = '". $county_id['0']['id'] ."' AND state_id = '". $state_id['0']['id'] ."' AND isactive = 1 ORDER BY name ASC", ARRAY_A  );
		$city_meta = arrangeArrayPair( $city_selected, 'name', 'name' );
		array_unshift( $city_meta, 'All City' );
		
		$countries				= $countries_obj->__get('countries');
		$default_country		= $countries_obj->get_base_country();
		$default_county_states	= $countries_obj->get_states( $ip_country );
		
		//if( $_GET['ethio_state'] == 0 ){
		if( isset( $_GET['ethio_state'] ) ){
			if( is_array(  $default_county_states ) ){
				array_unshift( $default_county_states, 'All State' );
			}
		}
		
		if($default_country == '' ) {
			$default_country = "US";
		}
		function addressPrefix( &$value,$key ) {
			//All Country
			if( $key != '0' ){			
				$value="$value (0)";
			}
		}
		array_walk( $county_meta,"addressPrefix" );
		array_walk( $state_meta,"addressPrefix" );
		array_walk( $city_meta,"addressPrefix" );
		
		echo '<div id="country_box">';
			woocommerce_form_field('ethio_country', array(
				'type'        	=> 'select',
			   //'type'       	=> 'country',
			   'class'      	=> array( 'chzn-drop' ),			   
			   //'label'      	=> __('Country'),
			   'placeholder'	=> __('All Country'),
			   'options'    	=> $county_meta,
			   'default' 		=> $ip_country,
			   'required'    	=> true
				)
			);
		echo '</div>';
		echo '<div id="state_box">';
			woocommerce_form_field('ethio_state', array(
				'type'       	=> 'select',
				'class'      	=> array( 'chzn-drop' ),
				//'label'      	=> __('State'),
				'placeholder'	=> __('All State'),
				'options'    	=> $state_meta,
				'default' 		=> $ip_state,
				'required'    	=> true
				)
			);
		echo '</div>';
		echo '<div id="city_box">';
			woocommerce_form_field('wcfmmp_city', array(
				'type'			=> 'select',
				'class'			=> array( 'chzn-drop' ),
				//'label'		=> __('City'),
				'placeholder'	=> __('All City'),
				'options'		=> $city_meta,
				'default'		=> $ip_city,
				//'required'   	=> false
				)
			);
		echo '</div>';
		echo '<div id="wcfmmp_state_code">';
			woocommerce_form_field('wcfmmp_state_code', array(
				'type'		=> 'hidden',
				'name'		=> 'wcfm_hd_state_code',
				'value'		=> $ip_state,
				'default'	=> $ip_state,				
				)
			);
		echo '</div>';
		echo '<div id="wcfmmp_city_name_select">';
			woocommerce_form_field('wcfmmp_city_name_select', array(
				'type'		=> 'hidden',
				'name'		=> 'wcfm_hd_city_name',
				'value'		=> $ip_city,
				'default'	=> $ip_city,				
				)
			);
		echo '</div>';
							
		?>
		<div class="country_ajax"></div>	
		<!--div id="wcfm_city_container test" class="wcfm_city_container">
			<p class="form-row chzn-drop">
				<label>City</label>
				<input type="text" id="wcfmmp_city" name="wcfmmp_city" class="wcfmmp-city" placeholder="<?php //esc_attr_e( 'Add City', 'wc-multivendor-marketplace' ); ?>" value="<?php //echo $ip_city ?>" />
			</p>
		</div-->
		<div id="wcfm_button_container" class="wcfm_button_container">	
			<button type="submit" class="button search_button"><?php echo esc_html__( 'Filter', 'woocommerce' ); ?></button>
		</div>
		<?php //echo wc_query_string_form_fields( null, array( 'radius_addr', 'radius_range', 'radius_lat', 'radius_lng', 'paged' ), '', true ); ?>
	</div>
</form>