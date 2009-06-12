<?php

$db = new PDO ("mysql:host=" . $config['db']['hostname'] . ";" . "dbname=" . $config['db']['database'], $config['db']['username'], $config['db']['password']) or die ('Error: could not connect to DB');
