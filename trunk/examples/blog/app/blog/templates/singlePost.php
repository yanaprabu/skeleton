
	
	<h3>This is the post you selected</h3>
	
	<?php foreach($content as $article){
		echo '<div class="post">';
		echo '<h2 class="post_title"><a href="blog/'. $article['permalink'] . '">' . $article['title'] . '</a></h2>';
		echo '<p class="post_meta">On ' .  $article['post_date'] . ' by ' . $article['username'] . '</p>';
		echo '<p>' .  $article['excerpt'] . '</p>';
		echo '<p>' .  $article['content'] . '</p>';
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
			<input type="text" name="author" value="<?php echo isset($comment['author']) ? htmlentities($comment['author'],ENT_QUOTES,"UTF-8") : ''; ?>" >
			<?php
			echo isset($commenterror['author']) ? '<p class="error">' . htmlentities($commenterror['author'],ENT_QUOTES,"UTF-8") . '</p>' : '';
			?>
		</div>
		<div>
			<label>Email</label>
			<input type="text" name="author_email" value="<?php echo isset($comment['author_email']) ? htmlentities($comment['author_email'],ENT_QUOTES,"UTF-8") : ''; ?>" >
			<?php
			echo isset($commenterror['author_email']) ? '<p class="error">' . htmlentities($commenterror['author_email'][0],ENT_QUOTES,"UTF-8") . '</p>' : '';
			?>
		</div>
		<div>
			<label>Url</label>
			<input type="text" name="author_url" value="<?php echo isset($comment['author_url']) ? htmlentities($comment['author_url'],ENT_QUOTES,"UTF-8") : ''; ?>" >
			<?php
			echo isset($commenterror['author_url']) ? '<p class="error">' . htmlentities($commenterror['author_url'][0],ENT_QUOTES,"UTF-8") . '</p>' : '';
			?>
		</div>
		<div>
			<label>Name</label>
			<textarea name="comment" rows="5" cols="20"><?php echo isset($comment['comment']) ? htmlentities($comment['comment'],ENT_QUOTES,"UTF-8") : ''; ?></textarea>
			<?php
			echo isset($commenterror['comment']) ? '<p class="error">' . htmlentities($commenterror['comment'][0],ENT_QUOTES,"UTF-8") . '</p>' : '';
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