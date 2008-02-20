<?php

?>
<html>
<style type="text/css" media="screen">
	body { margin:0;padding:0;color:#333;background:#fff;}
	#container {}
	#header { padding:20px;background:#eee;}
	#content { padding:20px; }
	
</style>
<body>
<div id="container">

	<div id="header">
		<h1>This is the main template</h1>
		
		<h3>Navigation</h3>
		<div id="menubar">
			<ul>
			<li><a href="/examples/blog/">/home</a></li>
			<li><a href="/examples/blog/blog/">/blog/</a></li>
			<li><a href="/examples/blog/blog/posts/">/blog/posts/</a></li>
			<li><a href="/examples/blog/articles/">/articles/</a></li>
			<li><a href="/examples/blog/articles/all/">/articles/all/</a></li>
			</ul>
		</div>

	</div>
	
	<div id="content">
		<?php echo $content; ?>
	</div>

</div>
</body>
</html>