<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>A Skeleton Framework Manual</title>

<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
<link href="prettify.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="prettify.js"></script>
<!--<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(function($) {
		$("#main_content").load("1/Introduction.html"); 
	}); 
	$('a.ajaxlink').live('click', function(){
		$('#main_content').load($(this).attr("href"));
		setTimeout('prettyPrint()', 100);
		return false;
	});
	 
});
</script>-->
</head>

<body onload="prettyPrint()">

<div id="main_header">
	<h1>A Skeleton Framework Manual</h1>
</div>

<div id="main_menu">
	<h2>Table of Contents</h2>
	<h3>Introduction</h3>
	<ol>
		<li><span>1.01</span><a class="ajaxlink" href="./?p=Introduction">Introduction</a></li>
		<li><span>1.02</span><a class="ajaxlink" href="./?p=Key_Concepts">Key Concepts</a></li>
		<li><span>1.03</span><a class="ajaxlink" href="./?p=Application_Flow">Application Flow</a></li>
		<li><span>1.04</span><a class="ajaxlink" href="./?p=Model_View_Controller">Model View Controller</a></li>
	</ol>
	
	<h3>Installation</h3>
	<ol>
		<li><span>2.01</span><a class="ajaxlink" href="./?p=Download">Download and svn</a></li>
		<li><span>2.02</span><a class="ajaxlink" href="./?p=Directory_Structure">Directory Structure</a></li>
	</ol>
	
	<h3>Quickstart</h3>
	<ol>
		<li><span>3.01</span><a class="ajaxlink" href="./?p=Quickstart">How to use the basic app</a></li>
	</ol>
	
	<h3>Topics</h3>
	<ol>
		<li><a class="ajaxlink" href="./?p=Bootstrap">Bootstrap</a></li>
		<li><a class="ajaxlink" href="./?p=Configuration">Configuration</a></li>
		<li><a class="ajaxlink" href="./?p=Urls">Urls</a></li>
		<li><a class="ajaxlink" href="./?p=Controllers">Controllers</a></li>
		<li><a class="ajaxlink" href="./?p=Views">Views</a></li>
		<li><a class="ajaxlink" href="./?p=Template_Classes">Template Classes</a></li>
		<li><a class="ajaxlink" href="./?p=Models">Models</a></li>
		<li><a class="ajaxlink" href="./?p=Databases">Database</a></li>
		<li><a class="ajaxlink" href="./?p=Pagination">Pagination</a></li>
		<li><a class="ajaxlink" href="./?p=Validation">Validation</a></li>
		<li><a class="ajaxlink" href="./?p=Filtering">Filtering</a></li>
		<li><a class="ajaxlink" href="./?p=Access_Control">Access Control</a></li>
		<li><a class="ajaxlink" href="./?p=Forms">Forms</a></li>
		<li><a class="ajaxlink" href="./?p=Error_Handling">Error Handling</a></li>
		<li><a class="ajaxlink" href="./?p=Event">Event Handling</a></li>
		<li><a class="ajaxlink" href="./?p=Socket">Socket Server</a></li>
		
	</ol>
	
	<!--<h3>Components</h3>
	<ol>

		<li><a class="ajaxlink" href="./?p=DataSpace">DataSpace.php</a></li>
		
		<li><a class="ajaxlink" href="3/Db">DB</a></li>
		<ol>
		<li><a class="ajaxlink" href="./?p=MySQL">MySQL.php</a></li>
		<li><a class="ajaxlink" href="./?p=MySQLi">MySQLi.php</a></li>
		<li><a class="ajaxlink" href="./?p=Postgres">Postgres.php</a></li>
		<li><a class="ajaxlink" href="./?p=SQLite">SQLite.php</a></li>
		</ol>
		
		<li><a class="ajaxlink" href="3/Filter">Filter</a></li>
		<ol>
		<li><a class="ajaxlink" href="./?p=Filter">Filter.php</a></li>
		<li><a class="ajaxlink" href="./?p=FilterChain">FilterChain.php</a></li>
		</ol>
		
		<li><a class="ajaxlink" href="3/functions">functions</a></li>
		
		<li><a class="ajaxlink" href="./?p=Html">Html</a></li>
		<ol>
		<li><a class="ajaxlink" href="./?p=FormField">FormField.php</a></li>
		</ol>
		

		<li><span>3.00</span><a class="ajaxlink" href="3/Http">Http</a>
			<ol>
				<li><a class="ajaxlink" href="./?p=Controller_Front">A_Controller_Front</a></li>
				<li><a class="ajaxlink" href="./?p=Http_PathInfo">A_Http_PathInfo.php</a></li>

				<li><a class="ajaxlink" href="./?p=Http_Download">Download.php</a></li>
				<li><a class="ajaxlink" href="./?p=Http_Request">Request.php</a></li>
				<li><a class="ajaxlink" href="./?p=Http_Response">Response.php</a></li>
				<li><a class="ajaxlink" href="./?p=Http_Upload">Upload.php</a></li>
-->
			</ol>
		</li>
<!--
		
		<li><a class="ajaxlink" href="./?p=Locator">Locator.php</a></li>
		<li><a class="ajaxlink" href="./?p=Logger">Logger.php</a></li>
		
		<li><a class="ajaxlink" href="3/Pager">Pager</a></li>
		<li><a class="ajaxlink" href="./?p=Pager">Pager.php</a></li>
		<ol>
		<li><a class="ajaxlink" href="./?p=ADODB">ADODB.php</a></li>
		<li><a class="ajaxlink" href="./?p=Array">Array.php</a></li>
		<li><a class="ajaxlink" href="./?p=DB">DB.php</a></li>
		<li><a class="ajaxlink" href="./?p=File">File.php</a></li>
		<li><a class="ajaxlink" href="./?p=HTMLWriterJump">HTMLWriterJump.php</a></li>
		<li><a class="ajaxlink" href="./?p=MySQL">MySQL.php</a></li>
		</ol>
		
		<li><a class="ajaxlink" href="3/Rule">Rule.php</a></li>
		<li><a class="ajaxlink" href="./?p=Rule">Rule.php</a></li>
		<ol>
		<li><a class="ajaxlink" href="./?p=Alpha">Alpha.php</a></li>
		<li><a class="ajaxlink" href="./?p=Date">Date.php</a></li>
		<li><a class="ajaxlink" href="./?p=Email">Email.php</a></li>
		<li><a class="ajaxlink" href="./?p=Length">Length.php</a></li>
		<li><a class="ajaxlink" href="./?p=Match">Match.php</a></li>
		<li><a class="ajaxlink" href="./?p=Numeric">Numeric.php</a></li>
		<li><a class="ajaxlink" href="./?p=Range">Range.php</a></li>
		</ol>
		
		<li><a class="ajaxlink" href="./?p=Session">Session.php</a></li>
		<li><a class="ajaxlink" href="./?p=Template">Template.php</a></li>
		<li><a class="ajaxlink" href="./?p=URL">URL.php</a></li>
		
		<li><a class="ajaxlink" href="3/User">User</a></li>
		<ol>
		<li><a class="ajaxlink" href="./?p=Access">Access.php</a></li>
		<li><a class="ajaxlink" href="./?p=Auth">Auth.php</a></li>
		<li><a class="ajaxlink" href="./?p=Session">Session.php</a></li>
		</ol>
		<li><a class="ajaxlink" href="./?p=Validator">Validator.php</a></li>
		<li><a class="ajaxlink" href="./?p=ValueObject">ValueObject.php</a></li>
-->	
	</ol>

	<h3>API Documentation</h3>
	<ol>
	<li><a href="api/index.html">API Documentation</a></li>
	</ol>
	
	<h3>Appendices</h3>
	<ol>
	<li><a class="ajaxlink" href="../A/LICENSE">LICENSE</a></li>
	</ol>
	
	 <p><a class="ajaxlink" href="sample_doc.html">sample doc</a></p>
	
</div>

<div id="main_content"><?php
$page = isset($_GET['p']) ? $_GET['p'] : 'Introduction';
$page = !empty($page) ? $page : 'Introduction';
$i = 1;
$exit = false;
while (!file_exists($i . '/' . $page . '.html')) {
	if ($i > 200) {
		echo '<h1>404: Not Found</h1>';
		$exit = true;
		break;
	}
	$i++;
}
if (!$exit) {
	echo file_get_contents($i . '/' . $page . '.html');
}
?>
</div>

<div id="main_footer">
	<p><a href="http://code.google.com/p/skeleton/">Project Repository</a></p>
</div>

</body>
</html>
