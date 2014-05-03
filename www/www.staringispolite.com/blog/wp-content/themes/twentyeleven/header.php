<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<!-- Styles from Koken -->
<link rel="stylesheet" type="text/css" media="all" href="http://staringispolite.com/koken/app/site/themes/common/css/reset.css?0.9.2" />
<link rel="stylesheet" type="text/css" media="all" href="http://staringispolite.com/koken/storage/themes/repertoire/css/fonts/default.css" />
<link rel="stylesheet" type="text/css" media="all" href="http://staringispolite.com/koken/storage/themes/repertoire/css/skeleton.css?1.3.2" /> 

<style type="text/css">
    body {
        font-size:14px;
        line-height:1.5;
        font-weight:normal;
        text-align:center;
        background:#ffffff;
        color:#666666;
        text-rendering:optimizeLegibility;
        -webkit-font-smoothing: subpixel-antialiased;
        -webkit-text-size-adjust: 100%;
    }

    img {
        -ms-interpolation-mode: bicubic;
    }

    a:link, a:visited, a:hover, a:active {
        color:#1981D1;
        text-decoration:none;
    }

    a:hover {
        color:#fa3c24;
    }

    b, strong {
        font-weight:bold;
    }

    em, i {
        font-style:italic;
    }

    p {
        font-size:16px;
        margin-bottom:14px;
        font-weight: 100;
        letter-spacing: .4px;
    }

    h1,h2,h3,h4,h5,h6 {
        font-size:12px;
        color:#222222;
        font-weight:400;
        text-transform:uppercase;
        letter-spacing:2px;
    }

    h1 a:link, h2 a:link, h3 a:link, h4 a:link, h5 a:link, h6 a:link, h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
        color:inherit;
    }

    .meta {
        font-size:11px;
        color:#999999;
    }

    .meta a:link, .meta a:visited, .meta a:active {
        color:inherit;
    }

    .meta a:hover {
        color:#fa3c24;
    }

    div.detail-text {
        font-size:14px;
        line-height:24px;
    }

    header.top {
        text-align:left;
        margin:0 auto 50px;
        overflow:auto;
    }

    header h1 {
        margin:0 0 10px;
        padding:0;
        font-size: 21px;
        line-height: 18px;
        font-weight: 400;
    }

    header h2 {
        font-size:15px !important;
        margin-bottom:8px;
        font-weight: 400;
    }

    header span.tagline {
        font-size:11px;
        text-transform:none;
        letter-spacing:0;
    }

    header h6 {
        font-size:11px;
        text-transform:none;
        letter-spacing:0;
        margin-bottom:8px;
    }

    nav li {
        margin-bottom:4px;
        font-size:12px;
    }

    nav li a:link, nav li a:visited, nav li a:hover, nav li a:active {
        color:#666666;
        text-decoration:none;
    }

    nav li a:hover {
        color:#000000;
    }

    nav a.k-nav-current {
        color:#fa3c24 !important;
    }

    div.container {
      margin-top: 20px;   
    }

    header span.tagline {
        display: inline-block;
        margin-right: 30px;
        margin-bottom: 20px;
        line-height: 22px;
        width: 210px;
        font-size: 12px;
    }

    header.top {
      margin-bottom: 0px;
    }

    .home-essays {
      width: 200px;
      margin-right: 20px;
      margin-top: -10px;
    }

    div.home-essays p {
       font-family: 'Karla', 'Helvetica Nueue', Helvetica, Arial, sans-serif;
       line-height: 22px;
       font-size: 12px;
    }
</style>
<!-- end styles from Koken -->
</head>

<body <?php body_class(); ?> style="text-align: left">
<!--
<div style="text-align: center; color: #ccc; background-color: #464646">
  NOTE: Currently re-working the header - bear with me, things might break.
</div>
-->
<div id="page" class="hfeed">

  <div class="container">
  <header class="top">
  <!--
  <header class="top" style="display:none">
  -->

    <div class="row">

    <div class="four columns">
    <h1><a href="/index.php?/" class="k-nav-current">Jonathan Howard</a></h1>   <span class="tagline">Co-founder and CEO of Emissary. Founder of Univ of Maryland Games Programming course. Co-founder of Issue Dictionary (acquired 2008).</span>
    </div>
    <div class="four columns">
    <h2>Content</h2>    <nav>
    <ul class="k-nav-list k-nav-root "><li><a href="/index.php?/">Home</a></li><li><a href="/index.php?/albums/">Albums</a></li><li><a class="k-nav-current" target="" href="http://staringispolite.com/blog">Blog</a></li><li><a target="" href="http://staringispolite.com/resume">Resume</a></li></ul>   </nav>
    </div>
    <div class="four columns">
    <h2>Social</h2> <nav>
    <ul class="k-nav-list k-nav-root "><li><a target="_blank" href="https://twitter.com/staringispolite">Twitter</a></li><li><a target="_blank" href="https://www.facebook.com/staringispolite">Facebook</a></li><li><a target="_blank" href="https://plus.google.com/102379749917261993538/posts">Google+</a></li><li><a target="_blank" href="http://www.linkedin.com/in/staringispolite/">LinkedIn</a></li></ul> </nav>
    </div>
    <div class="four columns">
    <h2>Projects</h2>   <nav>
    <ul class="k-nav-list k-nav-root "><li><a target="_blank" href="http://staringispolite.com/likepython">Like, Python: Code like you speak</a></li><li><a target="_blank" href="http://code.google.com/p/sevenup">SevenUp: User-friendly IE6+ upgrader</a></li><li><a href="/index.php?/pages/dance/">Hip Hop Dance</a></li></ul> </nav>
    </div>

    </div>

  </header>
  </div>

	<div id="main">
