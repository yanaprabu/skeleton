<html>
<head>
<title>Skeleton - Pagination example - Standard Request</title>
</head>
<body>
<?php
include 'config.php';
include 'Datasource.php';
include 'A/Pagination/Request.php';
include 'A/Pagination/View/Standard.php';

// initialize an array for testing
for ($i=0; $i<=750; ++$i) {
	$myarray[$i]['title'] = 'This is row ' . $i;
	$myarray[$i]['month'] = date ('F', time() + ($i * 60 * 60 * 24 * 30));
}

// create a data object that has the interface needed by the Pager object
$datasource = new Datasource($myarray);

// create a request processor to set pager from GET parameters
$pager = new A_Pagination_Request($datasource);

// set range (number of links on either side of current page) and process core based on request
$pager->setRangeSize(3)->process();

// create a new standard view
$view = new A_Pagination_View_Standard($pager);

// retrieve items on current page
$rows = $pager->getItems();

// Set up view internally. For first/last, label is optional. If no label is passed, the number will be displayed.
$view
	->first('First')
	->previous('Previous')
	->range()
	->next('Next')
	->last('Last');

// display the data
echo "<div>{$view->render()}</div>";
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

?>
<p/>
<a href="../">Return to Examples</a>
</p>

</body>
</html>