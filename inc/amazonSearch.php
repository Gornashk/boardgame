<?php
add_action( 'wp_ajax_nopriv_ks_getAmazonSearch', 'ks_getAmazonSearch' );
add_action( 'wp_ajax_ks_getAmazonSearch', 'ks_getAmazonSearch' );
function ks_getAmazonSearch($game) {
	if($game) {
		$gameTitle = preg_replace('/[^\w\d\s]/', '', $game);
	} else {
		$gameTitle = preg_replace('/[^\w\d\s]/', '', $_GET['gameTitle']);
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
      "Operation" => "ItemSearch",
      "AWSAccessKeyId" => "AKIAJJPEFEFEBAODFIAQ",
      "AssociateTag" => "awkwar0a-20",
      "SearchIndex" => "Toys",
      "ResponseGroup" => "Images,ItemAttributes,Offers",
      "BrowseNode" => "165793011",
      "Keywords" => $gameTitle
  );

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

  // Get data from signed URL
  $response = get_data($request_url);

	if(!$game) {
		echo $response;
	} else {
		return $response;
	}

  return;
  die();

}
?>