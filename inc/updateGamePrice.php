<?php
add_action( 'wp_ajax_nopriv_ks_updateGamePrice', 'ks_updateGamePrice' );
add_action( 'wp_ajax_ks_updateGamePrice', 'ks_updateGamePrice' );
function ks_updateGamePrice() {
  $postID = $_POST['postID'];
  $retailer = $_POST['retailer'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $link = $_POST['link'];

  if($retailer == 'amazon') {
    update_field('field_5a2dad14832c6', $price, $postID);
    update_field('field_5aa01f24c949a', $stock, $postID);
    update_field('field_5aa01f2fc949b', $link, $postID);
  }
  if($retailer == 'walmart' ) {
    update_field('field_5a2dad25832c7', $price, $postID);
    update_field('field_5aa01f5e5df73', $stock, $postID);
    update_field('field_5aa01f685df74', $link, $postID);
  }

  return;
  die();
}
?>