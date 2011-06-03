<?php

error_reporting ('E_ALL ^ E_NOTICE');

class UrlGenerator	{
	function __construct ($state = array())	{
		$this->base = $_SERVER['PHP_SELF'];
		$this->state = $state;
	}
	function render ($base = null, $array = array())	{
		return ($base ? $base : $this->base) . '?' . http_build_query (array_merge ($this->state, $array));
	}
}

// Pretend this is a state object, and the code below is set up by A_Pager_Request
$state = array();
$state['page'] = $_GET['page'] ? $_GET['page'] : 1;
$state['sort_key'] = $_GET['sort_key'] ? $_GET['sort_key'] : 'text';
$state['sort_order'] = $_GET['sort_order'] ? $_GET['sort_order'] : 'asc';
$state['items_per_page'] = $_GET['items_per_page'] ? $_GET['items_per_page'] : 10;

// Optional: if you want link to persist. Maybe this is set up by another component?
// $state['link'] = $_GET['link'] ? $_GET['link'] : 1;

$url = new UrlGenerator ($state);

?>

<p>
<a href="<?php echo $url->render(array ('link' => 1)); ?>"><?php if ($_GET['link'] == 1): ?><strong>Link1</strong><?php else: ?>Link1<?php endif ?></a>
<a href="<?php echo $url->render(array ('link' => 2)); ?>"><?php if ($_GET['link'] == 2): ?><strong>Link2</strong><?php else: ?>Link2<?php endif ?></a>
<a href="<?php echo $url->render(array ('link' => 3)); ?>"><?php if ($_GET['link'] == 3): ?><strong>Link3</strong><?php else: ?>Link3<?php endif ?></a>
</p>
<p>
<a href="<?php echo $url->render('child_state.php'); ?>">View Child</a>
</p>

<form method="get" action="">
<input type="hidden" name="page" value="<?php echo $_GET['page'] ? $_GET['page'] : 1; ?>" />
<?php if ($state['link']): ?><input type="hidden" name="link" value="<?php echo $_GET['link'] ? $_GET['link'] : 1; ?>" /><?php endif; ?>
Order by: <select name="sort_key">
<option value="id" <?php if ($_GET['sort_key'] == 'id'): ?>selected="selected"<?php endif; ?>>ID</option>
<option value="text" <?php if ($_GET['sort_key'] == 'text'): ?>selected="selected"<?php endif; ?>>text</option>
<option value="sort" <?php if ($_GET['sort_key'] == 'sort'): ?>selected="selected"<?php endif; ?>>Sort</option>
</select>
<select name="sort_order">
<option value="asc" <?php if ($_GET['sort_order'] == 'asc'): ?>selected="selected"<?php endif; ?>>Ascending</option>
<option value="desc" <?php if ($_GET['sort_order'] == 'desc'): ?>selected="selected"<?php endif; ?>>Descending</option>
</select>
Items per page: <select name="items_per_page">
<option value="5" <?php if ($_GET['items_per_page'] == '5'): ?>selected="selected"<?php endif; ?>>5</option>
<option value="10" <?php if ($_GET['items_per_page'] == '10'): ?>selected="selected"<?php endif; ?>>10</option>
</select>
<input type="submit" name="submit" value="Show" />
</form>