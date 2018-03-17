<?php
add_action( 'wp_footer', 'ks_getBggHot' );

function ks_getBggHot() {
  
  // Get data from signed URL
  // $response = get_data('https://www.boardgamegeek.com/xmlapi2/hot?type=boardgame');

  $response = get_data( 'https://www.boardgamegeek.com/xmlapi2/hot?type=boardgame' );

  crafted_var_dump($response);

  return;

}

?>