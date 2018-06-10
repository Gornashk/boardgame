<?php 
function ks_home_cats () {
  // $queried = get_queried_object();
  $rows = get_field('taxonomy_selector', 'options');
  $terms = array();

  if($rows) {
    foreach($rows as $row) {
      $terms[] = $row['term_selected'][0];
    }
  }
  
  $homeCats = array();

  if(count($terms) > 0) {
    foreach($terms as $term) {

      // crafted_var_dump($term);
      
      $args = array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'tax_query' => array(
          array(
            'taxonomy' => $term->taxonomy,
            'field' => 'term_id',
            'terms' => $term->term_id
          )
        ),
        'meta_key' => 'rating',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
      );
      // The Query
      $the_query = new WP_Query( $args );
      // Create empty posts array
      $posts = array();
      $posts['name'] = $term->name;
      $posts['id'] = $term->term_id;
      $posts['taxPage'] = get_term_link($term->term_id, $term->taxonomy);
      $posts['games'] = array();
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
          array_push($posts['games'], $item);
        }
        /* Restore original Post Data */
        wp_reset_postdata();
      } else {
        // no posts found
      }
      array_push($homeCats, $posts);
    }

  }

  return $homeCats;

}
?>