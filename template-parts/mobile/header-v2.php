<?php 
$els = (array) martfury_get_option( 'navigation_els_mobile' );

if ( empty( $els ) ) {
	return;
}
?>
<div class="header-mobile-v1 header-mobile-v2">
    <div class="container">
        <div class="header-main header-row">
            <div class="header-title">
				<?php do_action( 'martfury_header_mobile_title' ); ?>
            </div>
            <div class="header-extras">
                <ul class="extras-menu">
					<?php
					martfury_extra_compare();
					martfury_extra_wislist();
					martfury_extra_cart();
					//martfury_extra_account();
					echo sprintf( '<li class="extra-menu-item menu-custom-search"><a href="javascript:void(0)" id="menu-custom-search" class="navigation-mobile_search"><i class="extra-icon icon-magnifier"></i></a></li>', esc_html( '' ) );					
					echo sprintf( '<li class="extra-menu-item menu-custom-sidebar-filter"><a href="javascript:void(0)" id="menu-custom-sidebar-filter" class="navigation-mobile-sidebar-filter"><i class="icon-equalizer"></i></a></li>', esc_html( '' ) );					
					?>
                </ul>
            </div>
        </div>
    </div>
</div>




