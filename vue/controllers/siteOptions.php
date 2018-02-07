<?php 
function ks_get_options () {

  $item = get_fields('options');

  // // Create new posts object
  // $item = new stdClass();
  // // Set the properties we want on the item
  // $item->id      = get_the_ID();
  // $item->title   = get_the_title();
  // $item->excerpt = get_the_excerpt();
  // $item->link    = get_permalink();
  // $item->thumb   = get_the_post_thumbnail_url();
  // $item->date    = get_the_date('m.d.Y');

  
  // Print posts array to javascript for use in Vue
  $reshuffled_data = array(
      'l10n_print_after' => 'options = ' . json_encode( $item )
  );
  wp_localize_script( 'main-js', 'options', $reshuffled_data );
}
?>