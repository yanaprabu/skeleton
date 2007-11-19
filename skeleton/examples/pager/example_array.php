<?php
include 'config.php';
include 'A/Pager.php';
include 'A/Pager/Array.php';

// initialize an array for testing
for ($i=0; $i<=75; ++$i) {
	$myarray[$i] = 'This is row ' . $i;
}
#$myarray = null;
// create a data object that has the interface needed by the Pager object
$datasource = new A_Pager_Array($myarray);

// create pager using values from datasource and request params
$pager = new A_Pager($datasource);
$pager->setRangeSize(5);

// create a request processor to set pager from GET parameters
$request = new A_Pager_Request($pager);
$request->process();
	
if ($pager->getLastRow() > 0) {

	// create a HTML writer to output
	$writer = new A_Pager_HTMLWriter($pager);
	
	// get rows of data
	$start_row = $pager->getStartRow();
	$end_row = $pager->getEndRow();
	$rows = $datasource->getRows($start_row, $end_row);
	
	// display the data
	echo '<table border="1">';
	$n = $start_row;
	foreach ($rows as $value) {
		echo '<tr>';
		echo '<td>' . $n++ . '.</td><td>' . $value . '</td>';
		echo '</tr>';
	}
	echo '</table>';
	
	// display the paging links
	echo $writer->getFirstLink() . ' | ' . $writer->getPrevLink() . ' | ' . implode(' | ', $writer->getRangeLinks()) . ' | ' . $writer->getNextLink() . ' | ' . $writer->getLastLink() . '<p/>';
	
} else {
	
	echo 'No records found.';
	
}

?>
<p/>
<a href="../">Return to Examples</a>
