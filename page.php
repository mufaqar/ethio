<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Martfury
 */

get_header();?>


<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { ?>
	<div id="primary" class="content-area <?php martfury_content_columns(); ?>">
		<div class="page-header page-header-catalog">
			<div class="page-breadcrumbs">
				<div class="row">
				<div class="col-md-10 col-sm-10 col-xs-7">
				<?php  if ( is_front_page() ) { ?>
					<ul class="breadcrumbs" itemscope="" itemtype="https://schema.org/BreadcrumbList">
						<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
							<a class="home" href="<?php echo get_site_url(); ?>" itemprop="item">
								<span itemprop="name"><?php _e('Home'); ?></span>
								<meta itemprop="position" content="1">
							</a>
						</li>
					</ul>
				<?php } ?>
				<?php martfury_get_breadcrumbs(); ?>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-5">
				<?php show_page_counter(); ?>
				</div>
			</div>
			</div>		
		</div>

	
		<main id="main" class="site-main">	
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', 'page' ); ?>
			
				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) : comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
    <?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>
