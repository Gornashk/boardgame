<?php 
get_header();
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1 style="font-size:2.5em;font-weight:400;"><?php echo single_cat_title() .' Games'; ?></h1>
    </div>
  </div>
</div>

<categories></categories>

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