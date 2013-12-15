<?php 
/*
Template Name: Pinterest Blog
*/
?>

<?php get_header();
//wp_enqueue_script('freewall');
wp_enqueue_script('shuffle');
?>

<div id="content" class="content-no-margin clearfix">

<?php
	global $wheniwasbad_options;
	$use_carousel = get_post_meta($post->ID, 'carousel_enable' , true);
	$carousel_cats = get_post_meta($post->ID, 'carousel_categories',true);
	$carousel_only_images = get_post_meta($post->ID, 'carousel_only_images',true);
	if ( ! is_array($carousel_cats) ) {
		$use_carousel = false;
	} else {
		$carousel_cats = implode(',',$carousel_cats);
	}
	$carousel_count = get_post_meta($post->ID, 'carousel_count' , true);
	$carousel_height_ratio = get_post_meta($post->ID, 'carousel_height_ratio' , true);
	$carousel_hide_xs = ( get_post_meta($post->ID, 'carousel_hide_xs' , true) ? ' hidden-xs' : '');
	if ( ! is_numeric($carousel_height_ratio) ) {
		$carousel_height_ratio = 2.33;
	}
	if ($use_carousel) : ?>
		
		<div id="myCarousel" class="carousel slide<?php echo $carousel_hide_xs; ?>" data-ride="carousel">
		    <!-- Carousel items -->
		    <div class="carousel-inner">

		    	<?php
				global $post;
				$tmp_post = $post;
				$args = array( 'numberposts' => $carousel_count, 'category' => $carousel_cats );
				if ( $carousel_only_images ) {
					$args['meta_key'] = '_thumbnail_id';
				}
				$myposts = get_posts( $args );
				$post_num = 0;
				foreach( $myposts as $post ) :	setup_postdata($post);
					$post_num++;?>

			    <div class="<?php if($post_num == 1){ echo 'active'; } ?> item" style="overflow:hidden;">
			    	<?php 
			    		if ( has_post_thumbnail($id) ){
							$post_thumbnail_id = get_post_thumbnail_id($id); 
							$featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'wpbs-featured-carousel' ); ?>

				    	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				    		<img src="<?php echo $featured_src[0]; ?>" alt="<?php the_title_attribute(); ?>" style="width:auto; height: 100%; max-width:none;" >
				    	</a>
					<?php }	?>
				   	<div class="carousel-caption jumbotron">

		                <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
		                <p>
		                	<?php
		                		///$excerpt_length = 100; // length of excerpt to show (in characters)
		                		$the_excerpt = get_the_excerpt(); 
		                		if($the_excerpt != ""){
		                			//$the_excerpt = substr( $the_excerpt, 0, $excerpt_length );
		                			echo $the_excerpt;
		                	} ?>
		                
		                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="btn btn-xs btn-primary">Read more &rsaquo;</a></p>
	                </div>
			    </div>

			    <?php endforeach; ?>
				<?php $post = $tmp_post; ?>

			    </div>

			    <!-- Carousel nav -->
			    <a class="carousel-control left" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
			    <a class="carousel-control right" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>

			    <!-- Indicators -->
				<ol class="carousel-indicators">
				<?php for ($c=0;$c<$post_num;$c++){ ?>
			    	<li data-target="#myCarousel" data-slide-to="<?php echo $c; ?>"<?php if ($c==0) echo 'class="active"'; ?>></li>
			    <?php } ?>
				</ol>
		    </div>
		
		</div> <!-- container for carousel -->
		<script>
			jQuery(window).on('load resize', function(){
			    jQuery('#myCarousel .item').each(function() {
			    	jQuery(this).width(jQuery(window).width());
					//jQuery(this).height(jQuery(window).width()/<?php echo $carousel_height_ratio; ?>);
					jQuery(this).height(jQuery(window).height() - jQuery('.navbar').height());
					jQuery(this).find('img').height('100%');
			    });
			});
		</script>
<?php endif; // end carousel ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
	<?php 
		$post_thumbnail_id = get_post_thumbnail_id();
		$featured_src = wp_get_attachment_image_src( $post_thumbnail_id, 'wpbs-featured-home' );
		$jumbotron_contents = get_post_meta($post->ID, 'jumbotron_contents' , true);
		$jumbotron_bg_color = get_post_meta($post->ID, 'jumbotron_bg_color' , true);
		$jumbotron_bg_image = get_post_meta($post->ID, 'jumbotron_bg_image' , true);

		$sidebar_position = get_post_meta($post->ID, 'sidebar_position' , true);
		$sidebar_widget_group = get_post_meta($post->ID, 'sidebar_widgets' , true);
		$hide_empty_sidebar = $wheniwasbad_options['hide_widgets'];
		if ( ! is_active_sidebar($sidebar_widget_group) && $hide_empty_sidebar) {
			$main_class = "col-md-12";
			$sidebar_class = "";
		} else {
			if ( $sidebar_position == 'left' ) {
				$main_class = "col-md-9 col-md-push-3";
				$sidebar_class = "col-md-3 col-md-pull-9";
			} elseif ( $sidebar_position == 'right' ) {
				$main_class = "col-md-9";
				$sidebar_class = "col-md-3";
			}
		}
		
	?>
	
	<?php if ($jumbotron_contents != ''): ?>

		<?php
			if ($jumbotron_bg_image) {
				$jumbotron_style = 'style="background-image: url(\'' . wp_get_attachment_url($jumbotron_bg_image) . '\'); background-repeat: no-repeat; background-position: 0 0; background-size: cover;\'"';
			} elseif ($jumbotron_bg_color) {
				$jumbotron_style = 'style="background-color: ' . $jumbotron_bg_color . '"';
			}
		?>
	
		<div class="jumbotron" <?php echo $jumbotron_style; ?>>
	
			<?php echo $jumbotron_contents;?>

		</div>

	<?php endif; ?>

	<?php 
		$display_page_title = get_post_meta( get_the_id(), 'display_page_title', false );
		$display_page_meta = get_post_meta( get_the_id(), 'display_page_meta', false );
	?>

	<?php if ( $display_page_title ) : ?>

			<div class="container clearfix">

				<header class="page-header">
				
					<h1><?php single_post_title(); ?></h1>
				
					<?php if ( $display_page_meta ) get_template_part('postmeta-horizontal'); ?>
			
				</header> <!-- end page header -->

			</div>

	<?php endif;?>

	<?php /* add the contents of additional pages */
		$additional_pages = get_post_meta( get_the_id(), 'homepage_additional_pages_above', false );
		print_r($additional_pages);
		foreach ($additional_pages as $addon_page_id) {
			$addon_page = get_post($addon_page_id);
			//echo "<div class='container'>\n";
			//echo "<h1>" . $addon_page->post_title . "</h1>\n";
			echo edit_post_link("Edit",'','',$addon_page_id) . "\n";
			echo $addon_page->post_content . "\n";
			//echo "</div>\n";
		}
	?>
	
	<div class="container clearfix">
		
		<div class="row clearfix">
	
			<section id="main" class="<?php echo $main_class; ?> clearfix" role="main">
		
				<?php get_template_part( 'content' ); ?>

				<!-- blog posts -->
				<?php
					$args = 'post_type=post&numberposts=20&posts_per_page=20';
					$pinterest_taxonomy = get_post_meta( get_the_id(), 'pinterest_taxonomy', true );
					$pinterest_args = get_post_meta( get_the_id(), 'pinterest_args', true );
					if ($pinterest_args != '') {
						//$args .= '&'.$pinterest_args;
					}
					if (is_array($pinterest_taxonomy)) {
						$pinterest_taxonomies = implode(',',$pinterest_taxonomy);
						//$args .= '&cat=' . $pinterest_taxonomies;
					}
					$pinterest_query = new WP_Query( $args );
				
					// setup the pinterest columns
					$pinterest_columns_width = get_post_meta($post->ID, 'pinterest_columns_width' , true);
					if ( ! is_numeric($pinterest_columns_width) || $pinterest_columns_width <= 0 ) { // sanity check to prevent div by 0
						$pinterest_columns_width = 150;
					}
					
				?>
				
				<div id="pinterest_list" style="width: 100%; ">

					<?php while ( $pinterest_query->have_posts() ) : $pinterest_query->the_post(); ?>

<?php 
$col_span = 1;
if (get_post_format() == 'video'){// || has_post_thumbnail() ){
	$col_span = 2;
}?>

						<?php if (get_post_format() == 'video') : //display videos across 2 columns ?>

								<div class="pinterest_item panel pinterest_video" style="width: <?php echo $pinterest_columns_width * $col_span; ?>px; height:auto; padding: 0; border: none;" data-groups='["post","video"]'>								

								<?php if ( get_the_title() != '' ) : ?>
									
									<div class="post-excerpt" style="padding: 10px;">

										<header class="entry-header media-heading">
										
											<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => 'Permalink to: ', 'after' => '' ) ); ?>" rel="bookmark"><?php the_title(); ?>
										
											<?php if (get_post_format() == 'link') : ?>
										
												<i class="glyphicon glyphicon-external-link"></i>
										
											<?php endif; ?>
										
											</a></h3>
										
										</header>

									</div>

								<?php endif; ?>

						 		<?php echo get_the_post_thumbnail( $the_post->ID, array($pinterest_columns_width* $col_span,$pinterest_columns_width) ); ?> 
								
								</div>

						<?php else : //not a video, use single column width ?>

							<div class="pinterest_item panel pinterest_<?php echo get_post_format(); ?>" style="width: <?php echo $pinterest_columns_width* $col_span; ?>px; padding: 0; border: none;" data-groups='["post","<?php echo get_post_format(); ?>"]'>
						
								<?php if ( has_post_thumbnail()) : ?>
									
									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
									
										<?php /*$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id($the_post->ID), 'thumbnail'); ?>

										<img src="<?php echo $thumbnail_src[0];?>" class="attachment-thumbnail wp-post-image" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: auto; border-top-right-radius: 4px; border-top-left-radius: 4px;">
										<?php */ ?>
										<?php 
										$has_thumb = ' thumb';
										echo get_the_post_thumbnail($the_post->ID,'thumbnail'); ?>

									</a>

					 			<?php else :
					 				$has_thumb='';
					 			endif; ?>

					 			<div class="pinterest_caption<?php echo $has_thumb; ?>">

									<?php if ( get_the_title() != '' ) : ?>

										<header class="entry-header media-heading">
										
											<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => 'Permalink to: ', 'after' => '' ) ); ?>" rel="bookmark"><?php the_title(); ?>
										
											<?php if (get_post_format() == 'link') : ?>
										
												<i class="glyphicon glyphicon-external-link"></i>
										
											<?php endif; ?>
										
											</a></h3>
										
										</header>
									
									<?php endif; ?>

									<section class="post_content clearfix">
									
										<?php the_excerpt(); ?>
									
									</section> <!-- post-content -->							
								
								</div>

							</div>

						<?php endif; ?>
					
					<?php endwhile; ?>

					<?php wp_reset_query(); ?>
				
				</div><!-- pinterest blog -->

			</section> <!-- end main -->
			
			<?php if ($sidebar_class != ''): ?>
		
				<section class="<?php echo $sidebar_class; ?> clearfix">
		
					<?php get_sidebar($sidebar_widget_group); ?>
		
				</section>
		
			<?php endif; ?>					
			
		</div> <!-- row -->
	</div> <!-- container -->

	<?php /* add the contents of additional pages */
		$additional_pages = get_post_meta( get_the_id(), 'homepage_additional_pages_below', false );
		foreach ($additional_pages as $addon_page_id) {
			$addon_page = get_post($addon_page_id);
			//echo "<div class='container'>\n";
			//echo "<h1>" . $addon_page->post_title . "</h1>\n";
			echo edit_post_link("Edit",'','',$addon_page_id) . "\n";
			echo $addon_page->post_content . "\n";
			//echo "</div>\n";
		}
	?>
				
<?php endwhile; ?>

<?php else : ?>
		
	<?php not_found(); ?>
	
<?php endif; ?>
			    
</div> <!-- end #content -->

<script type='text/javascript'>
	
	jQuery(document).ready(function() {
		var $pinterest_list = jQuery('#pinterest_list'),
			$sizer = <?php echo $pinterest_columns_width; ?>;
		$pinterest_list.shuffle({
			itemSelector: '.pinterest_item',
			sizer: $sizer,
			gutterWidth: 10
		});

		/*var ewall = new freewall("#pinterest_list");

		ewall.reset({
			selector: '.pinterest_item',
			animate: true,
			cellW: <?php echo $pinterest_columns_width; ?>,
			cellH: 60,
			gutterX: 10,
			gutterY: 30,
			fixSize: 1,
			animate: true,
			onResize: function() {
				ewall.fitWidth();
			},
		});
		jQuery(window).trigger("resize");*/
	});

</script>

<?php get_footer(); ?>