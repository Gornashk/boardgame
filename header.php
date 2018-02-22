<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  	
	<title><?php wp_title();?></title>
	<?php wp_head();
	$nonce = wp_create_nonce("ks_postNewGame");
	?>
	<script type="text/javascript">
		var nonce = '<?php echo $nonce; ?>';
		var adminAjax = '<?php echo admin_url('admin-ajax.php'); ?>';
		var siteUrl = 'http://boardgame.local';
	</script>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'crafted_before' ); ?>
<div id="mobile-nav-wrap" class="mobile-nav-wrap" :class="{ opened : mobileMenu }" v-show="mobileMenu">
	<nav id="mobile-navigation-top" class="navigation-mobile" role="navigation">
    <ul>
     	<?php wp_nav_menu( array( 'theme_location' => 'mobile-menu', 'items_wrap' => '%3$s', 'container' => '' ) ); ?>
		</ul>
	</nav><!-- #mobile-navigation -->
</div> <!-- #mobile-nav-wrap -->
<div id="page" :class="{ opened : mobileMenu }">

	<header class="site-header" itemscope="" itemtype="http://schema.org/WPHeader">
	  <div class="container">
	    <div class="row">
	      <div class="col-sm-6 col-md-4">
	        <a href="/" class="site-logo">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/Board_Game_Deals_logo.svg" 
						alt="<?php echo get_bloginfo('name') .' | '. get_bloginfo('description'); ?>">
					</a>
	      </div>
	      <div class="col-sm-6 col-md-8">
	        <?php // wp_nav_menu( array( 'theme_location' => 'main-nav' ) ); ?>
	        
	        <!-- <div class="navicon-wrap">
	        	<button class="lines-button x" :class="{ close: mobileMenu }"  @click="mobileMenu = !mobileMenu" type="button" role="button" aria-label="Toggle Navigation">
	        		<span class="lines"></span>
	        	</button>
	        </div> -->

	      </div>
	    </div>
	  </div>
	</header>
