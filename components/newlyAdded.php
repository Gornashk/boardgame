<?php 
function ks_newly_added () {
  // $queried = get_queried_object();
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => 5,
  );
  // The Query
  $the_query = new WP_Query( $args );
  // Create empty posts array
  $posts = array();
  // The Loop
  if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {

      $the_query->the_post();
      // Create new posts object
      $item = new stdClass();
      // Set the properties we want on the item
      $item->id      = get_the_ID();
      $item->acf     = get_fields();
      $item->post_title   = get_the_title();
      $item->permalink    = get_permalink();
      $item->images['medium']['url']   = get_the_post_thumbnail_url(get_the_ID(), '250x250');

      // Push item to posts array
      array_push($posts, $item);
    }
    /* Restore original Post Data */
    wp_reset_postdata();
  } else {
    // no posts found
  }
  
  return $posts;
}
?>