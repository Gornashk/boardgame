<?php

require_once('bones.php'); // if you remove this, bones will break

/************* THUMBNAIL SIZE OPTIONS *************/

/* NOTE: Only use thumbnails if you need to, to stop digital waste */

add_image_size( '540x999', 540, 999, false );
add_image_size( '250x250', 250, 250, false );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas

function bones_register_sidebars() {

    register_sidebar(array(

    	'id' => 'sidebar1',

    	'name' => __('Default Sidebar', 'bonestheme'),

    	'description' => __('The first (primary) sidebar.', 'bonestheme'),

    	'before_widget' => '<div id="%1$s" class="widget %2$s">',

    	'after_widget' => '</div>',

    	'before_title' => '<h3 class="widgettitle">',

    	'after_title' => '</h3>',

    ));  

} // don't remove this bracket!

/************* SEARCH FORM LAYOUT *****************/

function bones_wpsearch($form) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >

    <label class="screen-reader-text" for="s">' . __('Search for:', 'bonestheme') . '</label>

    <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','bonestheme').'" />

    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />

    </form>';

    return $form;

} // don't remove this bracket!

//* Add Vue to Project
require_once( get_stylesheet_directory() . '/vue/bootstrap.php' );

function crafted_var_dump($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

// Enable Featured Image
if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' ); 
}

// Fields to Algolia
add_filter( 'algolia_post_shared_attributes', 'my_post_attributes', 10, 2 );
add_filter( 'algolia_searchable_post_shared_attributes', 'my_post_attributes', 10, 2 );

/**
 * @param array   $attributes
 * @param WP_Post $post
 *
 * @return array
 */
function my_post_attributes( array $attributes, WP_Post $post ) {

    if ( 'post' !== $post->post_type ) {
        // We only want to add an attribute for the 'speaker' post type.
        // Here the post isn't a 'speaker', so we return the attributes unaltered.
        return $attributes;
    }

    $description = get_field( 'description', $post->ID );
    $excerpt = substr($description, 0, 1500);
    // Get the field value with the 'get_field' method and assign it to the attributes array.
    // @see https://www.advancedcustomfields.com/resources/get_field/
    $attributes['acf']['prices'] = get_field( 'prices', $post->ID );
    $attributes['acf']['description'] = $excerpt;
    $bggRank = get_field( 'rating', $post->ID );
    $attributes['bggRank'] = (float)$bggRank;
    $attributes['images']['medium']['url'] = get_the_post_thumbnail_url($post->ID, '250x250');
    $attributes['bgg_id'] = intval( get_field( 'bgg_id', $post->ID ) );

    // Always return the value we are filtering.
    return $attributes;
}


  
  function add_custom_taxonomies() {
      // Add new "Locations" taxonomy to Posts
      register_taxonomy('mechanic', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        'show_admin_column' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
          'name' => _x( 'Mechanics', 'taxonomy general name' ),
          'singular_name' => _x( 'Mechanic', 'taxonomy singular name' ),
          'search_items' =>  __( 'Search Mechanics' ),
          'all_items' => __( 'All Mechanics' ),
          'parent_item' => __( 'Parent Mechanic' ),
          'parent_item_colon' => __( 'Parent Mechanic:' ),
          'edit_item' => __( 'Edit Mechanic' ),
          'update_item' => __( 'Update Mechanic' ),
          'add_new_item' => __( 'Add New Mechanic' ),
          'new_item_name' => __( 'New Mechanic Name' ),
          'menu_name' => __( 'Mechanics' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
          'slug' => 'mechanics', // This controls the base slug that will display before each term
          'with_front' => false, // Don't display the category base before "/locations/"
          'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
      ));
  
      // Add new "Locations" taxonomy to Posts
      register_taxonomy('family', 'post', array(
          // Hierarchical taxonomy (like categories)
          'hierarchical' => true,
          'show_admin_column' => true,
          // This array of options controls the labels displayed in the WordPress Admin UI
          'labels' => array(
            'name' => _x( 'Families', 'taxonomy general name' ),
            'singular_name' => _x( 'Family', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Families' ),
            'all_items' => __( 'All Families' ),
            'parent_item' => __( 'Parent Family' ),
            'parent_item_colon' => __( 'Parent Family:' ),
            'edit_item' => __( 'Edit Family' ),
            'update_item' => __( 'Update Family' ),
            'add_new_item' => __( 'Add New Family' ),
            'new_item_name' => __( 'New Family Name' ),
            'menu_name' => __( 'Families' ),
          ),
          // Control the slugs used for this taxonomy
          'rewrite' => array(
            'slug' => 'family', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
          ),
      ));
  
        // Add new "Locations" taxonomy to Posts
      register_taxonomy('publisher', 'post', array(
          // Hierarchical taxonomy (like categories)
          'hierarchical' => true,
          'show_admin_column' => true,
          // This array of options controls the labels displayed in the WordPress Admin UI
          'labels' => array(
            'name' => _x( 'Publishers', 'taxonomy general name' ),
            'singular_name' => _x( 'Publisher', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Publishers' ),
            'all_items' => __( 'All Publishers' ),
            'parent_item' => __( 'Parent Publisher' ),
            'parent_item_colon' => __( 'Parent Publisher:' ),
            'edit_item' => __( 'Edit Publisher' ),
            'update_item' => __( 'Update Publisher' ),
            'add_new_item' => __( 'Add New Publisher' ),
            'new_item_name' => __( 'New Publisher Name' ),
            'menu_name' => __( 'Publishers' ),
          ),
          // Control the slugs used for this taxonomy
          'rewrite' => array(
            'slug' => 'publisher', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
          ),
      ));
  
      register_taxonomy('artists', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        'show_admin_column' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
          'name' => _x( 'Artists', 'taxonomy general name' ),
          'singular_name' => _x( 'Artist', 'taxonomy singular name' ),
          'search_items' =>  __( 'Search Artists' ),
          'all_items' => __( 'All Artists' ),
          'parent_item' => __( 'Parent Artist' ),
          'parent_item_colon' => __( 'Parent Artist:' ),
          'edit_item' => __( 'Edit Artist' ),
          'update_item' => __( 'Update Artist' ),
          'add_new_item' => __( 'Add New Artist' ),
          'new_item_name' => __( 'New Artist Name' ),
          'menu_name' => __( 'Artists' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
          'slug' => 'artists', // This controls the base slug that will display before each term
          'with_front' => false, // Don't display the category base before "/locations/"
          'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
  
    register_taxonomy('designers', 'post', array(
      // Hierarchical taxonomy (like categories)
      'hierarchical' => true,
      'show_admin_column' => true,
      // This array of options controls the labels displayed in the WordPress Admin UI
      'labels' => array(
        'name' => _x( 'Designers', 'taxonomy general name' ),
        'singular_name' => _x( 'Designer', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Designers' ),
        'all_items' => __( 'All Designers' ),
        'parent_item' => __( 'Parent Designer' ),
        'parent_item_colon' => __( 'Parent Designer:' ),
        'edit_item' => __( 'Edit Designer' ),
        'update_item' => __( 'Update Designer' ),
        'add_new_item' => __( 'Add New Designer' ),
        'new_item_name' => __( 'New Designer Name' ),
        'menu_name' => __( 'Designers' ),
      ),
      // Control the slugs used for this taxonomy
      'rewrite' => array(
        'slug' => 'designers', // This controls the base slug that will display before each term
        'with_front' => false, // Don't display the category base before "/locations/"
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
      ),
    ));


    register_taxonomy('awards', 'post', array(
      // Hierarchical taxonomy (like categories)
      'hierarchical' => true,
      'show_admin_column' => true,
      // This array of options controls the labels displayed in the WordPress Admin UI
      'labels' => array(
        'name' => _x( 'Awards', 'taxonomy general name' ),
        'singular_name' => _x( 'Award', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Awards' ),
        'all_items' => __( 'All Awards' ),
        'parent_item' => __( 'Parent Award' ),
        'parent_item_colon' => __( 'Parent Award:' ),
        'edit_item' => __( 'Edit Award' ),
        'update_item' => __( 'Update Award' ),
        'add_new_item' => __( 'Add New Award' ),
        'new_item_name' => __( 'New Award Name' ),
        'menu_name' => __( 'Awards' ),
      ),
      // Control the slugs used for this taxonomy
      'rewrite' => array(
        'slug' => 'designers', // This controls the base slug that will display before each term
        'with_front' => false, // Don't display the category base before "/locations/"
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
      ),
    ));
  }
  add_action( 'init', 'add_custom_taxonomies', 0 );
  
  
  function get_data($url) {
      $ch = curl_init();
      $timeout = 15;
      $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
      curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
  }

  function get_Cj_data($url) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',
      // CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_HTTPHEADER => array(
        "Authorization: 00a6dd1cf06e33479ee017af3d7de3e1e8f77407ab66777c2ca9d7e2c8b2a099e6c22a7e6d01a27277707247503f435ce0dfc18d442771cb347afd4e2306ffb967/292905c8710689363184e61f3efc856f580af11160973cb42fb92721e69e231818776f8b727a9c9a2b18e3ef66840874e395c7d47efce8f5fa7c435936b73b31"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }

  function xml_attribute($object, $attribute)
{
    if(isset($object[$attribute]))
        return (string) $object[$attribute];
}
  
  if( function_exists('acf_add_options_page') ) {
	
    acf_add_options_page(array(
    'page_title'  => 'Theme General Settings',
    'menu_title'  => 'Theme Settings',
    'menu_slug'   => 'theme-general-settings',
    'capability'  => 'edit_posts',
    'position' => 4,
    'redirect'    => false
    ));
  }
  
  
  require_once( __DIR__ . '/inc/postGame.php' );
  require_once( __DIR__ . '/inc/updateGamePrice.php' );
  
  require_once( __DIR__ . '/inc/amazonPrice.php' );
  require_once( __DIR__ . '/inc/walmartPrice.php' );
  require_once( __DIR__ . '/inc/cjPrice.php' );

  require_once( __DIR__ . '/inc/upcIDs.php' );

  require_once( __DIR__ . '/inc/amazonSearch.php' );
  require_once( __DIR__ . '/inc/bggHot.php' );
  