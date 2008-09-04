
<h3>This is a list of all posts</h3>

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
