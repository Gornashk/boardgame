<?php
  
function postNewGame() {
  echo 'test123';
  /*
  $title = $_POST['title'];
  $image = $_POST['image'];
  $bggId = $_POST['bggId'];

//  $ages = $_POST['ages'];
//  $numPlayers = $_POST['numPlayers'];


  // Create New Game Post
  $shirt_post = array(
    'post_title'     => $title, // The title of your post.
    'post_status'    => 'publish', // Default 'draft'.
    'post_type'      => 'post', // Default 'post'.
    'post_date'      => $postdate, // The time post was made.
  //  'post_date_gmt'  => [ Y-m-d H:i:s ] // The time post was made, in GMT.
  //  'tax_input'      => $custom_tax // For custom taxonomies. Default empty.
  );  
  $shirt_post_ID = wp_insert_post($shirt_post);
  
  //wp_set_object_terms( $shirt_post_ID, $site, $taxonomy );
  add_post_meta( $shirt_post_ID, 'bgg_id', $bggId ); // Set Deep Link custom field value
  
  /*  
  // Insert Featured Image
  $upload_dir = wp_upload_dir();
  $image_data = file_get_contents($shirtimg);
  $filename = basename($shirtimg);
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
  $attach_id = wp_insert_attachment( $attachment, $file, $shirt_post_ID );
  require_once(ABSPATH . 'wp-admin/includes/image.php');
  $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
  wp_update_attachment_metadata( $attach_id, $attach_data );
  
  set_post_thumbnail( $shirt_post_ID, $attach_id );
  */

}
?>