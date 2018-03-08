<?php
add_action( 'wp_ajax_nopriv_ks_getWalmartPrice', 'ks_getWalmartPrice' );
add_action( 'wp_ajax_ks_getWalmartPrice', 'ks_getWalmartPrice' );
function ks_getWalmartPrice() {
    $upcCode = $_GET['upc'];
    $apiKey = $_GET['apiKey'];
    $lsPublisherId = $_GET['lsPublisherId'];

    if( isset($upcCode) ) {
      $itemID = $upcCode;
      $idType = 'UPC';
    } 

    if( !isset($itemID) ) {
        return;
        die();
    }

  // // Generate the signed URL
  $request_url = 'http://api.walmartlabs.com/v1/items?apiKey='.$apiKey.'&upc='.$upcCode.'&lsPublisherId='.$lsPublisherId.'&format=json';

  // // Get data from signed URL
  $response = get_data($request_url);

  echo $response;

  return;
  die();

}
?>