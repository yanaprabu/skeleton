<?php
include 'config.php';
include 'A/Sql/Prepare.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$prepare = new A_Sql_Prepare("INSERT mytable SET one=?, two='?', three=:three, three=?");
$prepare->bind("foo's", array(':three'=>'faz'), 'bar', 1);
echo "Default is escaping, but no quoting<br/>A_Db_Prepare::render=" . $prepare->render() . '<br/>';

$prepare = new A_Sql_Prepare("INSERT mytable SET one=?, two=?, three=:three, three=?");
$prepare->quoteValues()->bind("foo's", array(':three'=>'faz'), 'bar', 1);
echo "With quoting on by calling quoteValues()<br/>A_Db_Prepare::render=" . $prepare->render() . '<br/>';

#dump($prepare);