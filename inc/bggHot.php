<?php
// add_action( 'wp_footer', 'ks_getBggHot' );

function ks_getBggHot() {
  
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://www.boardgamegeek.com/xmlapi2/hot?type=boardgame",
    CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Cache-Control: no-cache",
      "Postman-Token: 5ec0f07c-f06f-4501-b741-80af30274242"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    $xml = simplexml_load_string($response);
    $items = $xml->item;
    $games = [];
    foreach($items as $item) {
      // $id = $item->attributes()['id'];
      $game = [];
      $id = xml_attribute($item, 'id');
      $name = $item->name;
      $name = xml_attribute($name, 'value');
      $published = $item->yearpublished;
      $published = xml_attribute($published, 'value');

      $game['id'] = $id;
      $game['name'] = $name;
      $game['published'] = $published;

      // Filter out any games that have a published date of current year or in the future
      $year = date('Y');
      if( strtotime($game['published']) >= strtotime($year) ) {
        continue;
      }
      array_push($games, $game);
    }
    
    crafted_var_dump($games);
    // echo $response;
  }

}

?>