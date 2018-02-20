<?php get_header(); 
$fields = get_fields();
?>
<div class="container gameInfoContainer">
	<div class="row">
		<game-info></game-info>
	</div>
	<div class="row ">
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

<front-search :options="options"></front-search>

<?php get_footer(); ?>