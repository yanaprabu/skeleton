<?php
include 'config.php';
include 'A/Sandbox/Collection.php';
include 'A/Sandbox/Paginator.php';
include 'A/Sandbox/Template.php';
include 'A/Sandbox/PaginationHelper.php';

$collection = new Collection();
$collection->add ('one', 'uno');
$collection->add ('two', 'dos');
$collection->add ('three', 'tres');
$collection->add ('four', 'cuatro');
$collection->add ('five', 'cinco');
$collection->add ('six', 'seis');
$collection->add ('seven', 'siete');
$collection->add ('eight', 'ocho');
$collection->add ('nine', 'nueve');
$collection->add ('ten', 'diez');
$collection->add ('eleven', 'once');
$collection->add ('twelve', 'doce');
$collection->add ('thirteen', 'trece');
$collection->add ('fourteen', 'catorce');
$collection->add ('fifteen', 'quince');
$collection->add ('sixteen', 'diez y seis');
$collection->add ('seventeen', 'diez y siete');
$collection->add ('eighteen', 'diez y ocho');
$collection->add ('ninetten', 'diez y nueve');
$collection->add ('twenty', 'viente');
$collection->add ('twenty one', 'viente uno');
$collection->add ('twenty two', 'viente dos');
$collection->add ('twenty three', 'viente tres');
$collection->add ('twenty four', 'viente cuatro');
$collection->add ('twenty five', 'viente cinco');
$collection->add ('twenty six', 'viente seis');
$collection->add ('twenty seven', 'viente siete');
$collection->add ('twenty eight', 'viente ocho');
$collection->add ('twenty nine', 'viente nueve');
$collection->add ('thirty', 'treinte');

$page_number = isset ($_GET['page'])?$_GET['page']:1;
$items_per_page = 3;
$paginator = new Paginator ($collection, $page_number, $items_per_page);
$template = new Template ('templates/standard_pagination.tpl');
$helper = new PaginationHelper ($paginator, $template, 2);

?>

<p><?=$helper->render(); ?></p>
<? foreach ($paginator->current() as $key => $value): ?>
<strong><?=$key; ?>:</strong> <?=$value ?><br />
<? endforeach; ?>
<p><?=$helper->render(); ?></p>