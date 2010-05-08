<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo "App: $title"; ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<base href="<?php echo $BASE; ?>" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $BASE ?>css/screen.css" >
	<title><?php echo $head; ?></title>
</head>
<body>
<div id="container">

	<div id="header">
		<h1><?php echo "App: $title"; ?></h1>
		
		<ul id="mainnav">
			<li><a href="<?php echo $BASE ?>">Home</a></li>
			<li><a href="<?php echo $BASE ?>posts/">Posts</a></li>
			<?php if (isset($user) && $user->isLoggedIn()) echo "<li><a href=\"{$BASE}admin/\">Admin</a></li>"; ?>
		</ul>
		
		<div id="loginbox">
			<p>Admin: <a href="<?php echo $BASE ?>login/">login</a> | <a href="<?php echo $BASE ?>login/logout">logout</a></p>
		</div>
	</div>
	
	<div id="content">
		<?php echo $maincontent; ?>
	</div>
	
	<?php if (isset($subcontent)) echo "<div id=\"subcontent\">\n$subcontent\n</div>"; ?>
	

</div>
</body>
</html>