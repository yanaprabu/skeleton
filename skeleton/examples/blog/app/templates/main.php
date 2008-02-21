<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<base href="<?php echo $BASE ?>" />
	<style type="text/css" media="screen">
		body { margin:0;padding:0;color:#333;background:#fff;}
		#container {}
		#header { padding:20px;background:#eee;}
		#content { padding:20px; }
	
	</style>
</head>
<body>
<div id="container">

	<div id="header">
		<h1>This is the main template.</h1>
		
		<h3>Navigation</h3>
		<div id="menubar">
			<ul>
			<li><a href="">/home</a></li>
			<li><a href="blog/">/blog/</a></li>
			<li><a href="blog/posts/">/blog/posts/</a></li>
			<li><a href="admin/">/admin/</a></li>
			</ul>
		</div>

	</div>
	
	<div id="content">
		<?php echo $content; ?>
	</div>

</div>
</body>
</html>