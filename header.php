<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name='ir-site-verification-token' value='2015756532' />
  	
	<title><?php wp_title();?></title>
	<?php wp_head();
	$nonce = wp_create_nonce("ks_postNewGame");
  if( $_SERVER['SERVER_NAME'] == 'dev.boardgamerdeals.com' ) {
    $algoliaPrefix = 'boardgame_dev_';
  }
  if( $_SERVER['SERVER_NAME'] == 'boardgamerdeals.com' || $_SERVER['SERVER_NAME'] == 'www.boardgamerdeals.com' ) {
    $algoliaPrefix = 'boardgame_';
  }
	?>
	<script type="text/javascript">
		var nonce = '<?php echo $nonce; ?>';
		var adminAjax = '<?php echo admin_url('admin-ajax.php'); ?>';
		var siteUrl = '<?php echo get_bloginfo("url"); ?>';
		var algoliaPrefix = '<?= $algoliaPrefix; ?>';
	</script>
	<!-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->

	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-60x60.png" />
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-152x152.png" />
	<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon-196x196.png" sizes="196x196" />
	<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon-16x16.png" sizes="16x16" />
	<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon-128.png" sizes="128x128" />
	<meta name="application-name" content="&nbsp;"/>
	<meta name="msapplication-TileColor" content="#FFFFFF" />
	<meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri(); ?>/img/mstile-144x144.png" />
	<meta name="msapplication-square70x70logo" content="<?php echo get_stylesheet_directory_uri(); ?>/img/mstile-70x70.png" />
	<meta name="msapplication-square150x150logo" content="<?php echo get_stylesheet_directory_uri(); ?>/img/mstile-150x150.png" />
	<meta name="msapplication-wide310x150logo" content="<?php echo get_stylesheet_directory_uri(); ?>/img/mstile-310x150.png" />
	<meta name="msapplication-square310x310logo" content="<?php echo get_stylesheet_directory_uri(); ?>/img/mstile-310x310.png" />

</head>

<body <?php body_class(); ?>>
<?php do_action( 'crafted_before' ); ?>

<div id="page" :class="{ opened : mobileMenu }">

	<header class="site-header" itemscope="" itemtype="http://schema.org/WPHeader">
	  <div class="container">
	    <div class="row">
	      <div class="col-sm-4 col-md-4 headFlex">
	        <a href="/" class="site-logo">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/Board_Game_Deals_Logo.svg" 
						alt="<?php echo get_bloginfo('name') .' | '. get_bloginfo('description'); ?>">
					</a>
	      </div>
	      <div class="col-sm-8 col-md-8 headMain headFlex">
					<div class="headerSearch">
						<div v-if="false" class="preloadBar"></div>
						<single-search :options="options"></single-search>
					</div>
	      </div>
	    </div>
		</div>
		<div class="navicon-wrap">
			<button class="lines-button x" :class="{ close: mobileMenu }"  @click="mobileMenu = !mobileMenu" type="button" role="button" aria-label="Toggle Navigation">
				<span class="lines"></span>
			</button>
		</div>
	</header>
	<?php ks_menu(); ?>
