<?php 
get_header();
?>
<div class="container categoryPage">
  <div class="row">
    <div class="col-md-12">
      <h1><?php echo single_cat_title() .' Games'; ?></h1>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <?php $posts = ks_cat();
      if($posts) { ?>
        <div class="gameBoxes">
          <?php
          foreach($posts as $post) {
            ks_gameBox($post);
          } ?>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- <categories></categories> -->

<front-search :options="options"></front-search>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <!-- Board Game Responsive -->
			<ins class="adsbygoogle"
					style="display:block"
					data-ad-client="ca-pub-0438075078271065"
					data-ad-slot="4591237891"
          data-ad-format="auto"></ins>
    </div>
  </div>
</div>

<?php
get_footer();
?>