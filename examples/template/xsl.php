<?php
error_reporting(E_ALL);
require_once('config.php');
require dirname(__FILE__) . '/../../A/autoload.php';

$template = new A_Template_Xslt('collection.xsl');

$xml = '<collection>
 <cd>
  <title>Fight for your mind</title>
  <artist>Ben Harper</artist>
  <year>1995</year>
 </cd>
 <cd>
  <title>Electric Ladyland</title>
  <artist>Jimi Hendrix</artist>
  <year>1997</year>
 </cd>
</collection>';

$template->setXML($xml);
#$template->setXMLFilename('collection.xml');
echo $template->render();
?>