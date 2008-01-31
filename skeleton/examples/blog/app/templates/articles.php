<?php

?>
<html>
<body>
	
	<h2>This is the articles view</h2>
	
	<h3>Navigation</h3>
	<ul>
		<li><a href="/examples/blog/">Home</a></li>
		<li><a href="/examples/blog/posts/">/posts/</a></li>
		<li><a href="/examples/blog/articles/">/articles/</a></li>
		<li><a href="/examples/blog/articles/all/">/articles/all/</a></li>
		<li><a href="/examples/blog/example/">/example/</a></li>
	</ul>
	<h3>This is a list of articles</h3>
	<ul>
	<?php foreach($content as $article){
		echo '<li>'.$article.'</li>';
	} ?>
	</ul>
	
</body>
</html>