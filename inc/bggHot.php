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
    
    // Send filtered array to function to retrieve game details
    ks_getGameDetails($games);

    // crafted_var_dump($games);
  }

}

function ks_getGameDetails($games) {

  foreach($games as $game) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.boardgamegeek.com/xmlapi2/thing?id='. $game['id'] .'&ratingcomments=1&stats=1',
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
      // If we get the game details from BGG
      $details = simplexml_load_string($response);

      $item = $details->item;

      $postGame['title'] = $game['name'];
      $postGame['bggID'] = $game['id'];
      $postGame['yearPublished'] = $game['published'];

      $postGame['description'] = xml_attribute($item->description, 0);
      $postGame['image'] = xml_attribute($item->image, 0);
      $postGame['maxPlayers'] = xml_attribute($item->maxplayers, 'value');
      $postGame['minPlayers'] = xml_attribute($item->minplayers, 'value');
      $postGame['maxPlayTime'] = xml_attribute($item->maxplaytime, 'value');
      $postGame['minPlayTime'] = xml_attribute($item->minplaytime, 'value');
      $postGame['minAge'] = xml_attribute($item->minage, 'value');
      $postGame['rating'] = xml_attribute($item->statistics->ratings->average, 'value');

      $cats = array();
      foreach($item->link as $link) {
        $cat = array();
        $cat['id'] = xml_attribute($link, 'id');
        $cat['type'] = xml_attribute($link, 'type');
        $cat['value'] = xml_attribute($link, 'value');

        array_push($cats, $cat);
      }
      $postGame['categories'] = $cats;
      
      // crafted_var_dump($postGame);

      // Post game with details from BGG
      // $postID = ks_postNewGame($postGame);


      // Get responses from Amazon and UPC Item DB
      $codes = array();
      $amazonRes = ks_getAmazonSearch($postGame['title']);
      $amazonRes = simplexml_load_string($amazonRes);
      $amazonItem = $amazonRes->Items->Item[0];

      $amzTitle = xml_attribute($amazonItem->ItemAttributes->Title, 0);
      $amzAsin = xml_attribute($amazonItem->ASIN, 0);
      $amzEan = xml_attribute($amazonItem->ItemAttributes->EAN, 0);
      $amzMpn = xml_attribute($amazonItem->ItemAttributes->MPN, 0);
      $amzUpc = xml_attribute($amazonItem->ItemAttributes->UPC, 0);
      $amzElid = xml_attribute($amazonItem->ItemAttributes->ELID, 0);

      $upcRes = ks_getUpcIds($postGame['title']);
      $upcRes = json_decode($upcRes);
      $upcItems = $upcRes->items;

      $upcs = array();
      $asins = array();
      $eans = array();
      $elids = array();
      $mpns = array();
      foreach($upcItems as $upcItem) {
        crafted_var_dump($upcItem);
      }

      crafted_var_dump($codes);

      // crafted_var_dump($amazonItem);
      // crafted_var_dump($upcRes);


      // Send game ID codes to function to be saved into DB.
      // ks_saveGameIds($codes);

      break;
    }


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