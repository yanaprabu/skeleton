<?php
	$browser->readDir();
	$dirs = $browser->getDirs();
	$files = $browser->getFiles();
?><!--
<html>
<head>
<title>Directory Browser Example</title>
</head>
<body bgcolor="#ffffff" text="#000000" link="#0000ff" vlink="#800080" alink="#ff0000">
-->
Browsing: /<?php echo $browser->getRelativePath(); ?><br/>
<div style="width: 300px; height: 200px; border: solid 1px gray; overflow: auto; ">
<table border="0" rowspacing="0" colspacing="0">
<?php
	foreach ($dirs as $entry) {
		if (strlen($entry['filename']) > $maxlength) {
			$entry['filename'] = substr($entry['filename'], 0, $maxlength);
		}
		$param = $browser->buildParameters($entry['param']);
		echo "<tr>\n<td><a href=\"{$_SERVER['SCRIPT_NAME']}/filemgr/?$param\">{$entry['filename']}</a></td>\n<td>&nbsp;</td>\n<td>&nbsp;</td>\n</tr>\n";
	}
	foreach ($files as $entry) {
		if (strlen($entry['filename']) > $maxlength) {
			$entry['filename'] = substr($entry['filename'], 0, $maxlength);
		}
		echo "<tr>\n";
		echo "<td width=\"80%\">{$entry['filename']}</td>\n";
		echo "<td><a href=\"{$_SERVER['SCRIPT_NAME']}/filemgr/renamefile/?{$param}&file={$entry['filename']}\">rename</a></td>\n";
		echo "<td><a href=\"{$_SERVER['SCRIPT_NAME']}/filemgr/deletefile/?{$param}&file={$entry['filename']}\">delete</a></td>\n";
		echo "</tr>\n";
	}
?>
</table>
</div>
<!--
<br/>
<a href="../">Return to Examples</a>
</body>
</html>
-->