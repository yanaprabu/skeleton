<h3>A little home content</h3>
<p>Lorum ipsum</p>
<h3>Defaults to showing a list of the latest posts:</h3>
<ul>
<?php foreach($maincontent as $post){
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