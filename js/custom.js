/*var options = '',
  state = states['PK']; // country can be: var country = $('country').val();

for (var index in state) {
  if (state.hasOwnProperty(index)) {
    options =
      options + '<option value="' + index + '">' + state[index] + '</option>';
  }
}*/
jQuery( document ).ready( function( $ ) {
	
	/* NEW implement With DB Date : 28-02-2022 */
	/* Vendor Store location start*/
	$( document ).on( 'change', '#country',function() {		
		var selected_country = this.value;
		var selected_state = $( '#state_location' ).find( 'option:selected' ).val();	
		$('#state_location, #city').empty();
		
		$( '#state_location' ).append( '<option value="0">Select State</option>' );
		$( '#city' ).append( '<option value="0">Select City</option>' );
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_state_slocation',
				selected_country: selected_country
			},
			success: function( response ) {
				if( response ) {					
					$( '#state_location' ).append( response );
					//$( '#state_location' ).append( '<option value="0">Select State</option>' );					
					//$( '#city' ).append( '<option value="0">Select City</option>' );					
				}
			}
		});
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_selected_city_slocation',
				selected_country: selected_country,
				selected_state: selected_state,
			},
			success: function( response ) {
				if( response ) {					
					$( '#city' ).append( response );									
				}
			}
		});			
	});
	
	$( document ).on( 'change', '#state_location',function() {
		var selected_country = $( '#country' ).find( 'option:selected' ).val();		
		var selected_state = this.value;
		$( '#city' ).empty();
		$( '#city' ).append( '<option value="0">Select City</option>' );		
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_city_slocation',
				selected_country: selected_country,
				selected_state: selected_state,
			},
			success: function( response ) {
				if( response ) {					
					$( '#city' ).append( response );
				}
			}
		});
	});
	
	$( document ).on( 'change', '#city',function() {
		var selected_country = $( '#country' ).find( 'option:selected' ).val();	
		var selected_state = $( '#state_location' ).find( 'option:selected' ).val();	
		var selected_city = $( '#city' ).find( 'option:selected' ).val();	
		var selected_city_lat = $( '#city' ).find( 'option:selected' ).data('latitude');	
		var selected_city_lon = $( '#city' ).find( 'option:selected' ).data('longitude');	
		
		$('#store_location').val( selected_city + ' ' + selected_state + ' ' + selected_country );
		$('#city_store_lat_ls').val( selected_city_lat );
		$('#city_store_lng_ls').val( selected_city_lon );
		
	});
	/* Vendor Store location end*/	
	
	/* Product Location location start*/
	$( document ).on( 'change', '#country_value',function() {		
		var selected_country = this.value;
		$('#state_value, #city_value').empty();
		
		$( '#state_value' ).append( '<option value="0">Select State</option>' );
		$( '#city_value' ).append( '<option value="0">Select City</option>' );
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_state_slocation',
				selected_country: selected_country
			},
			success: function( response ) {
				if( response ) {					
					$( '#state_value' ).append( response );				
				}
			}
		});		
	});
	$( document ).on( 'change', '#state_value',function() {
		var selected_country = $( '#country_value' ).find( 'option:selected' ).val();		
		var selected_state = this.value;
		
		$( '#city_value' ).empty();
		$( '#city_value' ).append( '<option value="0">Select City</option>' );		
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_city_slocation',
				selected_country: selected_country,
				selected_state: selected_state,
			},
			success: function( response ) {
				if( response ) {					
					$( '#city_value' ).append( response );									
				}
			}
		});
	});	
	
	$( document ).on( 'change', '#city_value',function() {
		var selected_country = $( '#country_value' ).find( 'option:selected' ).val();	
		var selected_state = $( '#state_value' ).find( 'option:selected' ).val();	
		var selected_city = $( '#city_value' ).find( 'option:selected' ).val();	
		var selected_city_lat = $( '#city_value' ).find( 'option:selected' ).data('latitude');	
		var selected_city_lon = $( '#city_value' ).find( 'option:selected' ).data('longitude');	
		
		$('#store_location').val( selected_city + ' ' + selected_state + ' ' + selected_country );
		$('#store_lat').val( selected_city_lat );
		$('#store_lng').val( selected_city_lon );
		
	});
	/* Product Location location end*/
	
	
	/*Product Advertisement Location Click to change first repeater */
	$(document).on( 'click', '#wcfm_products_adv_location',function() {
		var check_product_loc_default_attr 	= jQuery('.check_product_loc_default li')[0];	
		var check_country_default	=  jQuery( check_product_loc_default_attr ).attr( 'data-country' );
		var check_state_default 	=  jQuery( check_product_loc_default_attr ).attr( 'data-state' );
		var check_city_default		=  jQuery( check_product_loc_default_attr ).attr( 'data-city' );
		var check_stateid_default 	=  jQuery( check_product_loc_default_attr ).attr( 'data-state-id' );
		
		var adv_selected_country = $('#wcfm_store_avd_rep_location_rep_country_value_0').find('option:selected').val();		
		var adv_selected_state	 = $('#wcfm_store_avd_rep_location_rep_state_value_0').find('option:selected').val();		
		var adv_selected_city 	 = $('#wcfm_store_avd_rep_location_rep_city_value_0').find('option:selected').val();		
		
		var selected_country = $('#country_value').find('option:selected').val();		
		var selected_state	 = $('#state_value').find('option:selected').val();		
		var selected_city 	 = $('#city_value').find('option:selected').val();
		
		if( check_country_default != '' && check_state_default != '' && check_city_default != '' ){		
			//if( check_country_default != selected_country && check_state_default != selected_state && check_city_default != selected_city ){
			if( check_country_default == adv_selected_country && check_state_default == adv_selected_state && check_city_default == adv_selected_city ){
				
				$("#wcfm_store_avd_rep_location_rep_country_value_0 option[value='"+ selected_country +"']").prop( 'selected', true );
				//For State				
				$( '#wcfm_store_avd_rep_location_rep_state_value_0' ).empty();
				$( '#wcfm_store_avd_rep_location_rep_state_value_0' ).append( '<option value="0">Select State</option>' );	
				jQuery.ajax({
					type: 'post',
					url: myjax.ajaxurl,
					data: {
						action: 'get_state_slocation',
						selected_country: selected_country
					},
					success: function( response ) {						
						if( response ) {
							$( '#wcfm_store_avd_rep_location_rep_state_value_0' ).append( response );
							$( "#wcfm_store_avd_rep_location_rep_state_value_0 option[value='"+ selected_state +"']" ).attr( 'selected', 'selected' );							
						}
					}
				});
				
				//For City
				$( '#wcfm_store_avd_rep_location_rep_city_value_0' ).empty();
				$( '#wcfm_store_avd_rep_location_rep_city_value_0' ).append( '<option value="0">Select City</option>' );
				
				jQuery.ajax({
					type: 'post',
					url: myjax.ajaxurl,
					data: {
						action: 'get_city_slocation',
						selected_country: selected_country,
						selected_state: selected_state,
					},
					success: function( response ) {
						if( response ) {					
							$( '#wcfm_store_avd_rep_location_rep_city_value_0' ).append( response );
							$( '#wcfm_store_avd_rep_location_rep_city_value_0 option[value="'+ selected_city +'"]' ).attr( 'selected', 'selected' );
						}
					}
				});

				var selected_country_default = jQuery( '#country_value' ).find('option:selected').val();
				var selected_state_default   = jQuery( '#state_value' ).find('option:selected').val();
				var selected_city_default    = jQuery( '#city_value' ).find('option:selected').val();
				
				jQuery( '.check_product_loc_default ul li.country' ).attr( 'data-country', selected_country_default );
				jQuery( '.check_product_loc_default ul li.country' ).attr( 'data-state', selected_state_default );
				jQuery( '.check_product_loc_default ul li.country' ).attr( 'data-city', selected_city_default );
				
			}
		}		
	});				
	/*Product Advertisement Location Click to change first repeater */
	
	/* repeater country change value  start */
	$( document ).on( 'change', '#wcfm_store_avd_rep_location .multi_input_block .wcfm_store_country_field',function() {
		var selected_country = this.value;
		var country_id = $( this ).attr('id');				
		var country_index = parseInt(country_id.replace(/[^0-9.]/g, ""));
		
		jQuery( '#wcfm_store_avd_rep_location .multi_input_block #wcfm_store_avd_rep_location_rep_state_value_'+country_index ).empty();
		jQuery( '#wcfm_store_avd_rep_location .multi_input_block #wcfm_store_avd_rep_location_rep_state_value_'+country_index ).append( '<option value="0">Select State</option>' );
		
		jQuery( '#wcfm_store_avd_rep_location .multi_input_block #wcfm_store_avd_rep_location_rep_city_value_'+country_index ).empty();
		jQuery( '#wcfm_store_avd_rep_location .multi_input_block #wcfm_store_avd_rep_location_rep_city_value_'+country_index ).append( '<option value="0">Select City</option>' );
		
		//for rep state
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_state_slocation',
				selected_country: selected_country
			},
			success: function( response ) {						
				if( response ) {
					$( '#wcfm_store_avd_rep_location_rep_state_value_'+country_index ).append( response );	
					$( '#wcfm_store_avd_rep_location_rep_state_value_'+ country_index +' option[value="0"]' ).attr( 'selected', 'selected' );					
				}
			}
		});
	});
	/* repeater country change value end */
	
	/* repeater state change value start */
	$( document ).on( 'change', '#wcfm_store_avd_rep_location .multi_input_block .wcfm_store_state_field',function() {
		var selected_state = this.value;
		var state_id = $( this ).attr('id');				
		var state_index = parseInt(state_id.replace(/[^0-9.]/g, ""));
		var selected_country = $('#wcfm_store_avd_rep_location_rep_country_value_'+state_index ).find('option:selected').val();	
		
		$( '#wcfm_store_avd_rep_location .multi_input_block:last-child .wcfm_store_city_field' ).empty();
		$( '#wcfm_store_avd_rep_location .multi_input_block:last-child .wcfm_store_city_field' ).append( '<option value="0">Select City</option>' );
		
		//for rep city
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_city_slocation',
				selected_country: selected_country,
				selected_state: selected_state,
			},
			success: function( response ) {
				if( response ) {					
					$( '#wcfm_store_avd_rep_location_rep_city_value_'+state_index ).append( response );					
				}
				if( $( '#wcfm_store_avd_rep_location .multi_input_block').length > 1 ){
					$( '#wcfm_store_avd_rep_location .multi_input_block .wcfm_store_city_field' ).map(function(index) {
					var selected_city = $( this ).find( ':selected' ).val();
					$( "#wcfm_store_avd_rep_location .multi_input_block:last-child .wcfm_store_city_field option[value='"+ selected_city +"']").remove();
					});
					$( '.wcfm_store_adv_location_fields .multi_input_block:last-child .wcfm_store_city_field' ).prepend( $( '<option selected></option>').attr( 'value', 0).text( 'Select City' ) );
				}
			}
		});
	});	
	/* repeater state change value end */
	
	/*repeater fields remove duplicat city*/
	$( document ).on( 'click', '#wcfm_store_avd_rep_location .multi_input_block .add_multi_input_block',function() {
		$( '#wcfm_store_avd_rep_location .multi_input_block:last-child .wcfm_store_country_field option[value="0"]' ).attr( 'selected', 'selected' );
		$( '#wcfm_store_avd_rep_location .multi_input_block:last-child .wcfm_store_state_field option[value="0"]' ).attr( 'selected', 'selected' );
		$( '#wcfm_store_avd_rep_location .multi_input_block:last-child .wcfm_store_city_field option[value="0"]' ).attr( 'selected', 'selected' );				
	});
	
	/*For Front end side filter start*/
	$(document).on( 'change', '#ethio_country',function() {		
		var selected_country = this.value;
		jQuery( '#ethio_state' ).empty();
		jQuery( '#ethio_state' ).append( '<option value="0">All State</option>' );
		jQuery( '#wcfmmp_city' ).empty();
		jQuery( '#wcfmmp_city' ).append( '<option value="0">All City</option>' );
		
		var get_category_id = $( '.ethio-country-count-hidden li' ).attr('data-category-id');		
		var thischange = $( this );
		var country_code =  thischange.value;
		
		//for rep state
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_state_slocation',
				selected_country: selected_country,
				get_category_id: get_category_id
			},
			success: function( response ) {						
				if( response ) {
					$( '#ethio_state' ).append( response );	
					$( '#ethio_state option[value="0"]' ).attr( 'selected', 'selected' );
					if ( jQuery( '.ethio-country-count-hidden  > li' ).length > 0 ){
						var groupBy = (x,f)=>x.reduce((a,b)=>((a[f(b)]||=[]).push(b),a),{});
						if( get_category_id ){							
							var mappings = jQuery( '.ethio-country-count-hidden li' ).map(function() {
								if( get_category_id == jQuery(this).attr('data-category-id') && selected_country == jQuery(this).attr('data-country')  ){
									return {
										country: jQuery(this).attr('data-country'),
										state: jQuery(this).attr('data-state'),
										city: jQuery(this).attr('data-city'),
									}
								}
							}).get();							
						}else{
							
								var mappings = jQuery( '.ethio-country-count-hidden li' ).map(function() {
									if( selected_country == jQuery(this).attr('data-country')  ){
										return {
											country: jQuery(this).attr('data-country'),
											state: jQuery(this).attr('data-state'),
											city: jQuery(this).attr('data-city'),
										}
									}
								}).get();
							
						}
						
						var get_state_val =  Object.values(groupBy(mappings, function(x) { return [x.country, x.state] })) //Return Array
						get_state_val.forEach(x=> {
						    var text = jQuery( '#mf-catalog-toolbar #geo_searchfrom #ethio_state option[value="'+ x[0].state +'"]' ).text().replace( '(0)', '' );
							jQuery( '#ethio_state option[value="'+ x[0].state +'"]' ).text( text + " (" + x.length + ")" );
						});	
						
						
						jQuery( '#ethio_state option[value="0"]' ).text( 'All State' );
						
					}			
				}
			}
		});	
		// Fill count in country state and city start		
	});
	$(document).on( 'change', '#ethio_state',function() {
		var selected_state = this.value;
		var selected_country = $( '#ethio_country' ).find('option:selected').val();	
		$( '#wcfmmp_city' ).empty();
		$( '#wcfmmp_city' ).append( '<option value="0">Select City</option>' );
		
		var get_category_id = $( '.ethio-country-count-hidden li' ).attr('data-category-id');		
		
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'get_city_slocation',
				selected_country: selected_country,
				selected_state: selected_state,
				get_category_id: get_category_id
			},
			success: function( response ) {
				if( response ) {					
					$( '#wcfmmp_city' ).append( response );
					if ( jQuery( '.ethio-country-count-hidden  > li' ).length > 0 ){
						var groupBy = (x,f)=>x.reduce((a,b)=>((a[f(b)]||=[]).push(b),a),{});
						
						if( get_category_id ){
							var mappings = jQuery('.ethio-country-count-hidden li').map(function() {
								if( get_category_id == jQuery(this).attr('data-category-id') && selected_state == jQuery(this).attr('data-state')  ){
									return {
										country: jQuery(this).attr('data-country'),
										state: jQuery(this).attr('data-state'),
										city: jQuery(this).attr('data-city'),
									}
								}
							}).get();
						}else{
							var mappings = jQuery( '.ethio-country-count-hidden li' ).map(function() {
								if( selected_state == jQuery(this).attr('data-state')  ){
									return {
										country: jQuery(this).attr('data-country'),
										state: jQuery(this).attr('data-state'),
										city: jQuery(this).attr('data-city'),
									}
								}
							}).get();
						}
						var get_city_val =  Object.values(groupBy(mappings, function(x) { return [x.country, x.state, x.city] })) //Return Array
						get_city_val.forEach(x=> {
							var text = jQuery( '#mf-catalog-toolbar #geo_searchfrom #wcfmmp_city option[value="'+ x[0].city +'"]' ).text().replace( '(0)', '' );
							jQuery( '#wcfmmp_city option[value="'+ x[0].city +'"]' ).text( text + " (" + x.length + ")" );
						});
						jQuery( '#wcfmmp_city option[value="0"]' ).text( 'All City' );
					}
				}
			}
		});
		// Fill count in country state and city start				
	});
	/*For Front end side filter end*/
	
	/*Bydefalt city selected set lat and log attributes start*/
	$(document).on( 'click', '#wcfm_products_cust_location',function() {
		var selected_country = $( '#country_value' ).find('option:selected').val();	
		var selected_state = $( '#state_value' ).find('option:selected').val();
		var vendor_site_url = $( '#vendor_site_url' ).val();
		$.getJSON( vendor_site_url +"/cities.json", function(json) {
			var city_jason = JSON.stringify( json );			
			var city_array = $.parseJSON( city_jason );
			$.each( city_array, function ( key, val ) {				
				if( val.country_code == selected_country && val.state_code == selected_state  ){
					$( "#city_value option[value='"+val.name+"']" ).attr( 'data-latitude', val.latitude );
					$( "#city_value option[value='"+val.name+"']" ).attr( 'data-longitude', val.longitude );
				}
			});
		});	
	});
	/*Bydefalt city selected set lat and log attributes end*/
	
	
	
	/*repeater fields get data start */
	if ( $( '.product_rep_country_hide > ul > li' ).length > 0 ){
		var index_val =  $( '.product_rep_country_hide > ul > li' ).length;
		jQuery( '.product_rep_country_hide > ul > li' ).map(function( i, value){
			var selected_country = $( this ).attr( 'data-country' );
			var selected_state 	 = $( this ).attr( 'data-state' );
			var selected_city 	 = $( this ).attr( 'data-city' );
			
			$( '#wcfm_store_avd_rep_location_rep_country_value_'+i +' option[value="'+ selected_country +'"]' ).attr( 'selected', 'selected' );
			
			$( '#wcfm_store_avd_rep_location_rep_state_value_'+i ).empty();
			$( '#wcfm_store_avd_rep_location_rep_state_value_'+i ).append( '<option value="0">Select State</option>' );
			
			$( '#wcfm_store_avd_rep_location_rep_city_value_'+i ).empty();
			$( '#wcfm_store_avd_rep_location_rep_city_value_'+i ).append( '<option value="0">Select City</option>' );
			
			jQuery.ajax({
				type: 'post',
				url: myjax.ajaxurl,
				data: {
					action: 'get_state_slocation',
					selected_country: selected_country
				},
				success: function( response ) {
					if( response ) {					
						$( '#wcfm_store_avd_rep_location_rep_state_value_'+i ).append( response );
						$( '#wcfm_store_avd_rep_location_rep_state_value_'+i+' option[value="'+selected_state+'"]' ).attr( 'selected', 'selected' );								
					}
				}
			});
			
			jQuery.ajax({
				type: 'post',
				url: myjax.ajaxurl,
				data: {
					action: 'get_city_slocation',
					selected_country: selected_country,
					selected_state: selected_state,
				},
				success: function( response ) {
					if( response ) {					
						$( '#wcfm_store_avd_rep_location_rep_city_value_'+i ).append( response );
						$( '#wcfm_store_avd_rep_location_rep_city_value_'+i+' option[value="'+selected_city+'"]' ).attr( 'selected', 'selected' );
					}
				}
			});						
		});		
	}
	/*Product page subcategory validation start*/
	
	//Parent category to click open all sub category start
	$( document ).on( 'click', '#product_cats_checklist > .product_cats_checklist_item > input',function() {
		if( this.checked ) {
			$( this ).prev( 'span' ).trigger( 'click' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).addClass( 'open' );
			$( this ).removeClass( 'wcfm-subcat-error wcfm_validation_success wcfm_validation_failed' );
			$( this ).parent( 'li.product_cats_checklist_item' ).parent( 'ul.product_taxonomy_checklist' ).children( 'li' ).children( 'input' ).removeClass( 'wcfm_validation_failed' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist.open' ).children( 'li.product_cats_checklist_item' ).children( 'input' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );
        }else{
			$( this ).prev( 'span' ).trigger( 'click' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).find('li.product_cats_checklist_item input').prop( 'checked', false );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).removeClass( 'open' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).children( 'li.product_cats_checklist_item' ).children( 'input' ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );			
		}
	});
	//Parent category to click open all sub category end
	
	//sub category to click child open child category start
	$( document ).on( 'click', '#product_cats_checklist > .product_cats_checklist_item > ul.product_taxonomy_sub_checklist.open > li.product_cats_checklist_item  > input',function() {
		if( this.checked ) {
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).addClass( 'open' );
			$( this ).removeClass( 'wcfm-subcat-error wcfm_validation_success wcfm_validation_failed' );	
			$( this ).parents( 'ul.product_taxonomy_sub_checklist.open' ).children( 'li' ).children( 'input' ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist.open' ).children('li').children('input').addClass( 'wcfm-subcat-error wcfm_validation_success' );
        }else{
			var sub_cat_count = 0;
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).find( 'li.product_cats_checklist_item input' ).prop( 'checked', false );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).removeClass( 'open' );	
			//$( this ).parents( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).parents( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input:checkbox:not(:checked)' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).children('li').children('input').removeClass( 'wcfm-subcat-error wcfm_validation_success wcfm_validation_failed' );
			
			$( this ).parents( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input:checkbox' ).map( function() { 
				if ( $( this ).prop( 'checked' ) == true ) {
					sub_cat_count++;
				}
			});			
			if( sub_cat_count > 0 ){
				$( this ).parents( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input:checkbox' ).removeClass( 'wcfm-subcat-error wcfm_validation_success wcfm_validation_failed' );
			}			
		}		
	});
	//sub category to click child open child category end
	
	// sub child category  click to error class togal start
	//$( document ).on( 'click', '#product_cats_checklist > .product_cats_checklist_item > ul.product_taxonomy_sub_checklist.open > li.product_cats_checklist_item > ul.product_taxonomy_sub_checklist_visible.open  input',function() {		
	$( document ).on( 'click', '#product_cats_checklist > .product_cats_checklist_item > ul.product_taxonomy_sub_checklist.open > li.product_cats_checklist_item > ul.product_taxonomy_sub_checklist.open > li.product_cats_checklist_item > input',function() {
		if( this.checked ) {					
			$( this ).removeClass( 'wcfm-subcat-error wcfm_validation_success wcfm_validation_failed' );
			$( this ).parents( 'ul.product_taxonomy_sub_checklist.open' ).children( 'li' ).children( 'input' ).removeClass( 'wcfm-subcat-error wcfm_validation_success wcfm_validation_failed' );			
        }else{	
			if( $( this ).parent( 'li.product_cats_checklist_item' ).parent( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input:checkbox:checked' ).length == 0 ){
				$( this ).parent( 'li.product_cats_checklist_item' ).parent( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );			
			}
		}				
	});
	// sub child category  click to error class togal end
	
	$( document.body ).on( 'wcfm_products_manage_form_validate', function( event, validating_form ) {
		if( validating_form ) {
			$form = $( validating_form );
			var count = 0;
			var count_brand = 0;
			var product_attr = 0;
			var product_attr_val_count = 0;
			$( '#product_cats_checklist > .product_cats_checklist_item > .product_taxonomy_sub_checklist .product_cats_checklist_item ul' ).each(function( i, value) {
				if ( $( this ).find( 'input:checkbox' ).length > 1 ) {
					$( this ).find( 'input:checkbox:checked' ).each(function() {
						if( $( this ).is( ':checked' ) ) {
							count++;
						}
					});
				}
			});
			//Check for singal category start
			$( '#product_cats_checklist > .product_cats_checklist_item, #product_cats_checklist > .product_cats_checklist_item > ul.product_taxonomy_sub_checklist > .product_cats_checklist_item ' ).each(function( i, value) {
				if( $( this ).children('ul').length == 0 ){
					if ( $(this).find( 'input:checkbox:checked' ).length > 0 ){
						count++;
					}
				}
			});
			//Check for singal category end
			
			//check validation for brand start
			/*$( '.wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item > .product_taxonomy_sub_checklist .product_cats_checklist_item ul' ).each(function( i, value) {
				if ( $( this ).find( 'input:checkbox' ).length > 0 ) {
					$( this ).find( 'input:checkbox:checked' ).each(function( i , value) {
						if( $( this ).is( ':checked' ) ) {
							count_brand++;
						}
					});
				}				
			});			
			$( '.wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item, .wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item > ul.product_taxonomy_sub_checklist > .product_cats_checklist_item' ).each(function( i, value) {
				if( $( this ).children('ul').length == 0 ){
					if ( $(this).find( 'input:checkbox:checked' ).length > 0 ){
						count_brand++;
					}
				}				
			});*/		
			//check validation for brand end
			
			/*Product attribute validation start*/
			//var product_attribute_length_one = jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox:checked' ).length;			
			var product_attribute_length_one = jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks:not(.wcfm_custom_hide) > input.attribute_ele.wcfm-checkbox:checkbox' ).length;
			if( product_attribute_length_one > 1 ){
				var product_attribute_length = jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox:checked' ).length;
				if( product_attribute_length == 0 || product_attribute_length < 2 ) {
					product_attr++;
				}
				if (  product_attribute_length < 2 ) {
					if( $wcfm_is_valid_form ) {
						jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox' ).map( function() { 
							if ( $( this ).prop( 'checked' ) == false ) {
								$( this ).addClass( 'wcfm_validation_failed' );
							}
						});
					}
					$wcfm_is_valid_form = false;
					product_form_is_valid = false;
				}
				
				if( product_attribute_length > 0 ){
					jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks:not(.wcfm_custom_hide) select' ).map( function() { 
						var product_attr_val = jQuery( this ).val();
						if ( product_attr_val.length > 0) {
							product_attr_val_count++;
						}
						
					});				
					if( product_attr_val_count < 2 ){
						if( $wcfm_is_valid_form ) {
							jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox' ).map( function() { 
								if ( $( this ).prop( 'checked' ) == false ) {
									$( this ).addClass( 'wcfm_validation_failed' );					
								}
							});
						}
						$wcfm_is_valid_form = false;
						product_form_is_valid = false;
					}
				}
			}else{
				jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks:not(.wcfm_custom_hide) select' ).map( function() { 
					var product_attr_val = jQuery( this ).val();
					if ( product_attr_val.length > 0) {
						product_attr_val_count++;
					}					
				});
				if( product_attr_val_count == 0 ){
					if( $wcfm_is_valid_form ) {
						jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox' ).map( function() { 
							if ( $( this ).prop( 'checked' ) == false ) {
								$( this ).addClass( 'wcfm_validation_failed' );					
							}
						});
					}
					$wcfm_is_valid_form = false;
					product_form_is_valid = false;
				}				
			}			
			/*Product attribute validation end*/
			
			if ( !count ) {
				if( $wcfm_is_valid_form ) {
					$( '#product_cats_checklist > .product_cats_checklist_item > .product_taxonomy_sub_checklist .product_cats_checklist_item ul li input[type="checkbox"]' ).each(function( i, value){
						$( this ).addClass( 'wcfm-subcat-error' );
					});
					$( '#' + $form.attr('id') + ' .wcfm-message' ).html( '<span class="wcicon-status-cancelled"></span>Please choose a sub-category before submit.' ).addClass( 'wcfm-error' ).slideDown();
				}else{
					$( '#' + $form.attr('id') + ' .wcfm-message' ).append( '<span class="wcicon-status-cancelled"></span>Please choose a sub-category before submit.' );
				}
				$wcfm_is_valid_form = false;
				product_form_is_valid = false;
			}else{
				$( '#product_cats_checklist > .product_cats_checklist_item > .product_taxonomy_sub_checklist .product_cats_checklist_item ul li input[type="checkbox"]' ).each(function( i, value){
					$( this ).removeClass( 'wcfm-subcat-error' );
				});
			}
			
			
			
			/*if ( !count_brand ) {
				if( $wcfm_is_valid_form ) {
					$( '.wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item >  ul li input[type="checkbox"]' ).each(function( i, value){
						$( this ).addClass( 'wcfm-subcat-error' );
					});
					$( '#' + $form.attr('id') + ' .wcfm-message' ).html( '<span class="wcicon-status-cancelled"></span>Please choose a sub-brand before submit.' ).addClass( 'wcfm-error' ).slideDown();
				}else{
					$( '#' + $form.attr('id') + ' .wcfm-message' ).append( '<span class="wcicon-status-cancelled"></span>Please choose a sub-brand before submit.' );
				}
				$wcfm_is_valid_form = false;
				product_form_is_valid = false;
			}else{				
				$( '.wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item >  ul li input[type="checkbox"]' ).each(function( i, value){
					$( this ).removeClass( 'wcfm-subcat-error' );
				});
			}*/
		}
	});
	$( document ).on( 'change', '#product_cats_checklist > .product_cats_checklist_item > .product_taxonomy_sub_checklist .product_cats_checklist_item ul li input',function() {
        if(this.checked) {
			$( this ).parents( '.product_cats_checklist_item' ).children( 'input[type="checkbox"]' ).prop( 'checked', true );
            $( this ).prop( 'checked', true );
        }else{
			$( this ).prop( 'checked', false );
		}
    });
	/*Parent category to click open all sub category end*/
	
	setTimeout(function() {
		if ( $( '#product_cats_checklist > .product_cats_checklist_item > input:checkbox:checked' ).length > 0 ){	
			$( '#product_cats_checklist > .product_cats_checklist_item > input:checkbox:checked, #product_cats_checklist > .product_cats_checklist_item > ul.product_taxonomy_sub_checklist li.product_cats_checklist_item input:checkbox:checked' ).map(function() {
				$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).addClass( 'product_taxonomy_sub_checklist_visible open' );
			});
			/*jQuery( '#product_cats_checklist > .product_cats_checklist_item > ul.product_taxonomy_sub_checklist li.product_cats_checklist_item input:checkbox:checked' ).map(function() {function(index) {
				jQuery( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).addClass( 'product_taxonomy_sub_checklist_visible open' );
			});*/
		}
	}, 1000);
	
	/* vendor-registration set state and city dropdown date 08-03-2022 start*/
	$( document ).on( 'change', '#wcfm_membership_registration_form_expander #country',function() {
		var selected_country =  $( '#wcfm_membership_registration_form_expander #country' ).find( 'option:selected' ).val();		
		var vendor_site_url = $( '#vendor_site_url' ).val();
		
		if( selected_country != '' ) {
			
			$('#wcfm_membership_registration_form_expander #state, #wcfm_membership_registration_form_expander #city').empty();		
			$( '#wcfm_membership_registration_form_expander #state' ).append( '<option value="0">Select State</option>' );
			$( '#wcfm_membership_registration_form_expander #city' ).append( '<option value="0">Select City</option>' );
			
			$.getJSON( vendor_site_url +"/states.json", function(json) {
				var state_jason = JSON.stringify( json );			
				var state_array = $.parseJSON( state_jason );				
				$.each( state_array, function ( key, val ) {
					if( val.country_code == selected_country ){
						$( '#wcfm_membership_registration_form_expander #state' ).append( '<option value="'+ val.state_code +'" data-country-code="'+ selected_country +'" data-state-id="'+ val.id +'" >'+ val.name +'</option>' );
						//$( '#wcfm_membership_registration_form_expander #state' ).append( '<option value="'+ val.name +'" data-country-code="'+ selected_country +'" data-state-id="'+ val.id +'" >'+ val.name +'</option>' );
					}
				});
			});
		}
	});
	
	$( document ).on( 'change', '#wcfm_membership_registration_form_expander #state',function() {		
		var selected_country =  $( '#wcfm_membership_registration_form_expander #country' ).find( 'option:selected' ).val();		
		var selected_state =  $( '#wcfm_membership_registration_form_expander #state' ).find( 'option:selected' ).val();		
		var vendor_site_url = $( '#vendor_site_url' ).val();
		if( selected_country != '' && selected_state != ''  ) {			
			$('#wcfm_membership_registration_form_expander #city').empty();
			$( '#wcfm_membership_registration_form_expander #city' ).append( '<option value="0">Select City</option>' );
			$.getJSON( vendor_site_url +"/cities.json", function(json) {
				var city_jason = JSON.stringify( json );			
				var city_array = $.parseJSON( city_jason );				
				$.each( city_array, function ( key, val ) {
					if( val.country_code == selected_country && val.state_code == selected_state ){
						$( '#wcfm_membership_registration_form_expander #city' ).append( '<option value="'+ val.name +'" data-country-code="'+ selected_country +'" data-state-code="'+ selected_state +'" >'+ val.name +'</option>' );	
					}
				});
			});
		}
	});		
	/* vendor-registration set state and city dropdown date 08-03-2022 end*/
	
	/*Brand checkbox start*/
	//Parent brand to click open all sub brand start
	$( document ).on( 'click', '.wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item > input',function() {
		if( this.checked ) {
			$( this ).prev( 'span' ).trigger( 'click' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).addClass( 'open' );
			//$( '.wcfm_product_manager_cats_checklist_fields > ul#product_brand  > li.product_cats_checklist_item > ul.product_taxonomy_sub_checklist_visible.open > li > input' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).parent( 'li.product_cats_checklist_item' ).parent( 'ul.product_taxonomy_checklist' ).children( 'li' ).children( 'input' ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist.open' ).children( 'li.product_cats_checklist_item' ).children( 'input' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );
        }else{
			$( this ).prev( 'span' ).trigger( 'click' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).find('li.product_cats_checklist_item input').prop( 'checked', false );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).removeClass( 'open' );			
			//$( '.wcfm_product_manager_cats_checklist_fields > ul#product_brand  > li.product_cats_checklist_item > ul.product_taxonomy_sub_checklist_visible.open > li > input'  ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).children( 'li.product_cats_checklist_item' ).children( 'input' ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );
		}
	});
	//Parent brand to click open all sub brand end
	
	setTimeout(function() {
		if ( $( '.wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item input:checkbox:checked' ).length > 0 ){	
			$( '.wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item input:checkbox:checked, .wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item  ul.product_taxonomy_sub_checklist  li.product_cats_checklist_item input:checkbox:checked' ).map(function() {
				$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).addClass( 'product_taxonomy_sub_checklist_visible open' );
			});			
		}
	}, 1000);
	
	//sub brand to click child open start
	$( document ).on( 'click', '.wcfm_product_manager_cats_checklist_fields > ul#product_brand > li.product_cats_checklist_item > ul.product_taxonomy_sub_checklist.open >li.product_cats_checklist_item  > input ', function() {
		if( this.checked ) {			
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).addClass( 'open' );
			$( this ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );			
			$( this ).parents( 'ul.product_taxonomy_sub_checklist.open' ).children( 'li' ).children( 'input' ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist.open' ).children('li').children('input').addClass( 'wcfm-subcat-error wcfm_validation_success' );
		
        }else{
			var sub_brand_count = 0;
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).find('li.product_cats_checklist_item input').prop( 'checked', false );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).removeClass( 'open' );
			//$( this ).parents( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).parents( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input:checkbox:not(:checked)' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).nextAll( 'ul.product_taxonomy_sub_checklist' ).children('li').children('input').removeClass( 'wcfm-subcat-error wcfm_validation_success' );

			$( this ).parents( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input:checkbox' ).map( function() { 
				if ( $( this ).prop( 'checked' ) == true ) {
					sub_brand_count++;
				}
			});			
			if( sub_brand_count > 0 ){
				$( this ).parents( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input:checkbox' ).removeClass( 'wcfm-subcat-error wcfm_validation_success wcfm_validation_failed' );
			}				
		}
	});
	//sub brand to click child open end
	
	//sub child brand to click child start
	$( document ).on( 'click', '.wcfm_product_manager_cats_checklist_fields > ul#product_brand  > li.product_cats_checklist_item > ul.product_taxonomy_sub_checklist.open > li.product_cats_checklist_item > ul.product_taxonomy_sub_checklist.open > li > input', function() {
		if( this.checked ) {			
			$( this ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );
			$( this ).parents( 'ul.product_taxonomy_sub_checklist.open' ).children( 'li' ).children( 'input' ).removeClass( 'wcfm-subcat-error wcfm_validation_success' );
		}else{			
			if( $( this ).parent( 'li.product_cats_checklist_item' ).parent( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input:checkbox:checked' ).length == 0 ){
				$( this ).parent( 'li.product_cats_checklist_item' ).parent( 'ul.product_taxonomy_sub_checklist' ).children( 'li' ).children( 'input' ).addClass( 'wcfm-subcat-error wcfm_validation_success' );			
			}
		}
	});
	//sub child brand to click child end
	
	/*Brand checkbox end*/
	
	/* vendor-profile set state and city dropdown date 08-03-2022 start*/
	//For Billing
	$( document ).on( 'change', '#wcfm_profile_address_expander #bcountry',function() {
		var selected_country =  $( '#wcfm_profile_address_expander #bcountry' ).find( 'option:selected' ).val();		
		var vendor_site_url = $( '#vendor_site_url' ).val();
		
		if( selected_country != '' ) {
			
			$('#wcfm_profile_address_expander #bstate, #wcfm_profile_address_expander #bcity').empty();		
			$( '#wcfm_profile_address_expander #bstate' ).append( '<option value="0">Select State</option>' );
			$( '#wcfm_profile_address_expander #bcity' ).append( '<option value="0">Select City</option>' );
			
			$.getJSON( vendor_site_url +"/states.json", function(json) {
				var state_jason = JSON.stringify( json );			
				var state_array = $.parseJSON( state_jason );				
				$.each( state_array, function ( key, val ) {
					if( val.country_code == selected_country ){
						$( '#wcfm_profile_address_expander #bstate' ).append( '<option value="'+ val.state_code +'" data-country-code="'+ selected_country +'" data-state-id="'+ val.id +'" >'+ val.name +'</option>' );						
					}
				});
			});
		}
	});
	
	$( document ).on( 'change', '#wcfm_profile_address_expander #bstate',function() {		
		var selected_country =  $( '#wcfm_profile_address_expander #bcountry' ).find( 'option:selected' ).val();		
		var selected_state =  $( '#wcfm_profile_address_expander #bstate' ).find( 'option:selected' ).val();
		var vendor_site_url = $( '#vendor_site_url' ).val();
		if( selected_country != '' && selected_state != ''  ) {			
			$('#wcfm_profile_address_expander #bcity').empty();
			$( '#wcfm_profile_address_expander #bcity' ).append( '<option value="0">Select City</option>' );
			$.getJSON( vendor_site_url +"/cities.json", function(json) {
				var city_jason = JSON.stringify( json );			
				var city_array = $.parseJSON( city_jason );				
				$.each( city_array, function ( key, val ) {
					if( val.country_code == selected_country && val.state_code == selected_state ){
						$( '#wcfm_profile_address_expander #bcity' ).append( '<option value="'+ val.name +'" data-country-code="'+ selected_country +'" data-state-code="'+ selected_state +'" >'+ val.name +'</option>' );	
					}
				});
			});
		}
	});
	
	//For Shipping
	$( document ).on( 'change', '#wcfm_profile_address_expander #scountry',function() {
		var selected_country =  $( '#wcfm_profile_address_expander #scountry' ).find( 'option:selected' ).val();		
		var vendor_site_url = $( '#vendor_site_url' ).val();
		
		if( selected_country != '' ) {
			
			$('#wcfm_profile_address_expander #sstate, #wcfm_profile_address_expander #scity').empty();		
			$( '#wcfm_profile_address_expander #sstate' ).append( '<option value="0">Select State</option>' );
			$( '#wcfm_profile_address_expander #scity' ).append( '<option value="0">Select City</option>' );
			
			$.getJSON( vendor_site_url +"/states.json", function(json) {
				var state_jason = JSON.stringify( json );			
				var state_array = $.parseJSON( state_jason );				
				$.each( state_array, function ( key, val ) {
					if( val.country_code == selected_country ){
						$( '#wcfm_profile_address_expander #sstate' ).append( '<option value="'+ val.state_code +'" data-country-code="'+ selected_country +'" data-state-id="'+ val.id +'" >'+ val.name +'</option>' );						
					}
				});
			});
		}
	});
	
	$( document ).on( 'change', '#wcfm_profile_address_expander #sstate',function() {		
		var selected_country =  $( '#wcfm_profile_address_expander #scountry' ).find( 'option:selected' ).val();		
		var selected_state =  $( '#wcfm_profile_address_expander #sstate' ).find( 'option:selected' ).val();
		var vendor_site_url = $( '#vendor_site_url' ).val();
		if( selected_country != '' && selected_state != ''  ) {			
			$('#wcfm_profile_address_expander #scity').empty();
			$( '#wcfm_profile_address_expander #scity' ).append( '<option value="0">Select City</option>' );
			$.getJSON( vendor_site_url +"/cities.json", function(json) {
				var city_jason = JSON.stringify( json );			
				var city_array = $.parseJSON( city_jason );				
				$.each( city_array, function ( key, val ) {
					if( val.country_code == selected_country && val.state_code == selected_state ){
						$( '#wcfm_profile_address_expander #scity' ).append( '<option value="'+ val.name +'" data-country-code="'+ selected_country +'" data-state-code="'+ selected_state +'" >'+ val.name +'</option>' );	
					}
				});
			});
		}
	});
	
	
	$( document ).on( 'click', '#wcfm_profile_address_head',function() {
		var vendor_site_url = $( '#vendor_site_url' ).val();
		
		var selected_bcountry =  $( '#wcfm_profile_address_expander #bcountry' ).find( 'option:selected' ).val();
		var selected_bstate =  $( '#bstate_hide' ).val();
		var selected_bcity =  $( '#bcity_hide' ).val();
		
		var selected_scountry =  $( '#wcfm_profile_address_expander #scountry' ).find( 'option:selected' ).val();
		var selected_sstate =  $( '#sstate_hide' ).val();
		var selected_scity =  $( '#scity_hide' ).val();
		
		//for Billing
		if( selected_bcountry != '' ) {			
			$('#wcfm_profile_address_expander #bstate').empty();		
			$( '#wcfm_profile_address_expander #bstate' ).append( '<option value="0">Select State</option>' );
			$.getJSON( vendor_site_url +"/states.json", function(json) {
				var state_jason = JSON.stringify( json );			
				var state_array = $.parseJSON( state_jason );				
				$.each( state_array, function ( key, val ) {
					if( val.country_code == selected_bcountry ){
						if( selected_bstate == val.state_code  ){
							check_select = 'selected="selected"';
						}else{
							check_select = '';
						}
						$( '#wcfm_profile_address_expander #bstate' ).append( '<option value="'+ val.state_code +'" data-country-code="'+ selected_bcountry +'" data-state-id="'+ val.id +'" '+ check_select +' >'+ val.name +'</option>' );						
					}
				});
			});
		}		
		if( selected_bcountry != '' && selected_bstate != ''  ) {	
			$('#wcfm_profile_address_expander #bcity').empty();
			$( '#wcfm_profile_address_expander #bcity' ).append( '<option value="0">Select City</option>' );		
			$.getJSON( vendor_site_url +"/cities.json", function(json) {
				var city_jason = JSON.stringify( json );			
				var city_array = $.parseJSON( city_jason );				
				$.each( city_array, function ( key, val ) {
					if( val.country_code == selected_bcountry && val.state_code == selected_bstate ){
						if( val.name == selected_bcity  ){
							check_select = 'selected="selected"';
						}else{
							check_select = '';
						}
						$( '#wcfm_profile_address_expander #bcity' ).append( '<option value="'+ val.name +'" data-country-code="'+ selected_bcountry +'" data-state-code="'+ selected_bstate +'" '+ check_select +' >'+ val.name +'</option>' );	
					}
				});
			});
		}
		
		//for Shipping
		if( selected_scountry != '' ) {
			$('#wcfm_profile_address_expander #sstate').empty();		
			$( '#wcfm_profile_address_expander #sstate' ).append( '<option value="0">Select State</option>' );
			$.getJSON( vendor_site_url +"/states.json", function(json) {
				var state_jason = JSON.stringify( json );			
				var state_array = $.parseJSON( state_jason );				
				$.each( state_array, function ( key, val ) {
					if( val.country_code == selected_scountry ){
						if( selected_sstate == val.state_code  ){
							check_select = 'selected="selected"';
						}else{
							check_select = '';
						}
						$( '#wcfm_profile_address_expander #sstate' ).append( '<option value="'+ val.state_code +'" data-country-code="'+ selected_scountry +'" data-state-id="'+ val.id +'" '+ check_select +' >'+ val.name +'</option>' );						
					}
				});
			});
		}		
		if( selected_scountry != '' && selected_sstate != ''  ) {
			$('#wcfm_profile_address_expander #scity').empty();
			$( '#wcfm_profile_address_expander #scity' ).append( '<option value="0">Select City</option>' );
			$.getJSON( vendor_site_url +"/cities.json", function(json) {
				var city2_jason = JSON.stringify( json );			
				var city2_array = $.parseJSON( city2_jason );				
				$.each( city2_array, function ( key, val ) {
					if( val.country_code == selected_scountry && val.state_code == selected_sstate ){
						if( val.name == selected_scity  ){
							check_select = 'selected="selected"';
						}else{
							check_select = '';
						}
						$( '#wcfm_profile_address_expander #scity' ).append( '<option value="'+ val.name +'" data-country-code="'+ selected_scountry +'" data-state-code="'+ selected_sstate +'" '+ check_select +' >'+ val.name +'</option>' );	
					}
				});
			});
		}
	});
	
	/*  vendor-profileset state and city dropdown date 08-03-2022 end*/	
	
	/*Admin add product premium start*/
	$( document ).on( 'click', '.wcfm_product_premium',function(e) {
		e.preventDefault();
		var thisclick = $( this );
		var product_id =  thisclick.data( 'proid' );
		var product_status =  thisclick.data( 'status' );
		jQuery.ajax({
			type: 'post',
			url: myjax.ajaxurl,
			data: {
				action: 'set_premium_product',
				product_id: product_id,
				product_status: product_status
			},
			success: function( response ) {		
				if( product_status == '0' ) {
					thisclick.data('status', '1');
					thisclick.addClass( 'active' );				
				}else{
					thisclick.data('status', '0');
					thisclick.removeClass( 'active' );
				}			
			}
		});
				
	});
	/*Admin add product premium end*/
	
	/*Product Filter start*/
	//$( document ).on( 'click', '.wcfm_product_premium',function(e) {}
	$( document ).on( 'click', '.ethio-filter-city',function() {
		$( '#wcfm_button_container .search_button' ).trigger( 'click' );
		var selected_city =  $( '#wcfmmp_city' ).find( 'option:selected' ).val();
		
	});
	$( document ).on( 'click', '.ethio-filter-state, .ethio-filter-country',function() {
		
		var selected_state =  $( '#ethio_state' ).find( 'option:selected' ).val();
		$( '#wcfmmp_city' ).empty();
		$( 'wcfmmp_city' ).append( '<option value="0">Select City</option>' );	
		$( '#wcfm_button_container .search_button' ).trigger( 'click' );
		
	});
	$( document ).on( 'click', '.ethio-filter-country',function() {
		var selected_country =  $( '#ethio_country' ).find( 'option:selected' ).val();		
		$( '#wcfmmp_city' ).empty();
		$( '#wcfmmp_city' ).append( '<option value="0">Select City</option>' );	
		$( '#ethio_state' ).empty();
		$( '#ethio_state' ).append( '<option value="0">Select State</option>' );	
		
		$( '#wcfm_button_container .search_button' ).trigger( 'click' );		
	});
	
	$( document ).on( 'click', '.ethio-filter-allcountry',function() {
		$( '#wcfmmp_city' ).empty();
		$( '#wcfmmp_city' ).append( '<option value="0">All City</option>' );	
		$( '#ethio_state' ).empty();
		$( '#ethio_state' ).append( '<option value="0">All State</option>' );	
		$( '#ethio_country' ).empty();
		$( '#ethio_country' ).append( '<option value="0">All Country</option>' );	
		
		/*setTimeout(function() {
			$( '#wcfm_button_container .search_button' ).trigger( 'click' );
		}, 100);*/
		
		var vendor_site_url = $( '.vendor_site_url_serach' ).val();
		window.location.href = vendor_site_url+'/?ethio_country=0&ethio_state=0&wcfmmp_city=0';
		
	});
	
	var selected_city_filter =  $( '#wcfmmp_city' ).find( 'option:selected' ).text();
	var selected_state_filter  =  $( '#ethio_state' ).find( 'option:selected' ).text();
	var selected_country_filter  =  $( '#ethio_country' ).find( 'option:selected' ).text();
	
	if( selected_city_filter ){
		$( '.ethio-filter-city' ).text( selected_city_filter );
	}
	if( selected_state_filter ){
		$( '.ethio-filter-state' ).text( selected_state_filter );
	}
	if( selected_country_filter ){
		$( '.ethio-filter-country' ).text( selected_country_filter );
	}
	
	
	/*Product Filter end*/
	
	/*Home page slider start*/
	$( '.ethioco-premium-large-slider .ethioco-premium-slider' ).owlCarousel({
		items: 1,
		loop: true,
		nav: false,
		autoplay: true, //true
		animateOut: 'fadeOut',
		animateIn: 'fadeIn',
	});
	$( '.ethioco-premium-small-slider .ethioco-premium-slider' ).owlCarousel({
		items: 1,
		loop: true,
		nav: false,
		animateOut: 'fadeOut',
		animateIn: 'fadeIn',
	});
	$( '.etho-products .ethioco-premium-slider' ).owlCarousel({
		items: 1,
		loop: true,
		nav: false,
		autoplay: true, //true
		animateOut: 'fadeOut',
		animateIn: 'fadeIn',
	});
	/*Home page slider end*/
	
	/*Home Page information section set in between category start*/
	jQuery( '.anytime' ).insertBefore( '.martfury-catlist-wrap .martfury-container .mf-products-of-category:nth-child(4)' );
	jQuery( '.go_local' ).insertBefore( '.martfury-catlist-wrap .martfury-container .mf-products-of-category:nth-child(3)' );
	jQuery( '.any_product' ).insertBefore( '.martfury-catlist-wrap .martfury-container .mf-products-of-category:nth-child(2)' );
	jQuery( '.free_search' ).insertBefore( '.martfury-catlist-wrap .martfury-container .mf-products-of-category:first-child' );
	/*Home Page information section set in between category end*/
	
	/*mobile search button click start*/
	$( document ).on( 'click', '#menu-custom-search',function() {
		//$( '.header-mobile-v1 .mobile-menu .product-extra-search' ).toggle();
		//$( '.mf-els-modal-mobile' ).toggleClass( 'open' );
		$( '#mf-els-modal-mobile' ).toggleClass( 'open search_btn' );
		$( '.mf-els-modal-mobile' ).find( '.mf-els-item' ).removeClass( 'current' );		 
		$( '#mf-els-modal-mobile .mf-search-mobile-modal' ).toggleClass( 'current' );
	});
	/*mobile search button click end*/
	
	/*mobile filter button click for side bar start*/
	$( document ).on( 'click', '#menu-custom-sidebar-filter',function() {
		$( '#primary-sidebar' ).toggleClass( 'open' );
	});
	/*mobile filter button click for side bar end*/
	
	// Set product filter in one line
	$( '.wcfmmp-product-geolocate-wrapper' ).insertBefore( '.woocommerce-ordering' );	
	if( jQuery ( '.woocommerce-ordering').length == 0 ){
		$( '.wcfmmp-product-geolocate-wrapper' ).insertBefore( '.shop-view' );	
	}
	if( jQuery ( '.woocommerce-ordering').length == 0 && jQuery ( '.shop-view').length == 0){
		$( '.wcfmmp-product-geolocate-wrapper' ).insertAfter( '.all-product' );	
	}
	
	// mobile view open country filter start 
	$( document ).on( 'click', '.ethio-filter-countryfilter', function() {
		$( '.wcfmmp-product-geolocate-wrapper, .products-found.all-product' ).toggleClass( 'open' );		
	});
	// mobile view open country filter end
	
	// Fill count in country state and city start
	count_country_state_city();
	
	/*date 25-03-2022 start*/
	// wcfm admin Categories wise Attributes open and close start
	jQuery( document ).on( 'click', '.admin-cat-list-wrap .admin-product-parent-cat .sub_checklist_toggler', function() {
		var parent_id = jQuery( this ).attr( 'data-parent-cat-id' );
		jQuery( this ).toggleClass( 'fa-arrow-circle-down' );
		jQuery( '.admin-cat-list-wrap .admin-product-sub-cat' ).map( function() { 
			var sub_cat_id = jQuery( this ).attr( 'data-sub-cat-id' );
			if( parent_id == sub_cat_id ){
				jQuery( this ).toggleClass( 'open' );				
			}			
		});
		jQuery( '.admin-cat-list-wrap .admin-product-sub-child-cat' ).removeClass('open');
		jQuery( '.admin-cat-list-wrap .admin-product-sub-cat .sub_checklist_toggler' ).removeClass( 'fa-arrow-circle-down' );
	});	
	jQuery( document ).on( 'click', '.admin-cat-list-wrap .admin-product-sub-cat .sub_checklist_toggler', function() {
		var parent_id = jQuery( this ).attr( 'data-sub-cat-id' );
		jQuery( this ).toggleClass( 'fa-arrow-circle-down' );
		jQuery( '.admin-cat-list-wrap .admin-product-sub-child-cat' ).map( function() { 
			var sub_child_cat_id = jQuery( this ).attr( 'data-sub-cat-id' );
			if( parent_id == sub_child_cat_id ){
				jQuery( this ).toggleClass( 'open' );
			}			
		});
	});	
	// wcfm admin Categories wise Attributes open and close end
	/*date 25-03-2022 end*/
	
	/*date 28-03-2022 start*/
	jQuery( document ).on( 'change', '.admin-product-parent-cat .wcfm_category_attributes_mapping' ,function() {
		var parent_cat_attr = jQuery( this ).val();
		var parent_cat_id = jQuery( this ).parents().attr( 'data-parent-cat-id' );
		jQuery( '.ethio-sub-cat-wrap[data-sub-cat-wrap-id="'+ parent_cat_id +'"] .wcfm_category_attributes_mapping' ).select2().val( parent_cat_attr ).trigger( 'change' );		
	});
	jQuery( document ).on( 'change', '.admin-product-sub-cat .wcfm_category_attributes_mapping' ,function() {
		var sub_cat_attr = jQuery( this ).val();
		var parent_cat_id = jQuery( this ).parents().attr( 'data-sub-cat-id' );
		var sub_cat_id = jQuery( this ).parents().attr( 'data-sub-main-id' );
		jQuery( '.ethio-sub-cat-wrap[data-sub-cat-wrap-id="'+ parent_cat_id +'"] .admin-product-sub-child-cat[data-sub-cat-id="'+ sub_cat_id +'"] .wcfm_category_attributes_mapping' ).select2().val( sub_cat_attr ).trigger( 'change' );		
	});		   
	/*date 28-03-2022 end*/
	
	/*date 30-03-2022 start*/
	// Product page Attribute check box validation color set
	jQuery( document ).on( 'change', '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox',function() {
		var product_attribute_length = jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox:checked' ).length;
		if ( product_attribute_length >= 2 ){
			jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox' ).removeClass( 'wcfm_validation_failed' );
			jQuery( '.ethio-notice-message' ).hide();			
		}else{
			jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox' ).map( function() { 
				if ( jQuery( this ).prop( 'checked' ) == false ) {
					jQuery( this ).addClass( 'wcfm_validation_failed' );
				}
			});			
			jQuery( '.ethio-notice-message' ).show();
		}
	});		
	setTimeout(function () {
		//var product_attribute_length = jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks > input.attribute_ele:checkbox:checked' ).length;
		var product_attribute_length = jQuery( '#wcfm_products_manage_form_attribute_expander #attributes .wcfm_attributes_blocks:not(.wcfm_custom_hide) > input.attribute_ele.wcfm-checkbox:checkbox' ).length;
		if ( product_attribute_length >= 2 ){
			jQuery( '.ethio-notice-message' ).hide();			
		}else{
			jQuery( '.ethio-notice-message' ).show();
		}
		if ( product_attribute_length == 1 ){
			jQuery( '.ethio-notice-message' ).hide();
		}
	}, 100);	
	/*date 30-03-2022 end*/
	
	/*date 31-03-2022 start*/
	var filter_country = $( '#ethio_country' ).find( 'option:selected' ).val();	
	var filter_state = $( '#ethio_state' ).find( 'option:selected' ).val();	
	var filter_city = $( '#wcfmmp_city' ).find( 'option:selected' ).val();	
	
	if( filter_city != 0 ){
		$( '#wcfmmp_city' ).addClass( 'location-active' );
	}else if( filter_state != 0 ){
		$( '#ethio_state' ).addClass( 'location-active' );
	}else if( filter_country != 0 ){
		$( '#ethio_country' ).addClass( 'location-active' );
	}else{
		$( '.products-found.all-product' ).addClass( 'location-active' );		
	}
	/*date 31-03-2022 end*/
});

jQuery( window ).on('load', function() {	
	jQuery( '.wcfmmp-product-geolocate-wrapper' ).insertBefore( '.woocommerce-ordering' );	
	if( jQuery ( '.woocommerce-ordering').length == 0 ){
		jQuery( '.wcfmmp-product-geolocate-wrapper' ).insertBefore( '.shop-view' );	
	}
	if( jQuery ( '.woocommerce-ordering').length == 0 && jQuery ( '.shop-view').length == 0){
		jQuery( '.wcfmmp-product-geolocate-wrapper' ).insertAfter( '.all-product' );	
	}
});
		
// Fill count in country state and city start
function count_country_state_city(){
	if ( jQuery( '.ethio-country-count-hidden  > li' ).length > 0 ){
		var groupBy	 = (x,f)=>x.reduce((a,b)=>((a[f(b)]||=[]).push(b),a),{});		
		var mappings = jQuery('.ethio-country-count-hidden li').map(function() {
			return {
				country: jQuery(this).attr('data-country'),
				state: jQuery(this).attr('data-state'),
				city: jQuery(this).attr('data-city'),
			}
		}).get();
		
		var get_country_val =  Object.values( groupBy(mappings, function(x) { return x.country } ) ); //Return Array			
		var get_state_val	=  Object.values( groupBy(mappings, function(x) { return [x.country, x.state] } ) ); //Return Array
		var get_city_val	=  Object.values( groupBy(mappings, function(x) { return [x.country, x.state, x.city] } ) ); //Return Array
		
		var selected_country = jQuery( '#mf-catalog-toolbar #geo_searchfrom #ethio_country' ).find( 'option:selected' ).val();
		
		get_country_val.forEach(x=> {			
			//var text = jQuery( '#mf-catalog-toolbar #geo_searchfrom #ethio_country option[value="'+ x[0].country +'"]' ).text() ;
			var text = jQuery( '#mf-catalog-toolbar #geo_searchfrom #ethio_country option[value="'+ x[0].country +'"]' ).text().replace( '(0)', '' );
			jQuery( '#ethio_country option[value="'+ x[0].country +'"]' ).text( text + " (" + x.length + ")" );
		});
		
		get_state_val.forEach(x=> {
			//var text = jQuery( '#mf-catalog-toolbar #geo_searchfrom #ethio_state option[value="'+ x[0].state +'"]' ).text();
			if( selected_country == x[0].country ){
				var text = jQuery( '#mf-catalog-toolbar #geo_searchfrom #ethio_state option[value="'+ x[0].state +'"]' ).text().replace( '(0)', '' );
				jQuery( '#ethio_state option[value="'+ x[0].state +'"]' ).text( text + " (" + x.length + ")" );
			}
		});	
		get_city_val.forEach(x=> {
			//var text = jQuery( '#mf-catalog-toolbar #geo_searchfrom #wcfmmp_city option[value="'+ x[0].city +'"]' ).text();
			var text = jQuery( '#mf-catalog-toolbar #geo_searchfrom #wcfmmp_city option[value="'+ x[0].city +'"]' ).text().replace( '(0)', '' );
			jQuery( '#wcfmmp_city option[value="'+ x[0].city +'"]' ).text( text + " (" + x.length + ")" );
		});
		jQuery( '#ethio_country option[value="0"]' ).text( 'All Country' );
		jQuery( '#ethio_state option[value="0"]' ).text( 'All State' );
		jQuery( '#wcfmmp_city option[value="0"]' ).text( 'All City' );
	}
}
// Fill count in country state and city end