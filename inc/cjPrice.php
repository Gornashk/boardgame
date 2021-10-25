<?php
add_action( 'wp_ajax_nopriv_ks_getCjPrice', 'ks_getCjPrice' );
add_action( 'wp_ajax_ks_getCjPrice', 'ks_getCjPrice' );
function ks_getCjPrice() {
  // echo 'test';
  // return;
  // die();
  $upcCode = $_GET['upc'];
  $websiteId = $_GET['websiteId'];
  $advertiserId = $_GET['advertiserId'];

  if( isset($upcCode) ) {
    $itemID = $upcCode;
    $idType = 'UPC';
  } 

  if( !isset($itemID) ) {
      return;
      die();
  }

  // Generate the signed URL
  $request_url = 'https://product-search.api.cj.com/v2/product-search?website-id='.$websiteId.'&advertiser-ids='.$advertiserId.'&upc='.$upcCode;
  
  // Get data from signed URL
  $response = get_Cj_data($request_url);

  echo $response;

  return;
  die();

}
?>