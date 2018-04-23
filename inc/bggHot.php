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
    // Get all current games from database. We need this to compare to later
    $args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
      'fields' => 'ids',
    );
    $the_query = new WP_Query( $args );
    if(isset($the_query->posts) && !empty($the_query->posts)){
      $prevGames = array();
      foreach((array) $the_query->posts as $id) {
        $bggID = get_field('bgg_id', $id);
        $prevGames[] = array( 'bggID' => $bggID );
      }
    }
    $prevGames = array_column($prevGames, 'bggID');

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

      // Filter out any games that have already been added to site database. Matching based on BGG ID
      $exists = ks_filterExisting($prevGames, $game);

      // Push any games that remain into games array to be added
      if(!$exists) {
        array_push($games, $game);
      }
    }
    
    crafted_var_dump($games);
    // echo $response;
  }

}

// Filter out any games that have already been added to site database. Matching based on BGG ID
function ks_filterExisting($prevGames, $hotGame) {
  $array_id = array_search($hotGame['id'], $prevGames);
  if($array_id !== false) {
    return true;
  } else {
    return false;
  }
}

?>