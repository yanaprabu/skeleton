<html>
<head>
<title>Skeleton - iFrame Upload Example</title>
<script type="text/javascript">
function init() {
	document.getElementById('upload_form').onsubmit=function() {
		document.getElementById('upload_form').target = 'upload_target'; //'upload_target' is the name of the iframe
		document.getElementById("upload_target").onload = uploadDone; //This function should be called when the iframe has compleated loading
	}
}
function uploadDone() { //Function will be called when iframe is loaded
	var ret = frames['upload_target'].document.getElementsByTagName("body")[0].innerHTML;
	var data = eval("("+ret+")"); //Parse JSON // Read the below explanations before passing judgment on me
	
	if (data.success) { //This part happens when the image gets uploaded.
		document.getElementById("message").innerHTML = "Uploaded: " + data.file_name + "<br />Size: " + data.size + " KB";
		document.getElementById("message").style.color = "green";
	} else { //Upload failed - show user the reason.
		document.getElementById("message").innerHTML = "Upload Failed: " + data.errmsg;
		document.getElementById("message").style.color = "red";
	}	
}

window.onload=init;
</script>
</head>
<body>
<h2>Skeleton - iFrame Upload Example</h2>
<?php
	if (isset($uploadform)) {
		$uploadform->addHidden('controller', 'upload-files-iframe');
		$uploadform->addHidden('action', 'upload');
		$content .= $uploadform->formOpen('fc.php', '', array('id'=>'upload_form')) . "<br/>\n";
		if (isset($select_path)) {
			$content .= $uploadform->formSelectPath() . "<br/>\n";
		}
		$content .= $uploadform->formInput() . "<br/>\n";
		if (isset($multi_file)) {
			$content .= $uploadform->formInput() . "<br/>\n";
		}
		$content .= $uploadform->formSubmit() . "<br/>\n";
		$content .= "<iframe id=\"upload_target\" name=\"upload_target\" src=\"\" style=\"width:0;height:0;border:0px solid #fff;\"></iframe>\n";
		$content .= $uploadform->formClose() . "<br/>\n";
	}
	echo "<span id=\"message\">$errmsg</span>\n<div id=\"content\">\n$content\n</div>\n";
?>
<p/>
<a href="../">Return to Examples</a>
|
<a href="fc.php?controller=upload-files-iframe">iFrame Upload Another File</a>
|
<a href="fc.php?controller=upload-files">Regular Upload Example</a>
</body>
</html>
