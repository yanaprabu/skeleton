<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<base href="<?php echo $BASE ?>" />

	<style type="text/css" media="screen">
		
		#container { margin:0 auto;width:950px; }
		#header { height:200px; }
		#maincontent { float:left;width:60%;}
		#subcontent { float:right;width:26%;}
	</style>
</head>
<body>
<div id="container">
	<div id="header">
		<h2>This is the index file for admin</h2>
		<p>With this link you can <a href="admin/login/?op=signout">log out</a></p>
	</div>
	
	<div id="maincontent">
	<?php echo $maincontent; ?>
	</div>
	<div id="subcontent">
	<?php echo $subcontent; ?>
	</div>

</div>
</body>
</html>