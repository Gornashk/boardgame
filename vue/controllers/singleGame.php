<?php 
function single_game () {
  // The Loop
  if ( have_posts() ) {
    $item = new stdClass();
    while ( have_posts() ) {
      the_post();
      // Create new posts object
      
      // Set the properties we want on the item
      $item->id      = get_the_ID();
      $item->post_title   = get_the_title();
      $item->excerpt = get_the_excerpt();
      $item->permalink    = get_permalink();
      $item->images['medium']['url']   = get_the_post_thumbnail_url( get_the_ID(), '250x250' );
      $item->image   = get_the_post_thumbnail_url( get_the_ID(), '540x999' );
      $item->date    = get_the_date('m.d.Y');
      $item->div     = get_stylesheet_directory_uri();
      $item->acf     = get_fields(get_the_ID());
      $item->cats    = wp_get_post_terms( get_the_ID(), 'category' );
      $item->groups  = wp_get_post_terms( get_the_ID(), 'family' );
      $item->mechanics = wp_get_post_terms( get_the_ID(), 'mechanic' );

    }
    // Print posts array to javascript for use in Vue
    $reshuffled_data = array(
      'l10n_print_after' => 'singleGame = ' . json_encode( $item )
    );
    
    wp_localize_script( 'main-js', 'single_game', $reshuffled_data );
    /* Restore original Post Data */
    wp_reset_postdata();
  } else {
    // no posts found
  }
  
  
}
?>