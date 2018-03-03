<?php
add_action( 'wp_ajax_nopriv_ks_getUpcIds', 'ks_getUpcIds' );
add_action( 'wp_ajax_ks_getUpcIds', 'ks_getUpcIds' );
function ks_getUpcIds() {
  $title = $_GET['gameTitle'];
  //var_dump($title);
  // $title = rawurlencode($title);
  // $user_key = 'only_for_dev_or_pro';
  $endpoint = 'https://api.upcitemdb.com/prod/trial/search';

  $ch = curl_init();
  // if your client is old and doesn't have our CA certs
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    // "user_key: $user_key",
    // "key_type: 3scale"
  ));
  // HTTP GET
  curl_setopt($ch, CURLOPT_POST, 0);
  curl_setopt($ch, CURLOPT_URL, $endpoint.'?s='. $title .'&match_mode=0&type=product');
  $response = curl_exec($ch);
  //$response = json_encode($response);
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($httpcode != 200)
    echo "error status $httpcode...";
  else 
    //return $response;
    echo $response;
  /* if you need to run more queries, do them in the same connection.
  * use rawurlencode() instead of URLEncode(), if you set search string
  * as url query param
  */
  sleep(2);
  // proceed with other queries
  curl_close($ch);

  die();
}
  
add_action( 'wp_ajax_nopriv_ks_saveGameIds', 'ks_saveGameIds' );
add_action( 'wp_ajax_ks_saveGameIds', 'ks_saveGameIds' );
function ks_saveGameIds() {
  $postID = $_POST['postID'];
  $amzTitle = $_POST['amzTitle'];
  $amzAsin = $_POST['amzAsin'];
  $amzEan = $_POST['amzEan'];
  $amzMpn = $_POST['amzMpn'];
  $amzUpc = $_POST['amzUpc'];
  $amzElid = $_POST['amzElid'];
  $upcs = $_POST['upc'];
  $asins = $_POST['asin'];
  $eans = $_POST['ean'];
  $elids = $_POST['elid'];
  $mpns = $_POST['mpn'];

  
  if($amzAsin) {
    $amzAsinArr = array(
      'field_5a63bffd794ae' => $amzAsin,
      'field_5a93402a9cbc6' => $amzTitle
    );
    add_row( 'field_5a2dac2e832c4', $amzAsinArr, $postID );
  }
  if($amzEan) {
    $amzEanArr = array(
      'field_5a63c00a794af' => $amzEan,
      'field_5a9340379cbc7' => $amzTitle
    );
    add_row( 'field_5a41b153e46cf', $amzEanArr, $postID );
  }
  if($amzMpn) {
    $amzMpnArr = array(
      'field_5a63bfdb794ac' => $amzMpn,
      'field_5a933fe296fa7' => $amzTitle
    );
    add_row( 'field_5a2dac1c832c2', $amzMpnArr, $postID );
  }
  if($amzUpc) {
    $amzUpcArr = array(
      'field_5a63bff0794ad' => $amzUpc,
      'field_5a9340209cbc5' => $amzTitle
    );
    add_row( 'field_5a2dac28832c3', $amzUpcArr, $postID );
  }
  if($amzElid) {
    $amzElidArr = array(
      'field_5a63c016794b0' => $amzElid,
      'field_5a93403f9cbc8' => $amzTitle
    );
    add_row( 'field_5a63b843892e3', $amzElidArr, $postID );
  }

  if($upcs) {
    foreach ($upcs as $upc) {
      $upcArr = array(
        'field_5a63bff0794ad' => $upc['upc'],
        'field_5a9340209cbc5' => $upc['title']
      );
      add_row( 'field_5a2dac28832c3', $upcArr, $postID );
    }
  }
  if($asins) {
    foreach ($asins as $asin) {
      $asinArr = array(
        'field_5a63bffd794ae' => $asin['asin'],
        'field_5a93402a9cbc6' => $asin['title']
      );
      add_row( 'field_5a2dac2e832c4', $asinArr, $postID );
    }
  }
  if($eans) {
    foreach ($eans as $ean) {
      $eanArr = array(
        'field_5a63c00a794af' => $ean['ean'],
        'field_5a9340379cbc7' => $ean['title']
      );
      add_row( 'field_5a41b153e46cf', $eanArr, $postID );
    }
  }
  if($elids) {
    foreach ($elids as $elid) {
      $elidArr = array(
        'field_5a63c016794b0' => $elid['elid'],
        'field_5a93403f9cbc8' => $elid['title']
      );
      add_row( 'field_5a63b843892e3', $elidArr, $postID );
    }
  }
  if($mpns) {
    foreach ($mpns as $mpn) {
      $mpnArr = array(
        'field_5a63bfdb794ac' => $mpn['mpn'],
        'field_5a933fe296fa7' => $mpn['title']
      );
      add_row( 'field_5a2dac1c832c2', $mpnArr, $postID );
    }
  }
  // Update Codes Retrieved to true
  update_field( 'field_5a933bbab2218', true, $postID );

  return;
  die();
}

?>