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
		<h1>This is the home view</h1>
	
		<h3>Navigation</h3>
		<ul>
			<li><a href="/examples/blog/">/home</a></li>
			<li><a href="/examples/blog/blog/">/blog/</a></li>
			<li><a href="/examples/blog/blog/posts/">/blog/posts/</a></li>
			<li><a href="/examples/blog/articles/">/articles/</a></li>
			<li><a href="/examples/blog/articles/all/">/articles/all/</a></li>
		</ul>
		
	</div>
	
	<div id="content">
		
		<h3>A little home content</h3>
		<p>Lorum ipsum</p>
		<h3>Defaults to showing a list of the latest posts:</h3>
		<ul>
		<?php foreach($posts as $post){
			echo '<li>';
			echo '<h4><a href="'. $post['permalink'] . '">' . $post['title'] . '</a></h4>';
			echo '<p>' .  $post['date'] . '</p>';
			echo '<p>' .  $post['excerpt'] . '</p>';
			echo '<p>' .  $post['content'] . '</p>';
			echo '</li>';
		} ?>
		</ul>	
	
		<h3>And showing a list of the latest articles:</h3>
		<ul>
		<?php foreach($articles as $article){
			echo '<li>'.$article.'</li>';
		} ?>
		</ul>
	</div>
</div>
</body>
</html>