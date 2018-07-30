<?php
/*-------------------------------------------------
	BlankPress - Default Taxonomy Archive Template
 --------------------------------------------------*/
get_header(); ?>

<section class="breadcrum clearfix">
	<div class="container">
		<div class="row">
			<div class="span12">			
			
				<div class="span3">
					<div class="parent-widget-title gap">
						Onze mensen
					</div>															
				</div>
				<div class="span9">
					<div class="two-col-bredcrum clearfix br">
						<?php if(function_exists('bcn_display'))
							{
								bcn_display();
							}
						?>						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="vc-content-wrap" class="clearfix">
	<div class="container">

		<div class="span12">	
			<div class="full-white-container clearfix">

				<aside class="span3">
					<div class="lf-sidebar space-top-bottom">
						<div class="lf-sidebar-widget">
							<?php
							$orderby = 'name';
							$show_count = 0; // 1 for yes, 0 for no
							$pad_counts = 0; // 1 for yes, 0 for no
							$hierarchical = 1; // 1 for yes, 0 for no
							$taxonomy = 'categories';
							$title = '';

							$args = array(
							  'orderby' => $orderby,
							  'show_count' => $show_count,
							  'pad_counts' => $pad_counts,
							  'hierarchical' => $hierarchical,
							  'taxonomy' => $taxonomy,
							  'title_li' => $title
							);
						?>
						<ul class="team-cat ortho">
							<?php
								wp_list_categories($args);
							?>
						</ul>
			

						</div>
					</div>

				</aside>

				<section class="span9">
					<div class="br clearfix space content">
						<span class="btn-view">
							<a href="http://vcs.proshoredev.nl/onze-mensen" class="gal1">view</a>								
						</span>
						<div class="page-title">
							<h1><?php single_cat_title(); ?></h1>
						</div>
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

							<div class="row">
								<div class="span4">
									<?php echo(types_render_field( "photo", array( "alt" => "photo" ))); ?>
								</div>
								<div  class="span8">
									<div class="port-des">
										<h2><?php the_title(); ?></h2>
										<div class="designation"><?php echo(types_render_field( "designations", "" )); ?> </div>
										<?php the_content(); ?>
										<span>
											Je kunt me ook volgen via: 
											<a href="<?php echo(types_render_field( "facebook-userid", "" ));?>">Facebook</a>, 
											<a href="<?php echo(types_render_field( "twitter-username", "" ));?>">Twitter</a> or 
											<a href="<?php echo(types_render_field( "linkedin", "" )); ?>">LinkedIn</a>
										</span>
									</div>
								</div>
							</div>
							<div class="span4">
								<div class="gal-block gal-bl-ne">
									<div href="#" class="img-box">
										<span class="zoom">
											<a href="#">Lees verder</a>
										</span>
										
									</div>
									<a href="#" class="gal-title v-name"></a>
									<span class="ani-tit"></span>
								</div>
							</div>

							<?php endwhile; ?>
							<div class="page-nav">
								<div class="alignleft"><?php next_posts_link( '&laquo; Older Entries' ) ?></div>
								<div class="alignright"><?php previous_posts_link( 'Newer Entries &raquo;' ) ?></div>
							</div>
						<?php endif; ?>
					</div>
			</section>
		</div>

	</div>

<?php get_footer();