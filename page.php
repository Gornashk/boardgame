<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<!-- .content-container -->
	<div class="content-container">
		
		<!-- Default H1 for page title -->
		<h1 class="page-title"><?php echo the_title();?></h1>
		
		<?php 
		
		// Print the content from the wordpress default editor
		the_content(); 

		?>
		
		<?php 
		
		// End while have_posts()
		endwhile; else : ?>
		
			<!-- Throw Error if you can't find the post -->
			<div class="alert alert-error">
				<h3>Error!</h3>
				<p>We couldn't find what you were looking for. Perhaps try a different link.</p>
			</div>
			<!-- End error -->
		
		<?php 
		
		// end if have posts()
		endif;
		
		?>
		
		<?php 
		
		// If the user is logged, show a courtesy 'edit this' link
		if (is_user_logged_in()) { 
		
		?>
		
			<div class="clearfix"></div>
		
			<?php

			// print the edit post link
			edit_post_link(); 

			?>
			
		<?php 
		
		// end if user is logged in
		} 
		
		?>

	</div>
	<!-- End .content-container -->

	
<?php get_footer(); ?>