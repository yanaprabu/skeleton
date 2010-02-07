
	
	<h3>This is the post you selected</h3>
	
	<?php foreach($content as $article){
		echo '<div class="post">';
		echo '<h2 class="post_title"><a href="'. $article['permalink'] . '">' . $article['title'] . '</a></h2>';
		echo '<p class="post_meta">On ' .  $article['post_date'] . ' by ' . $article['username'] . '</p>';
		echo '<p>' .  $article['excerpt'] . '</p>';
		echo '<p>' .  $article['content'] . '</p>';
		echo '	</div>';
	} 
	?>

	<h3>Leave a reply</h3>
	<form id="comment_form" action="" method="post">
		<p>
			<label>Name</label>
			<input type="text" name="author" value="" >
		</p>
		<p>
			<label>Email</label>
			<input type="text" name="author_email" value="" >
		</p>
		<p>
			<label>Url</label>
			<input type="text" name="author_url" value="" >
		</p>
		<p>
			<label>Name</label>
			<textarea name="comment" rows="5" cols="20"></textarea>
		</p>
		<p>
			<input type="submit" name="submit" value="Send" >
		</p>	
		
	</form>
	
	<div id="comments">
	<h3><?php echo $content[0]['nocomms']; ?> Comments for this post:</h3>
	
	<ul id="comment-list">
	<?php 
	if(empty($comments)){
		echo '<li>No comments now</li>';
	} 
	foreach($comments as $comment){
		echo '<li class="alt item" id="comment-' . $comment['comment_id'] . '">';
		echo '	<h4 class="vcard">';
		echo '		<a href="#comment-' . $comment['comment_id'] . '">#</a> ';
		echo '		<strong><cite>' . $comment['author'] . '</cite> says on ';
		echo '		<span class="comment-date"> ' . $comment['comment_date']. '</span></strong>';
		echo '		<span class="gravatar">';
		echo '			<img src="img/user.png" width="48" height="48" alt="" />';
		echo '		</span>';
		echo '	</h4>';
		echo '	<blockquote><p>' . $comment['comment']. '</p></blockquote>';
		echo '</li>';
	}
	?>
	</ul>
	
	</div>