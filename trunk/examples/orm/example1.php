<?php

include('../config.php');
include('orm_config.php');
#include('PostMapper.php');
#include('UserMapper.php');
#include('DomainObjects.php');

$mapper = new PostMapper($db);
$post = new Post();
$post->title = 'New Title';
$post->body = 'New Body';
$post->author_id = 1;
//$mapper->insert($post);
$post->id = 2;
$mapper->update($post);
$post = $mapper->find(1);
$post->title = 'Updated Title';
$post->body = 'Updated Body';
$mapper->update($post);
$posts = $mapper->find();

$userMapper = new UserMapper($db);
$user = $userMapper->find(1);
p($user);

?>

<?php foreach ($posts as $post): ?>
<h1><?php echo $post->title; ?></h1>
<p><?php echo $post->body; ?></p>
<?php endforeach; ?>