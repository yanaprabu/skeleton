
<h3>This is a list of all posts</h3>


	<?php foreach($content as $article){
		echo '<div class="post">';
		echo '<h2 class="post_title"><a href="posts/'. $article['post_id'] . '">' . $article['title'] . '</a></h2>';
		echo '<p class="post_meta">On ' .  $article['post_date'] . ' by ' . $article['username'] . '</p>';
		echo '<p>' .  $article['excerpt'] . '</p>';
		echo '<p>' .  $article['content'] . '</p>';
		echo '<p class="comment_meta">' . $article['nocomms'] . ' comments</p>';
		echo '</div>';
	} ?>

