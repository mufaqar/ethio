<?php
global $WCFM, $WCFMmp;

$wcfm_store_url    = wcfm_get_option( 'wcfm_store_url', 'store' );
$wcfm_store_name   = apply_filters( 'wcfmmp_store_query_var', get_query_var( $wcfm_store_url ) );
if ( empty( $wcfm_store_name ) ) return;
$seller_info       = get_user_by( 'slug', $wcfm_store_name );
if( !$seller_info ) return;

$store_user        = wcfmmp_get_store( $seller_info->ID );
$store_info        = $store_user->get_shop_info();

$gravatar = $store_user->get_avatar();
$email    = $store_user->get_email();
$phone    = $store_user->get_phone(); 
$address  = $store_user->get_address_string(); 

$store_lat    = isset( $store_info['store_lat'] ) ? esc_attr( $store_info['store_lat'] ) : 0;
$store_lng    = isset( $store_info['store_lng'] ) ? esc_attr( $store_info['store_lng'] ) : 0;

$store_address_info_class = '';
$store_tabs = $store_user->get_store_tabs();


?>

<?php do_action( 'martfury_before_site_content_close' ); ?>
</div><!-- #content -->


    <footer id="colophon" class="site-footer">

	

<nav class="footer-layout footer-layout-1">

	<div class="<?php echo martfury_footer_container_classes(); ?>">
		<div class="footer-content">
		<div class="footer-widgets columns-3" id="footer-widgets">				
						
			<div class="footer-sidebar footer-3">
			<div class="footer-link">
			<h4 class="footer-title"> <?php _e('Contact Information','martfury'); ?></h4>	  
			<ul class="ng-star-inserted">
			<li class="address">
				<span><i aria-hidden="true" class="fa fa-map-marker"></i></span
				><span class="text-capitalize"></span> <?php  echo $address; ?>
			</li>
			<li>
				<span><i aria-hidden="true" class="fa fa-phone"></i></span>
				<?php  echo $phone; ?>
			</li>
			<li>
				<span><i aria-hidden="true" class="fa fa-fax"></i></span>
				<?php  echo $email; ?>
			</li>
			</ul>
		
	  </div>	


			</div>
			<div class="footer-sidebar footer-3">
			<div class="footer-link social">
			<?php if( !empty( $store_info['social'] ) && $store_user->has_social() && wcfm_vendor_has_capability( $store_user->get_id(), 'vendor_social' ) ) { ?>	<h4 class="footer-title"> <?php _e('Follow Us','martfury'); ?></h4>
				<?php } ?>
		<ul>

			<?php if( !empty( $store_info['social'] ) && $store_user->has_social() && wcfm_vendor_has_capability( $store_user->get_id(), 'vendor_social' ) ) { ?>
					<div class="social_area rgt">
						<?php $WCFMmp->template->get_template( 'store/wcfmmp-view-store-social.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) ); ?>
					</div>
					 <div class="spacer"></div>
				<?php } ?>

			
		
		</ul>
	  </div>
				


			</div>
	
</div>
			

		
	</div>

	
</nav>






	
<div class="footer-bottom">
<div class="martfury-container">
			<div class="row footer-row">
				<div class="col-footer-copyright col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<?php martfury_footer_copyright(); ?>
				</div>
			
			</div>
</div>
		</div>
    </footer><!-- #colophon -->
	<?php do_action( 'martfury_after_footer' ) ?>

</div><!-- #page -->

<?php wp_footer(); ?>

<script type="text/javascript">


   
jQuery(document).ready(function($) {

	$('.wcfm_datepicker').datepicker({
				minDate: 0
	});

	$("#ethio_country").change(function(e){	
		var selected_country = this.value;
		jQuery('#wcfmmp_city').val('');


		$.getJSON("<?php echo site_url(); ?>/wp-content/themes/ethiopian/states.json", function(json) {
			//console.log(JSON.stringify(json));
			var states_jason = JSON.stringify(json);
			states_array = $.parseJSON(states_jason);
			//console.log(states_array);

			if(states_array.hasOwnProperty(selected_country)){
				//console.log('Key is exist in Object!');
				$('#state_box').show();
				$.each(states_array, function (key, val) {
					var get_country_array = key;
					var get_states_value = val;
					if( get_country_array == selected_country){
						$('#state_box').show();
						//console.log('test show');
						//console.log(get_states_value);
						$('#ethio_state').empty();
						if( get_states_value != ''){
							$.each(get_states_value, function (key, val) {

								// console.log(key);
								 //console.log(val);
								 if($("#ethio_state").length){
										$('#ethio_state').append(`<option value="${key}">${val}</option>`);
										$('#state_box').show();
									}else{
										$('#state_box').html('<p class="form-row chzn-drop validate-required" id="ethio_state_field" data-priority=""><label for="ethio_state" class="">State&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><select name="ethio_state" id="ethio_state" class="select " data-placeholder="All State"></select></span></p>');
										$('#ethio_state').append(`<option value="${key}">${val}</option>`);
										$('#state_box').show();
									}
	
							});
						}else{
								$('#state_box').hide();
						}	
					}
				});
			}else{
				$('#state_box').hide();
				//console.log('Key is not exist in Object!');
			}
		});       
	});
		   		
});
	  


	</script>



</body>
</html>
