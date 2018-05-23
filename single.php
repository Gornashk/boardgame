<?php get_header(); 
$fields = get_fields();

// crafted_var_dump($fields);
?>

	<div class="container gameInfoContainer" itemscope itemtype="http://schema.org/Product">
		<div class="row">
			<div class="col-sm-4 gameImage">
			<?php
				if (has_post_thumbnail( $post->ID )): 
  	    	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '540x999' ); ?>
					<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(); ?>" itemprop="image">
			<?php endif; ?>
			<social-share 
			url="<?php echo get_the_permalink(); ?>"
			title="<?php echo get_the_title(); ?>"
			media="<?php echo $image[0]; ?>"></social-share>
			</div>
			<div class="col-sm-8 gameInfoTop">
				<div class="gameTitle"><h1><span itemprop="name"><?php the_title(); ?></span></h1></div>
				<hr/>
				<h4>Current Offers for <span><?php the_title(); ?></span></h4>

				<?php if($fields['amazon_price'] || $fields['walmart_price']) { ?>
				<div v-if="showDbPrice == true" class="priceTable" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<?php if($fields['amazon_price']) { ?>
					<div class="priceRow" itemprop="seller" itemscope itemtype="http://schema.org/Organization">
						<div class="rowName">
							<a href="<?php echo $fields['amazon_link']; ?>"><span itemprop="name">Amazon.com</span></a>
						</div>
						<div class="rowPrice">
							<span itemprop="price"><?php echo $fields['amazon_price']; ?></span>
						</div>
						<div class="rowStock">
							<span><?php echo $fields['amazon_stock']; ?></span>
						</div>
						<div class="rowLink">
							<a href="<?php echo $fields['amazon_link']; ?>" target="_blank" itemprop="url" class="storeBtn">Visit Store</a>
						</div>
					</div>
					<?php } ?>
					<?php if($fields['walmart_price']) { ?>
					<div class="priceRow" itemprop="seller" itemscope itemtype="http://schema.org/Organization">
						<div class="rowName">
							<a href="<?php echo $fields['walmart_link']; ?>"><span itemprop="name">Walmart.com</span></a>
						</div>
						<div class="rowPrice">
							<span itemprop="price"><?php echo $fields['walmart_price']; ?></span>
						</div>
						<div class="rowStock">
							<span><?php echo $fields['walmart_stock']; ?></span>
						</div>
						<div class="rowLink">
							<a href="<?php echo $fields['walmart_link']; ?>" target="_blank" itemprop="url" class="storeBtn">Visit Store</a>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php } ?>

				<game-pricing :options="options" v-on:hide-db-price="hideDbPrice"></game-pricing>

				<?php $mpn = $fields['mpns'][0]['mpn']; 
				if($mpn) : ?>
				<p class="model">Model Number: <span itemprop="mpn"><?php echo $mpn; ?></span></p>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-sm-3">
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
					<div class="col-sm-9">
						<h3>Game Description</h3>
						<div class="gameDescription" itemprop="description">
							<?php echo $fields['description']; ?>
						</div>
					</div>
				</div>
				
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-sm-6 col-md-12">
						<?php
						$categories = array(
							'title' => 'Categories',
							'terms' => get_the_terms( $post->ID, 'category' )
						);
						$family = array(
							'title' => 'Groups',
							'terms' => get_the_terms( $post->ID, 'family' )
						);
						$mechanic = array(
							'title' => 'Gameplay Mechanics',
							'terms' => get_the_terms( $post->ID, 'mechanic' )
						);
						$publisher = array(
							'title' => 'Publishers',
							'terms' => get_the_terms( $post->ID, 'publisher' )
						);
						$artists = array(
							'title' => 'Artists',
							'terms' => get_the_terms( $post->ID, 'artists' )
						);
						$designers = array(
							'title' => 'Designers',
							'terms' => get_the_terms( $post->ID, 'designers' )
						);
						$awards = array(
							'title' => 'Awards',
							'terms' => get_the_terms( $post->ID, 'awards' )
						);

						$cats = array(
							$categories,
							$family,
							$mechanic,
							$publisher,
							$artists,
							$designers,
							$awards
						);

						foreach( $cats as $cat ) : 
							$catTerms = $cat['terms'];
							if($catTerms) { ?>
							
								<div class="catBox">
									<h5><?php echo $cat['title']; ?></h5>
									<?php
									
									
										echo '<ul>';
										foreach( $catTerms as $ct ) {
											echo '<li><a href="'. get_term_link($ct->term_id) .'">'. $ct->name .'</a></li>';
										}
										echo '</ul>';								
									?>
								</div>

						<?php
							}
						endforeach;

						// crafted_var_dump($cats);
						?>

					</div>
					<div class="col-sm-6 col-md-12">
						<!-- Board Game Responsive -->

					</div>
				</div>
					<ins class="adsbygoogle"
								style="display:block"
								data-ad-client="ca-pub-0438075078271065"
								data-ad-slot="4591237891"
								data-ad-format="auto"></ins>
			</div>
		</div>
	</div>

	<!-- <game-info></game-info> -->
<div class="container">
	<div class="row homeCats">
		<div class="col-sm-12">
			<related post-id="<?php echo $post->ID; ?>"></related>
		</div>
	</div>
</div>


<front-search :options="options"></front-search>

<?php get_footer(); ?>