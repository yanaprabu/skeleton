<h1>Posts</h1>
<p>This is a list of all posts</p>

<ul>
	<?php foreach($content as $article){
		echo '<li>';
		echo '<h4><a href="'. $article['permalink'] . '">' . $article['title'] . '</a></h4>';
		echo '<p>' .  date('F jS, Y', strtotime($article['date']) ) . '</p>';
		echo '<p>' .  $article['excerpt'] . '</p>';
		echo '<p>' .  $article['content'] . '</p>';
		echo '</li>';
	} ?>
</ul>
