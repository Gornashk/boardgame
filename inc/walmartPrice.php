<?php
add_action( 'wp_ajax_nopriv_ks_getWalmartPrice', 'ks_getWalmartPrice' );
add_action( 'wp_ajax_ks_getWalmartPrice', 'ks_getWalmartPrice' );
function ks_getWalmartPrice() {
  $ids = $_GET['ids'];
  $upcCodes = $_GET['upc'];
  $apiKey = $_GET['apiKey'];
  $publisherId = $_GET['publisherId'];

  if( !isset($upcCodes) && !isset($ids) ) {
    return;
    die();
}

  if( isset($ids) ) {
    // If getting product from Walmart ID
    $itemID = $ids;
    $idType = 'ids';

    // Generate the signed URL
    $request_url = 'http://api.walmartlabs.com/v1/items?apiKey='.$apiKey.'&'.$idType.'='.$itemID.'&publisherId='.$publisherId.'&format=json';

    // Get data from signed URL
    $response = get_data($request_url);

    echo $response;

    return;
    die();
  }
  elseif( isset($upcCodes) ) {
    // If using UPC / EAN codes
    // $itemID = $upcCodes;
    // $idType = 'upc';

    foreach($upcCodes as $upc) {
      // var_dump($upc);

      // Generate the signed URL
      $request_url = 'http://api.walmartlabs.com/v1/items?apiKey='.$apiKey.'&upc='.$upc.'&publisherId='.$publisherId.'&format=json';

      // Get data from signed URL
      $response = get_data($request_url);

      // var_dump($response);

      if( strpos($response, '{"errors":') !== false ) {
        // If UPC code returns an error, move on to the next one.
        continue;
      } else {
        // IF UPC code returns a product, spit out that product and end.
        echo $response;

        return;
        die();

      }

    }
  } 

  
return;
die();
  

}
?>