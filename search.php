<?php
/*-------------------------------------------------
	BlankPress - Default Page Template
 --------------------------------------------------*/
get_header(); ?>
<section class="breadcrum clearfix">
	<div class="container">
		<div class="row">
			<div class="span12">
				<div class="gap clearfix">	
					<?php if(function_exists('bcn_display'))
						{
							bcn_display();
						}
					?>
				</div>	
			</div>
		</div>
	</div>
</section>
		
<section id="vc-content-wrap" class="clearfix">
	<div class="container">

		<div class="span12" role="main">
			<div class="white-container clearfix">
				
				<div class="page-title">	
					<h1>Zoekresultaten</h1>
					<span class="tagline">
						<h3>Resultaten voor : <?php echo get_search_query(); ?></h3>
					</span>
				</div>
				
				<div class="vcs-row">					
				
					<?php while (have_posts()) : the_post();?>
					<div class="span6 search-box">
						<div class="row">
							<div class="span3 alpha">
								<?php if (has_post_thumbnail()) { ?>
									<?php the_post_thumbnail( array(120,90), array('class'=>' alignleft') ); ?>	
								<?php } else { ?>
									<img src="<?php bloginfo('template_url'); ?>/images/preview.png" alt="Preview">
								<?php } ?>
							</div>
							<div class="span9">
								<header>
									<h4>
										<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
										<?php the_title(); ?></a>
									</h4>
								</header>
								<p><?php echo substr(strip_tags($post->post_content), 0, 150) . "..."; ?></p>
								<a href="<?php the_permalink(); ?>" class="search-link">Lees verder</a>
							</div>
						</div>
					</div>
					<?php endwhile ?>	
				</div>		
				
			</div>
			
		</div><!-- end content -->		
	
<?php get_footer();