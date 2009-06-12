<?php

include('../config.php');
include('orm_config.php');
include('PostMapper.php');
include('Post.php');

$mapper = new PostMapper($db);
$posts = $mapper->getAll();

?>

<?php foreach ($posts as $post): ?>
<h1><?php echo $post->title; ?></h1>
<p><?php echo $post->body; ?></p>
<?php endforeach; ?>