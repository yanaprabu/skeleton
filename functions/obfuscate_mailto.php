<?php
/*
 * by Ken Butcher based on code by John Haller
 * 
 * This work is licensed under a Creative Commons Attribution-ShareAlike 2.5 License allowing you to use 
 * this code on your own site as long as you give proper attribution and include the same license.
 */
function obfuscate_mailto($strEmail){
	$strNewAddress = '';
	for($intCounter = 0; $intCounter < strlen($strEmail); $intCounter++){
		$strNewAddress .= "&#" . ord(substr($strEmail,$intCounter,1)) . ";";
	}
	$arrEmail = explode("&#64;", $strNewAddress);
	$strTag = "<script language="."Javascript"." type="."text/javascript".">\n";
	$strTag .= "<!--\n";
	$strTag .= "document.write('<a href=\"mai');\n";
	$strTag .= "document.write('lto');\n";
	$strTag .= "document.write(':" . $arrEmail[0] . "');\n";
	$strTag .= "document.write('@');\n";
	$strTag .= "document.write('" . $arrEmail[1] . "\">');\n";
	$strTag .= "document.write('" . $arrEmail[0] . "');\n";
	$strTag .= "document.write('@');\n";
	$strTag .= "document.write('" . $arrEmail[1] . "<\/a>');\n";
	$strTag .= "// -->\n";
	$strTag .= "</script><noscript>" . $arrEmail[0] . " at \n";
	$strTag .= str_replace("&#46;"," dot ",$arrEmail[1]) . "</noscript>";
	return $strTag;
}