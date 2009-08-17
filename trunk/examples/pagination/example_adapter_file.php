<html>
<head>
<title>Skeleton - Pagination example - Standard Request</title>
</head>
<body>
<?php
include 'config.php';
include 'A/Pagination/Request.php';
include 'A/Pagination/View/Standard.php';
include 'A/Pagination/Adapter/File.php';

// create a data object that has the interface needed by the Pager object
$datasource = new A_Pagination_Adapter_File('constitution.txt');

// create a request processor to set pager from GET parameters
$pager = new A_Pagination_Request($datasource);

// set core values based on request
$pager->process();

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
$n = 1;
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value['line'] . '</td>';
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