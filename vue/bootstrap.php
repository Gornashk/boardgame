<?php
// Add controllers
require_once( __DIR__ . '/controllers/bootstrap.php' );

// Set up app div
add_action('crafted_before', function () {
  echo '<div id="app"> <!-- Open #app -->';
});

// Close app div
add_action('wp_footer', function () {
  // Add controllers
  echo '</div> <!-- Close #app -->';
});

// Enqueue Vue, Styles, Axios
// add_action( 'wp_enqueue_scripts', 'vue_enqueue_scripts_styles' );

function vue_enqueue_scripts_styles () {
  wp_enqueue_script( 'vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.3/vue.js', array('jquery'), '2.3.3', true );
}

// Pass controllers/data to page
add_action( 'wp_head', function () {
  // Example of conditionally running controllers
  if ( is_front_page() ) {
    newly_added();
  }
  if ( is_tax() || is_category() ) {
    category_games();
  }
  if ( is_singular('post') ) {
    global $post;
    single_game();
    ks_clearPriceRepeater($post->ID);
  }
  ks_get_options();

});





