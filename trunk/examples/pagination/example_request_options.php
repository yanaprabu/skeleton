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

// create a data object that has the interface needed by the Pager object
$datasource = new Datasource($myarray);

// create a request processor to set pager from GET parameters
$pager = new A_Pagination_Request($datasource);
$pager->setRangeSize(3)->process();

include 'A/Pagination/View/Standard.php';

$view1 = new A_Pagination_View_Standard($pager);

$view2 = new A_Pagination_View_Standard($pager);
$view2->alwaysShowFirstLast();

$view3 = new A_Pagination_View_Standard($pager);
$view3->alwaysShowPreviousNext();

$view4 = new A_Pagination_View_Standard($pager, false, true);
$view4->alwaysShowFirstLast();
$view4->alwaysShowPreviousNext();

?>

<h3>Standard</h3>
<pre>$view = new A_Pagination_View_Standard($pager);</pre>
<div><?php echo $view1->render(); ?></div>

<h3>Always Show First & Last</h3>
<pre>$view = new A_Pagination_View_Standard($pager);
$view->alwaysShowFirstLast();</pre>
<div><?php echo $view2->render(); ?></div>

<h3>Always Show Previous & Next</h3>
<pre>$view = new A_Pagination_View_Standard($pager);
$view->alwaysShowPreviousNext();</pre>
<div><?php echo $view3->render(); ?></div>

<h3>Show All + Don't Cache Number Of Items </h3>
<pre>$view = new A_Pagination_View_Standard($pager, false, true);
$view->alwaysShowFirstLast();
$view->alwaysShowPreviousNext();</pre>
<div><?php echo $view4->render(); ?></div>

<p><a href="../">Return to Examples</a></p>

</body>
</html>