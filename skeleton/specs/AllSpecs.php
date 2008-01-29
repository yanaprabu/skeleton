<?php
$phpspec_path = 'c:/Users/ct/workspace/php/PHPSpec';
$library_path = '..';
set_include_path(get_include_path() . PATH_SEPARATOR . $phpspec_path . PATH_SEPARATOR . $library_path);

require_once 'Framework.php';

$options = new stdClass;
$options->recursive = true;
$options->specdocs = true;
$options->reporter = 'html';

PHPSpec_Runner::run($options);
