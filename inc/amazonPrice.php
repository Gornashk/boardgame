<?php
add_action( 'wp_ajax_nopriv_ks_getAmazonPrice', 'ks_getAmazonPrice' );
add_action( 'wp_ajax_ks_getAmazonPrice', 'ks_getAmazonPrice' );
function ks_getAmazonPrice() {
    $asinCode = $_GET['asin'];
    $eanCode = $_GET['ean'];
    $upcCode = $_GET['upc'];
    $elidCode = $_GET['elid'];
    
    // var_dump($_GET);
    // var_dump($asinCode);

    // return;
    // die();


    if( isset($asinCode) ) {
        $itemID = $asinCode;
        $idType = 'ASIN';
    } elseif( isset($eanCode) ) {
        $itemID = $eanCode;
        $idType = 'EAN';
    } elseif( isset($upcCode) ) {
        $itemID = $upcCode;
        $idType = 'UPC';
    } elseif( isset($elidCode) ) {
        $itemID = $elidCode;
        $idType = 'ELID';
    }

    // var_dump($itemID);
    // return;
    // die();

    if( !isset($itemID) ) {
        return;
        die();
    }

  // Your Access Key ID, as taken from the Your Account page
  $access_key_id = "AKIAJJPEFEFEBAODFIAQ";

  // Your Secret Key corresponding to the above ID, as taken from the Your Account page
  $secret_key = "fUlEDj4eN6Hpo+trdeWDCwDFgaW82ZXDXsrz/7CW";

  // The region you are interested in
  $endpoint = "webservices.amazon.com";

  $uri = "/onca/xml";

  $params = array(
      "Service" => "AWSECommerceService",
      "Operation" => "ItemLookup",
      "AWSAccessKeyId" => "AKIAJJPEFEFEBAODFIAQ",
      "AssociateTag" => "awkwar0a-20",
      "ItemId" => $itemID,
      "IdType" => $idType,
      "ResponseGroup" => "Images,ItemAttributes,Offers",
  );

  if( !isset($asinCode) ) {
      $params["SearchIndex"] = "All";
  }

  // Set current timestamp if not set
  if (!isset($params["Timestamp"])) {
      $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
  }

  // Sort the parameters by key
  ksort($params);

  $pairs = array();

  foreach ($params as $key => $value) {
      array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
  }

  // Generate the canonical query
  $canonical_query_string = join("&", $pairs);

  // Generate the string to be signed
  $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

  // Generate the signature required by the Product Advertising API
  $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $secret_key, true));

  // Generate the signed URL
  $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

//   echo $request_url;

  // Get data from signed URL
  $response = get_data($request_url);

  echo $response;

  return;
  die();

}
?>