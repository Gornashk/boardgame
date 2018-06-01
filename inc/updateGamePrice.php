<?php
add_action( 'wp_ajax_nopriv_ks_updateGamePrice', 'ks_updateGamePrice' );
add_action( 'wp_ajax_ks_updateGamePrice', 'ks_updateGamePrice' );
function ks_updateGamePrice() {
  $postID = $_POST['postID'];
  $retailer = $_POST['retailer'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $link = $_POST['link'];
  $rowNum = $_POST['rowNum'];

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

    // Update price repeater
    $pricePlain = str_replace('$', '', $price);
    // Repeater sub-field IDs
    $priceRepeaterArr = array(
      'field_5aad656479234' => $pricePlain,
      'field_5aad7c84aa607' => $retailer
    );
    // Repeater field ID
    update_row( 'field_5aad654a79233', $rowNum, $priceRepeaterArr, $postID );

  return;
  die();
}

add_action( 'wp_ajax_nopriv_ks_clearPriceRepeater', 'ks_clearPriceRepeater' );
add_action( 'wp_ajax_ks_clearPriceRepeater', 'ks_clearPriceRepeater' );
function ks_clearPriceRepeater($postID) {
  if( $_GET['postID'] ) {
    $postID = $_GET['postID'];
  }
  if( have_rows('prices', $postID) ) {
    delete_field('prices', $postID);
  }
  return;
  die();
}
?>