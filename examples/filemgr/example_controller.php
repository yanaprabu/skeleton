<?php
/**
 *
 *  * Browse directories
    * Search files
    * Upload files
    * View files
    * Edit files
    * Rename files
    * Delete files
    * Download files
    * Define root directory, user cannot 'break out' of it
    * Sorting up and down, by name, size, type and modification date
    * Move files (cut/copy/paste functionality)
    * Create and delete folders
    * Create text files
    * User Interface supports language packs. You can easily create your own. Available languages in the download: english and german.

 */
ini_set('error_reporting', E_ALL);
include '../../A/Locator.php';

$basedir = $_SERVER['DOCUMENT_ROOT'];
$maxlength = 20;

$Locator = new A_Locator();
$Locator->autoload();

$Request = new A_Http_Request();
$Response = new A_Http_Response();
$Response->setTemplate('mainlayout.php');
$Locator->set('Request', $Request);
$Locator->set('Response', $Response);

// put clean URL values into Request
$Pathinfo = new A_Http_Pathinfo();
$Pathinfo->run($Request);

$Front = new A_Controller_Front('', array('', 'filemgr', ''));
$Front->run($Locator);

echo $Response->render();
