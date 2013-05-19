
	
	<h3>This is the post you selected</h3>
	
	<?php foreach($content as $article){
		echo '<div class="post">';
		echo '<h2 class="post_title"><a href="blog/'. $this->escape($article['permalink']) . '">' . $this->escape($article['title']) . '</a></h2>';
		echo '<p class="post_meta">On ' .  $this->escape($article['post_date']) . ' by ' . $this->escape($article['username']) . '</p>';
		echo '<p>' .  $this->escape($article['excerpt']) . '</p>';
		echo '<p>' .  $this->escape($article['content']) . '</p>';
		echo '	</div>';
	} 
	?>

	<h3>Leave a reply</h3>
	
	<?php if(isset($commentsuccess) && $commentsuccess === true) {
		echo '<p class="success">Thanks for commenting.</p>';
	}?>
	
	<form id="comment_form" action="blog/posts/<?php echo (int) $content[0]['id']; ?>" method="post">
		<input type="hidden" name="posts_id" value="<?php echo (int) $content[0]['id']; ?>" />
		<div>
			<label>Name</label>
			<input type="text" name="author" value="<?php echo isset($comment['author']) ? $this->escape($comment['author']) : ''; ?>" >
			<?php
			echo isset($commenterror['author']) ? '<p class="error">' . $this->escape($commenterror['author']) . '</p>' : '';
			?>
		</div>
		<div>
			<label>Email</label>
			<input type="text" name="author_email" value="<?php echo isset($comment['author_email']) ? $this->escape($comment['author_email']) : ''; ?>" >
			<?php
			echo isset($commenterror['author_email']) ? '<p class="error">' . $this->escape($commenterror['author_email'][0]) . '</p>' : '';
			?>
		</div>
		<div>
			<label>Url</label>
			<input type="text" name="author_url" value="<?php echo isset($comment['author_url']) ? $this->escape($comment['author_url']) : ''; ?>" >
			<?php
			echo isset($commenterror['author_url']) ? '<p class="error">' . $this->escape($commenterror['author_url'][0]) . '</p>' : '';
			?>
		</div>
		<div>
			<label>Name</label>
			<textarea name="comment" rows="5" cols="20"><?php echo isset($comment['comment']) ? $this->escape($comment['comment']) : ''; ?></textarea>
			<?php
			echo isset($commenterror['comment']) ? '<p class="error">' . $this->escape($commenterror['comment'][0]) . '</p>' : '';
			?>
		</div>
		<div>
			<input type="submit" name="submit" value="Send" >
		</div>	
		
	</form>
	
<div id="comments">
	<h3><?php echo $content[0]['nocomms']; ?> Comments for this post:</h3>
	
	<ul id="comment-list">
	<?php 
	if(empty($comments)){
		echo '<li>No comments now</li>';
	} 
	foreach($comments as $comment){
		echo '<li class="alt item" id="comment-' . (int) $comment['comment_id'] . '">';
		echo '	<h4 class="vcard">';
		echo '		<a href="#comment-' . (int) $comment['comment_id'] . '">#</a> ';
		echo '		<strong><cite>' . $this->escape($comment['author']) . '</cite> says on ';
		echo '		<span class="comment-date"> ' . $this->escape($comment['comment_date']) . '</span></strong>';
		echo '		<span class="gravatar">';
		echo '			<img src="img/user.png" width="48" height="48" alt="" />';
		echo '		</span>';
		echo '	</h4>';
		echo '	<blockquote><p>' . $this->escape($comment['comment']) . '</p></blockquote>';
		echo '</li>';
	}
	?>
	</ul>
	
</div>