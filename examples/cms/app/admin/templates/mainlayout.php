<?php

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo "Blog: $title"; ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<base href="<?php echo $BASE; ?>" />
	<!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $BASE ?>css/screen.css" > -->
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $BASE ?>css/style.css" >
	<title><?php echo $head; ?></title>
</head>
<body>
<div id="container">

	<div id="header">
		<h1><?php echo "Blog: $title"; ?></h1>
		
		<ul id="mainnav">
			<li><a href="<?php echo $BASE ?>">Home</a></li>
			<li><a href="<?php echo $BASE ?>posts/">Posts</a></li>
			<li><a href="<?php echo $BASE ?>admin/">Admin</a></li>
		</ul>
		
	</div>
	
	<div id="content">
		<?php echo $maincontent; ?>
	</div><!-- close content -->
	
	<div id="sidebar">
		<ul id="sidenav">
			<li><a href="<?php echo $BASE ?>">Home</a></li>
			<li><a href="<?php echo $BASE ?>posts/">Posts</a></li>
			<li><a href="<?php echo $BASE ?>admin/">Admin</a></li>
		</ul>
		<div id="loginbox">
			<p>Admin: <a href="<?php echo $BASE ?>login/">login</a> | <a href="<?php echo $BASE ?>login/logout">logout</a></p>
		</div>
		<?php if (isset($subcontent)) echo "<div id=\"subcontent\">\n$subcontent\n</div>"; ?>

		<? //include('includes/inc_quicksearch.php'); ?>
		<? //include('includes/inc_socialnetworks.php'); ?>
		<? //include('includes/inc_loginbox.php'); ?>
		<? //include('includes/inc_viewByIslandList.php'); ?>
	</div><!-- close sidebar -->
	
&nbsp;
	<div id="header">
		<a href="/index.php">Home</a>
	</div>
	
	<div id="main-navigation">
		<ul id="topnav">
			<li id="t1"><a href="/search_start.php">Find A Property</a></li>
			<li id="t2"><a href="/communities/">Communities</a></li>
			<li id="t3"><a href="/bahamas.php">About The Bahamas</a></li>
			<li id="t9"><a href="/articles.php">Articles</a></li>	
			<li id="t4"><a href="/buyguide.php">Buyer's Guide</a></li>
			<li id="t5"><a href="/sellguide.php">Seller's Guide</a></li>
			<li id="t6"><a href="/about.php">About Us</a></li>
			<li id="t7"><a href="/contact.php">Contact Us</a></li>
			<li id="t8"><a href="/index.php">Home</a></li>
		</ul>
	</div>
	
	
	<div id="footer">
		<p>
		&copy; Copyright <?=date("Y")." Foobar"?>, Nassau, The Bahamas. Information on this site is provided without guarantee. 
		<a href="sitemap.php">Site Map</a> | <a href="/signup.php?newsletter">Get our Newsletter</a>
		</p>
		<a id="footerToTop" href="#header">Top</a>
	</div>

	
	
</div><!-- close container -->
</body>
</html>