<html>
<head>
<title>Skeleton - Pagination example - basic</title>
</head>
<body>
<?php
include 'config.php';
include 'Datasource.php';
include 'A/Pagination/Request.php';

// initialize an array for testing
for ($i=0; $i<=750; ++$i) {
	$myarray[$i]['title'] = 'This is row ' . $i;
	$myarray[$i]['month'] = date ('F', time() + ($i * 60 * 60 * 24 * 30));
}
#$myarray = null;
// create a data object that has the interface needed by the Pager object
$datasource = new Datasource($myarray);

// create a request processor to set pager from GET parameters
$pager = new A_Pagination_Request($datasource);
$pager->setRangeSize(3)->process();

$url = new A_Pagination_Helper_Url();
$url->set('page', $pager->getCurrentPage());
$url->set('order_by', $pager->getOrderBy());

include 'A/Pagination/View/Standard.php';
$view = new A_Pagination_View_Standard($pager);

$rows = $pager->getItems();

$view
	->previous('Previous')
	->first()
	->range()
	->last()
	->next('Next');

echo "<div>{$view->render()}</div>";

// display the data
echo '<table border="1">';
echo '<tr><th>' . $view->link()->order('', 'Row') . '</th><th>' . $view->link()->order('title', 'Title') . '</th><th>' . $view->link()->order('month', 'Month') . '</th></tr>';
$n = 1;
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value['title'] . '</td><td>' . $value['month'] . '</td>';
	echo '</tr>';
}
echo '</table>';

echo "<div>{$view->render()}</div>";

#dump($pager);
?>
<p/>
<a href="../">Return to Examples</a>
</p>

</body>
</html>