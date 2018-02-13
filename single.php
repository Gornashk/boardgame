<?php get_header(); 
$description = get_field('description');
$minPlayers = get_field('min_players');
$maxPlayers = get_field('max_players');
$minPlaytime = get_field('min_playtime');
$maxPlaytime = get_field('max_playtime');
$minAge = get_field('min_age');
$published = get_field('year_published');
?>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			Image goes here
		</div>
		<div class="col-md-8">
			<div id="content">
				<div class="gameTitle"><h1><?php the_title();?></h1></div>
				<hr/>
				<?php the_content(); ?>
				</div><!-- End Blog Content -->
			</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<game-pricing></game-pricing>
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
</div><!-- End First Container -->
<?php get_footer(); ?>