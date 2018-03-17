<?php 
get_header();
?>

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

<newly-added></newly-added>

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