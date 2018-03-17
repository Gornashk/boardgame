<?php get_header(); 
$fields = get_fields();

// crafted_var_dump($fields);
?>

	<div class="container gameInfoContainer">
		<div class="row">
			<div class="col-md-4 gameImage">
			<?php
				if (has_post_thumbnail( $post->ID )): 
  	    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '540x999' ); ?>
				<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(); ?>">
			<?php endif; ?>
			</div>
			<div class="col-md-8 gameInfoTop">
				<div class="gameTitle"><h1><?php the_title(); ?></h1></div>
				<hr/>
				<game-pricing></game-pricing>
				<?php /*
				<game-pricing
				:acfs="<?php echo $fields['acfs']; ?>"
				:upcs="<?php echo $fields['upcs']; ?>"
				:eans="<?php echo $fields['eans']; ?>"
				:elids="<?php echo $fields['elids']; ?>"
				:codes="<?php echo $fields['codes']; ?>"
				gameTitle="<?php echo get_the_title(); ?>">
				</game-pricing>
				*/ ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-3">
						<ul class="gameStats">
							<li class="rating">
								<h6>Rating</h6>
								<p><span></span><?php echo $fields['rating']; ?></p>
							</li>
							<li class="players">
								<h6># Players</h6>
								<p><span><i class="fas fa-users"></i></span><?php echo $fields['min_players']; ?> - <?php echo $fields['max_players']; ?></p>
							</li>
							<li class="playtime">
								<h6>Playtime</h6>
								<p><span><i class="far fa-clock"></i></span><?php echo $fields['min_playtime']; ?> Minutes</p>
							</li>
							<li class="age">
								<h6>Age</h6>
								<p><span><i class="far fa-calendar-alt"></i></span><?php echo $fields['min_age']; ?></p>
							</li>
							<li class="published">
								<h6>Year Published</h6>
								<p><span></span><?php echo $fields['year_published']; ?></p>
							</li>
						</ul>
					</div>
					<div class="col-md-9">
						<h3>Game Description</h3>
						<div class="gameDescription">
							<?php echo $fields['description']; ?>
						</div>
					</div>
				</div>
				
			</div>
			<div class="col-md-4">
				<!-- Board Game Responsive -->
				<ins class="adsbygoogle"
						style="display:block"
						data-ad-client="ca-pub-0438075078271065"
						data-ad-slot="4591237891"
						data-ad-format="auto"></ins>
			</div>
		</div>
	</div>

	<!-- <game-info></game-info> -->


<front-search :options="options"></front-search>

<?php get_footer(); ?>