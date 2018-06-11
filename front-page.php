<?php 
get_header();
?>

<div v-cloak>
  <front-search :options="options"></front-search>
</div>

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

<!-- <newly-added></newly-added> -->
<?php $new = ks_newly_added();
if($new) { ?>
  <div class="container">
    <div class="row recentSearch">
      <div class="col-md-12">
        <h4>Recently Searched Board Games</h4>
        <div class="gameBoxes">
          <?php
          foreach($new as $post) {
            ks_gameBox($post);
          } ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php $cats = ks_home_cats();
if($cats) { ?>
  <div class="container">
    <?php foreach($cats as $cat) {
      // crafted_var_dump($cat); ?>
    <div class="row homeCats">
      <div class="col-md-12">
        <div class="flex">
          <h4><?php echo $cat['name'].' Games'; ?></h4>
          <a href="<?php echo $cat['taxPage']; ?>" class="catBtn">View All</a>
        </div>
        <div class="gameBoxes">
          <?php
          foreach($cat['games'] as $post) {
            ks_gameBox($post);
          } ?>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
<?php } ?>

<!-- <home-cats></home-cats> -->

<?php
if( have_posts() ) :
  while( have_posts() ) :
    the_post(); ?>
    
    <div class="frontContent">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <?php the_content(); ?>
          </div>
        </div>
      </div>
    </div>

<?php
  endwhile;
endif;
?>

<?php
get_footer();
?>