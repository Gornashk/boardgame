<?php
/*
Recently Viewed Posts
Version: 2.1.0.1
Plugin URI: http://www.pinoy.ca/
Description: Show "What others are reading now" links on your page. 
Author: Pinoy.ca 
Author URI: http://www.pinoy.ca/

Copyright ( c ) 2010
Released under the GPL license
http://www.gnu.org/licenses/gpl.txt

    This file is part of WordPress.
    WordPress is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    ( at your option ) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	INSTALL: 
	Unzip Plugin, upload to wp-content/plugins folder, activate, then add this line wherever on your theme:
	<?php if ( function_exists( 'recently_viewed_posts' ) ) recently_viewed_posts(); ?> 

	Plugin settings are set in your widget gallery.
	
	LEGAL:
	GPL Copyright as above, Tested on WP 2.4+, No warranties promised or implied, use at your own risk
	
	TROUBLESHOOT:
	Uncomment the line "recently_viewed_posts_uninstall()" to reset the thing!

*/

// Uncomment the next line to reset the thing!
// recently_viewed_posts_uninstall();

// Max number of links to keep
if ( !defined( 'MAX_RECENTLY_VIEWED_LINKS' ) )
	define( 'MAX_RECENTLY_VIEWED_LINKS', 16 );

add_action( 'wp_footer', 'add_to_recently_viewed_posts' );

function recently_viewed_posts_cache_set( $value = null ) {
	$value = apply_filters("recently_viewed_posts_cache_set", $value);
	// use WP2.8 Transients API if available
	if ( function_exists( 'set_transient' ) ) 
		set_transient( 'recently_viewed_posts', $value, $expiration = 0 );
	else if ( get_option( 'recently_viewed_posts' ) )
		update_option( 'recently_viewed_posts', $value );
	else
		set_option( 'recently_viewed_posts', $value );
}

function recently_viewed_posts_cache_get() {
	// use WP2.8 Transients API if available
	$get = function_exists( 'get_transient' ) ? get_transient( 'recently_viewed_posts' ) :
		get_option( 'recently_viewed_posts' );
	return apply_filters("recently_viewed_posts_cache_get", $get);
}

function _bot_detected() {

  return (
    isset($_SERVER['HTTP_USER_AGENT'])
    && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
  );
}

// add [ID, IP, time] set to end of 'recently_viewed_posts' wordpress option, unless already there.
function add_to_recently_viewed_posts() {
	global $post;
	$type = get_post_type();
	if(!is_singular('post')) {
		return;
	}
	if(_bot_detected()) {
		return;
	}

	// get existing list
	$recently_viewed_posts = recently_viewed_posts_cache_get();
	if ( !$recently_viewed_posts || !is_array( $recently_viewed_posts ) ) 
		$recently_viewed_posts = array();
	// remove if there, then add
	foreach ( $recently_viewed_posts as $key => $recently_viewed_post ) 
		if ( is_array( $recently_viewed_post ) && $recently_viewed_post[0] == $post->ID ) {
			unset( $recently_viewed_posts[$key] ); 
			break;
		}
	$item = array( $post->ID, recently_viewed_posts_get_remote_IP(), time() );
	$item = apply_filters( "recently_viewed_posts_new", $item );
	if ( empty( $item ) ) return;
	array_unshift( $recently_viewed_posts, $item );

	// make sure we only keep MAX_RECENTLY_VIEWED_LINKS number of links
	if ( count( $recently_viewed_posts ) > MAX_RECENTLY_VIEWED_LINKS ) 
		$recently_viewed_posts = array_slice( $recently_viewed_posts, 0, MAX_RECENTLY_VIEWED_LINKS );
	// save
	recently_viewed_posts_cache_set( $recently_viewed_posts );
}

/* Works out the time since the entry post, takes a an argument in unix time ( seconds ) */
function recently_viewed_posts_time_since( $original ) {
	// array of time period chunks
	$chunks = array( 
		array( 60 * 60 * 24 * 365 , 'year' ),
		array( 60 * 60 * 24 * 30 , 'month' ),
		array( 60 * 60 * 24 * 7, 'week' ),
		array( 60 * 60 * 24 , 'day' ),
		array( 60 * 60 , 'hour' ),
		array( 60 , 'minute' ),
	array( 1, 'second' ),
	 );
	
	$today = time(); /* Current unix time  */
	$since = $today - $original;
	
	// $j saves performing the count function each time around the loop
	for ( $i = 0, $j = count( $chunks ); $i < $j; $i++ ) {
		
		$seconds = $chunks[$i][0];
		$name = $chunks[$i][1];
		
		// finding the biggest chunk ( if the chunk fits, break )
		if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
			// DEBUG print "<!-- It's $name -->\n";
			break;
		}
	}
	
	$print = ( $count == 1 ) ? '1 '.$name : "$count {$name}s";
	
	if ( $i + 1 < $j ) {
		// now getting the second item
		$seconds2 = $chunks[$i + 1][0];
		$name2 = $chunks[$i + 1][1];
		
		// add second item if it's greater than 0
		if ( ( $count2 = floor( ( $since - ( $seconds * $count ) ) / $seconds2 ) ) != 0 ) {
			$print .= ( $count2 == 1 ) ? ', 1 '.$name2 : ", $count2 {$name2}s";
		}
	}
	return apply_filters("recently_viewed_posts_time_since", $print);
}

// returns <li> list of recently viewed posts, excluding visits by this visitor
function get_recently_viewed_posts( $max_shown = 10 ) {

	if ( $max_shown + 0 > 0 );
	else $max_shown = 10;
	
	$recently_viewed_posts = recently_viewed_posts_cache_get();
	if ( !$recently_viewed_posts && !is_array( $recently_viewed_posts ) ) 
		return "";
	$html = "";
	$count = 0;
	
	$html .= "<!-- BUFFER:" . count( $recently_viewed_posts ) . "-->";

	// run a WP_Query so that the get_permalinks and get_the_titles don't cause individual queries.
	if ( $max_shown > 5 ) { // guesstimate threshold
		$post_in = array();
    foreach ( $recently_viewed_posts as $item ) 
      /*
      ** CHANGE THIS BACK TO != BEFORE LAUNCH
			*/
			if ( $item[1] != recently_viewed_posts_get_remote_IP() ) {
				$post_in[] = $item[0];
				if ( ++$count == $max_shown ) 
					break;  // i've shown enough
			}
		if(empty($post_in)) { return; }
    new WP_Query( array( 'post__in' => $post_in, 'posts_per_page' => 10000 ) );
	}
  
  $count = 0;
  foreach ( $recently_viewed_posts as $item ) 
    /*
    ** CHANGE THIS BACK TO != BEFORE LAUNCH
    */
		if ( $item[1] != recently_viewed_posts_get_remote_IP() ) {
      // Create new posts object
      $game = new stdClass();
      // Set the properties we want on the game
      $game->id      = $item[0];
      $game->acf     = get_fields( $item[0] );
      $game->post_title   = get_the_title( $item[0] );
      $game->permalink    = get_permalink( $item[0] );
      $game->images['medium']['url']   = get_the_post_thumbnail_url($item[0], '250x250');

      $html .= ks_gameBox($game);
			if ( ++$count == $max_shown ) 
				break;  // i've shown enough
		}
		
	return apply_filters( "get_recently_viewed_posts", $html );
}

// Finally, not as a widget. 
function recently_viewed_posts( $max_shown = 5 ) {
  // $html = get_recently_viewed_posts( $max_shown );
  /*
	if ( $html && ( $html != "" ) ) { ?>
		<div class="recently-viewed-posts">
      <h3 class="recently-viewed-posts-header">What others are reading right now</h3>
      <div class="recently-viewed-posts-list">
        <ul class="recently-viewed-posts-list">
		      <?php echo $html; ?>
		    </ul>
      </div>
    </div>
<?php
	} */
}

// Get visitor's real IP, hashed
function recently_viewed_posts_get_remote_IP() {
	if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )   //check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'].$SECRET_KEY;
	if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )   //to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$ip = $_SERVER['REMOTE_ADDR'];
	if ( defined('SECRET_KEY') )
		$md5 = md5( ip2long( $ip ) . 'SECRET_KEY' );
	else
		$md5 = md5( ip2long( $ip ) );
	return apply_filters( "recently_viewed_posts_get_remote_IP", $md5 );
}

?>