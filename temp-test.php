<?php
/* Template Name: Test */

get_header(); ?>


<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { ?>
	<div id="primary" class="content-area <?php martfury_content_columns(); ?>">


	<div class="page-header page-header-catalog">
			<div class="page-breadcrumbs">
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

	
		<main id="main" class="site-main">	

		<div class="toggle-product-cats nav">	
			<ul class="menu">
		<?php

$options = array(
	//	'theme_location' => $location,
		'container'      => false,
		'echo'           => true,
		//'depth'               => 10,
		'title' => 'none',
		'taxonomy' => 'product_cat',
	    'hierarchical'        => true,
		'show_count'          => 1,
	
		'title_li'            => '',
		'walker'         => new My_Walker_Category()
	);

	echo wp_list_categories( $options );

	?>
	</ul>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->
    <?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>
