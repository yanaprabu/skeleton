<?php
ini_set('error_reporting', E_ALL);
include '../../A/autoload.php';
include '../../A/classes/DirectoryBrowser.php';

$basedir = $_SERVER['DOCUMENT_ROOT'];
$maxlength = 20;

$browser = new DirectoryBrowser(new A_Http_Request(), $basedir);

$browser->readDir();
$html = '';
while ($entry = $browser->nextDir()) {
	if (strlen($entry['filename']) > $maxlength) {
		$entry['filename'] = substr($entry['filename'], 0, $maxlength);
	}
	$html .= "<tr>\n";
	$param = $browser->buildParameters($entry['param']);
	$html .= "<td><a href=\"?$param\">{$entry['filename']}</a></td>\n";
	$html .= "</tr>\n";
}
while ($entry = $browser->nextFile()) {
	if (strlen($entry['filename']) > $maxlength) {
		$entry['filename'] = substr($entry['filename'], 0, $maxlength);
	}
	$html .= "<tr>\n";
	$html .= "<td>{$entry['filename']}</td>\n";
	$html .= "</tr>\n";
}
?><html>
<head>
<title>Directory Browser Example</title>
</head>
<body bgcolor="#ffffff" text="#000000" link="#0000ff" vlink="#800080" alink="#ff0000">
Browsing: /<?php echo $browser->getRelativePath(); ?><br/>
<div style="width: 300px; height: 200px; border: solid 1px gray; overflow: auto; ">
<table border="0" rowspacing="0" colspacing="0">
<?php echo $html ?>
</table>
</div>
<br/>
<a href="../">Return to Examples</a>
</body>
</html>
