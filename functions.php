<?php

require_once('bones.php'); // if you remove this, bones will break

/************* THUMBNAIL SIZE OPTIONS *************/

/* NOTE: Only use thumbnails if you need to, to stop digital waste */

//add_image_size( '300x300', 300, 300, true );

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

    // Get the field value with the 'get_field' method and assign it to the attributes array.
    // @see https://www.advancedcustomfields.com/resources/get_field/
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
  
  
  add_action( 'wp_ajax_nopriv_ks_postNewGame', 'ks_postNewGame' );
  add_action( 'wp_ajax_ks_postNewGame', 'ks_postNewGame' );
  function ks_postNewGame() {
    $title = $_POST['title'];
    $bggID = $_POST['bggID'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $categories = $_POST['categories'];
    $maxPlayers = $_POST['maxPlayers'];
    $minPlayers = $_POST['minPlayers'];
    $maxPlayTime = $_POST['maxPlayTime'];
    $minPlayTime = $_POST['minPlayTime'];
    $minAge = $_POST['minAge'];
    $yearPublished = $_POST['yearPublished'];
  
    //return;
    $date = current_time( 'Y-m-d H:i:s', $gmt = 0 );
    $postdate = date($date);
  
  
    // Create New Game Post
    $game_post = array(
      'post_title'     => $title, // The title of your post.
      'post_status'    => 'publish', // Default 'draft'.
      'post_type'      => 'post', // Default 'post'.
      'post_date'      => $postdate, // The time post was made.
    //  'post_date_gmt'  => [ Y-m-d H:i:s ] // The time post was made, in GMT.
    //  'tax_input'      => $custom_tax // For custom taxonomies. Default empty.
    );  
    $game_post_ID = wp_insert_post($game_post);
  
    //wp_set_object_terms( $shirt_post_ID, $site, $taxonomy );
    // add_post_meta( $game_post_ID, 'bgg_id', $bggID );
    // add_post_meta( $game_post_ID, 'description', $description );
    // add_post_meta( $game_post_ID, 'max_players', $maxPlayers );
    // add_post_meta( $game_post_ID, 'min_players', $minPlayers );
    // add_post_meta( $game_post_ID, 'min_playtime', $minPlayTime );
    // add_post_meta( $game_post_ID, 'max_playtime', $maxPlayTime );
    // add_post_meta( $game_post_ID, 'min_age', $minAge );
    // add_post_meta( $game_post_ID, 'year_published', $yearPublished );
  
    update_field('field_5a2dbe2658a21', $bggID, $game_post_ID);
    update_field('field_5a3d654a4a6f4', $description, $game_post_ID);
    update_field('field_5a3d64d04a6f0', $maxPlayers, $game_post_ID);
    update_field('field_5a3d650b4a6f1', $minPlayers, $game_post_ID);
    update_field('field_5a3d651e4a6f3', $minPlayTime, $game_post_ID);
    update_field('field_5a3d65134a6f2', $maxPlayTime, $game_post_ID);
    update_field('field_5a2ef263d448f', $minAge, $game_post_ID);
    update_field('field_5a3d65c34a6f5', $yearPublished, $game_post_ID);
  
    // Set Board Game Categories
    foreach($categories as $cat) {
      if($cat['type'] == 'boardgamecategory') {
        $taxonomy = 'category';
      } 
      elseif($cat['type'] == 'boardgamefamily') {
        $taxonomy = 'family';
      }
      elseif($cat['type'] == 'boardgamemechanic') {
        $taxonomy = 'mechanic';
      }
      elseif($cat['type'] == 'boardgamedesigner') {
        $taxonomy = 'designers';
      }
      elseif($cat['type'] == 'boardgameartist') {
        $taxonomy = 'artists';
      }
      elseif($cat['type'] == 'boardgamepublisher') {
        $taxonomy = 'publisher';
      } else {
        continue;
      }
      $value = $cat['value'];
      wp_set_object_terms( $game_post_ID, $value, $taxonomy, true );
    }
  
    // Insert Featured Image
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image);
    $filename = basename($image);
    if(wp_mkdir_p($upload_dir['path']))
      $file = $upload_dir['path'] . '/' . $filename;
    else
      $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);
    
    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
      'post_mime_type' => $wp_filetype['type'],
      'post_title' => sanitize_file_name($filename),
      'post_content' => '',
      'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $game_post_ID );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    
    set_post_thumbnail( $game_post_ID, $attach_id );
  
    echo $game_post_ID;
    
    return;
  
    die();
  }
  
  add_action( 'wp_ajax_nopriv_ks_getUpcIds', 'ks_getUpcIds' );
  add_action( 'wp_ajax_ks_getUpcIds', 'ks_getUpcIds' );
  function ks_getUpcIds() {
    $title = $_GET['gameTitle'];
    //var_dump($title);
    // $title = rawurlencode($title);
    // $user_key = 'only_for_dev_or_pro';
    $endpoint = 'https://api.upcitemdb.com/prod/trial/search';
  
    $ch = curl_init();
    // if your client is old and doesn't have our CA certs
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      // "user_key: $user_key",
      // "key_type: 3scale"
    ));
    // HTTP GET
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_URL, $endpoint.'?s='. $title .'&match_mode=0&type=product');
    $response = curl_exec($ch);
    //$response = json_encode($response);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpcode != 200)
      echo "error status $httpcode...";
    else 
      //return $response;
      echo $response;
    /* if you need to run more queries, do them in the same connection.
    * use rawurlencode() instead of URLEncode(), if you set search string
    * as url query param
    */
    sleep(2);
    // proceed with other queries
    curl_close($ch);
  
    die();
  }
  
  add_action( 'wp_ajax_nopriv_ks_saveGameIds', 'ks_saveGameIds' );
  add_action( 'wp_ajax_ks_saveGameIds', 'ks_saveGameIds' );
  function ks_saveGameIds() {
    $postID = $_POST['postID'];
    $upcs = $_POST['upc'];
    $asins = $_POST['asin'];
    $eans = $_POST['ean'];
    $elids = $_POST['elid'];
    $mpns = $_POST['mpn'];
  
    if($upcs) {
      foreach ($upcs as $upc) {
        $upcArr = array(
          'field_5a63bff0794ad' => $upc
        );
        add_row( 'field_5a2dac28832c3', $upcArr, $postID );
      }
    }
    if($asins) {
      foreach ($asins as $asin) {
        $asinArr = array(
          'field_5a63bffd794ae' => $asin
        );
        add_row( 'field_5a2dac2e832c4', $asinArr, $postID );
      }
    }
    if($eans) {
      foreach ($eans as $ean) {
        $eanArr = array(
          'field_5a63c00a794af' => $ean
        );
        add_row( 'field_5a41b153e46cf', $eanArr, $postID );
      }
    }
    if($elids) {
      foreach ($elids as $elid) {
        $elidArr = array(
          'field_5a63c016794b0' => $elid
        );
        add_row( 'field_5a63b843892e3', $elidArr, $postID );
      }
    }
    if($mpns) {
      foreach ($mpns as $mpn) {
        $mpnArr = array(
          'field_5a63bfdb794ac' => $mpn
        );
        add_row( 'field_5a2dac1c832c2', $mpnArr, $postID );
      }
    }
    return;
    die();
  }
  
  
  add_action( 'wp_ajax_nopriv_ks_updateGamePrice', 'ks_updateGamePrice' );
  add_action( 'wp_ajax_ks_updateGamePrice', 'ks_updateGamePrice' );
  function ks_updateGamePrice() {
  
    die();
  }
  
  require_once( __DIR__ . '/inc/amazonPrice.php' );