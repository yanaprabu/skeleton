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
<a href="<?php echo $url->render('parent_state.php', array ('link' => 1)); ?>">Back to Parent</a>
</p>