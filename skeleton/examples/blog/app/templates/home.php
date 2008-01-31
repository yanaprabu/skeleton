<?php

?>
<html>
<body>
	
	<h2>This is the home view</h2>
	
	<h3>Navigation</h3>
	<ul>
		<li><a href="/examples/blog/">Home</a></li>
		<li><a href="/examples/blog/posts/">/posts/</a></li>
		<li><a href="/examples/blog/articles/">/articles/</a></li>
		<li><a href="/examples/blog/articles/all/">/articles/all/</a></li>
		<li><a href="/examples/blog/example/">/example/</a></li>
	</ul>
	
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
	
</body>
</html>