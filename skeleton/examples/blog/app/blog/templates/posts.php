<html>
<body>
	
	<h2>This is the posts view</h2>
	
	<h3>Navigation</h3>
	<ul>
		<li><a href="/examples/blog/">/home</a></li>
		<li><a href="/examples/blog/blog/">/blog/</a></li>
		<li><a href="/examples/blog/blog/posts/">/blog/posts/</a></li>
		<li><a href="/examples/blog/articles/">/articles/</a></li>
		<li><a href="/examples/blog/articles/all/">/articles/all/</a></li>
		<li><a href="/examples/blog/example/">/example/</a></li>
	</ul>
	
	<h3>This is a list of all posts</h3>
	<p>Todo: add paging here :) Sorting, filtering?</p>
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

</body>
</html>