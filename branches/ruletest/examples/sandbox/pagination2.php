<?php

include 'config.php';
error_reporting (E_ALL ^E_NOTICE);
include ('A/Sandbox/Collection.php');
include ('A/Sandbox/Pagination.php');
include ('A/Sandbox/Template.php');
include ('A/Sandbox/View.php');
include ('A/Sandbox/ArraySorter.php');

for ($i=0; $i<=100; ++$i) {
	$myarray[$i]['id'] = $i;
	$myarray[$i]['text'] = 'The month is ' . date ('F', time() + ($i * 60 * 60 * 24 * 30)) . ' and the day is ' . date ('l', time() + $i * 60 * 60 * 24);
	$myarray[$i]['sort'] = 100 - $i;
}

$collection = new Icebox_Collection();
foreach ($myarray as $item)	{
	$collection->add ($item['id'], $item);
}

$sort_key = isset ($_GET['sort_key']) ? $_GET['sort_key'] : 'id';
$sort_order = isset ($_GET['sort_order']) ? $_GET['sort_order'] : 'asc';
$page_number = isset ($_GET['page'])?$_GET['page']:1;
$items_per_page = isset ($_GET['items_per_page'])?$_GET['items_per_page']:5;
$pages_to_display = 2;

$collection->orderBy ($sort_key, $sort_order);
$pagination = new Icebox_Pagination ($collection, $items_per_page);
$pagination->setCurrentPage ($page_number);

$template = new Icebox_Template ('templates/standard_pagination2.tpl');
$helper = new Icebox_Pagination_View ($pagination, $pages_to_display, $template);

$template = new Icebox_Template ('templates/list2.tpl');
$template->pagination = $helper;
$template->pages = $pagination->getItems();
echo $template->render();