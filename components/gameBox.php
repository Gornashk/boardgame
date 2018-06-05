<?php 
function ks_gameBox($post) { 
  // crafted_var_dump($post);

  // Calc lowest price
  if( isset($post->acf['prices']) && !empty($post->acf['prices']) ) {
    $prices = array();
    foreach( $post->acf['prices'] as $price ) {
      array_push( $prices, floatval($price['price']) );
    }
    $minPrice = min($prices);
  } else {
    $minPrice = null;
  }
?>

  <div class="gameBox">
    <a href="<?php echo $post->permalink; ?>">
      <div class="gameThumb">
        <img src="<?php echo $post->images['medium']['url']; ?>" alt="<?php echo $post->post_title; ?>">
      </div>
      <h5><?php echo $post->post_title; ?></h5>
      <?php if($minPrice != null) { ?>
      <p class="lastPrice">
        Last Seen Lowest Price
        <span><?php echo '$'.$minPrice; ?></span>
      </p>
      <?php } ?>
    </a>
  </div>

<?php 
  return;
} 

?>