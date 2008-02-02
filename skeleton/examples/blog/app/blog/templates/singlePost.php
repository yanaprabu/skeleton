<html>
<body>
	
	<h2>This is the singleposts view</h2>
	
	<h3>Navigation</h3>
	<ul>
		<li><a href="/examples/blog/">/</a></li>
		<li><a href="/examples/blog/posts/">/posts/</a></li>
		<li><a href="/examples/blog/articles/">/articles/</a></li>
		<li><a href="/examples/blog/articles/all/">/articles/all/</a></li>
		<li><a href="/examples/blog/example/">/example/</a></li>
	</ul>
	
	<h3>This is the post you selected</h3>
	<ul>
	<?php foreach($content as $article){
		echo '<li>';
		echo '<h4><a href="'. $article['permalink'] . '">' . $article['title'] . '</a></h4>';
		echo '<p>' .  $article['date'] . '</p>';
		echo '<p>' .  $article['excerpt'] . '</p>';
		echo '<p>' .  $article['content'] . '</p>';
		echo '</li>';
	} ?>
	</ul>
	<h3>Comments for this post:</h3>
	<p>not yet ;)</p>

</body>
</html>