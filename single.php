<?php get_header(); ?>
<div class="container">
	<div class="row">
		<div class="col-md-9 col-md-push-3 col-lg-push-3">
			<div id="content">
				<div class="blog_title"><h1 class="entry-title"><?php the_title();?></h1></div>
				<div class="blog-date-single"><?php echo the_time( 'F j, Y' ); ?></div><br/>
				<hr/>
				<?php the_content(); ?>
				<br/>
				<hr/>
				<br/>
				
				</div><!-- End Blog Content -->
			</div>
			<div class="col-md-3 col-md-pull-9 col-md-pull-9">
				<div id="sidebar">
					<br/>
					<br/>
					<img class="lazyload avatar-blog" width="50" height="50" src="<?php echo get_avatar_url(get_avatar( $post->post_author, 150 )); ?>"/>

					<div class="blog-author">
						<br/>
						<a href=""><span class="blog-author"><?php the_author_posts_link(); ?></span></a>
						<br/>
						<span class="blog-author">author</span>
						<br/>
						<br/>
						<hr align="center" class="blog-author"/>
					</div>
					
				</div>
			</div>
	</div>
	<game-pricing></game-pricing>
</div><!-- End First Container -->
<?php get_footer(); ?>