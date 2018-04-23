<?php
  
  add_action( 'wp_ajax_nopriv_ks_postNewGame', 'ks_postNewGame' );
  add_action( 'wp_ajax_ks_postNewGame', 'ks_postNewGame' );
  function ks_postNewGame($game) {
    if($game) {
      $title = $game['title'];
      $bggID = $game['bggID'];
      $description = $game['description'];
      $image = $game['image'];
      $categories = $game['categories'];
      $maxPlayers = $game['maxPlayers'];
      $minPlayers = $game['minPlayers'];
      $maxPlayTime = $game['maxPlayTime'];
      $minPlayTime = $game['minPlayTime'];
      $minAge = $game['minAge'];
      $yearPublished = $game['yearPublished'];
      $rating = $game['rating'];
    } else {
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
      $rating = $_POST['rating'];
    }
  
    //return;
    $date = current_time( 'Y-m-d H:i:s', $gmt = 0 );
    $postdate = date($date);

    // Format game description
    $description = html_entity_decode($description, 0, "UTF-8");
    // $description = str_replace( , '&mdash;', $description );
  
  
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
  
    update_field('field_5a2dbe2658a21', $bggID, $game_post_ID);
    update_field('field_5a3d654a4a6f4', $description, $game_post_ID);
    update_field('field_5a3d64d04a6f0', $maxPlayers, $game_post_ID);
    update_field('field_5a3d650b4a6f1', $minPlayers, $game_post_ID);
    update_field('field_5a3d651e4a6f3', $minPlayTime, $game_post_ID);
    update_field('field_5a3d65134a6f2', $maxPlayTime, $game_post_ID);
    update_field('field_5a2ef263d448f', $minAge, $game_post_ID);
    update_field('field_5a3d65c34a6f5', $yearPublished, $game_post_ID);
    update_field('field_5a88641b23010', $rating, $game_post_ID);
  
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
    // Remove from uncategorized
    wp_remove_object_terms( $game_post_ID, 'Uncategorized', $category );

  
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
?>