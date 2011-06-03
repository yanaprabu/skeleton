<?php
include 'config.php';
include 'A/Paginator/Collection.php';
include 'A/Pager.php';
include 'A/Pager/Array.php';
include 'A/Template/Include.php';		// same as A/Sandbox/Template.php

// converted class to use the A_Pager methods
class PagerHelper	{

function __construct ($paginator, $template, $size)	{
	$this->paginator = $paginator;
	$this->template = $template;
	$this->size = $size;
}

function first()	{
#	if ($this->paginator->previous ($this->size) && $this->paginator->previous ($this->size) > $this->paginator->first()) return $this->paginator->first();
	if (($this->paginator->getCurrentPage() - $this->size) > $this->paginator->getFirstPage()) {
		return $this->paginator->getFirstPage();
	} else {
		return '';
	}
}

function last()	{
#	if ($this->paginator->last() && $this->paginator->next ($this->size) && ($this->paginator->next ($this->size) < $this->paginator->last())) return $this->paginator->last();
	if (($this->paginator->getLastPage() && ($this->paginator->getCurrentPage() + $this->size) < $this->paginator->getLastPage())) {
		return $this->paginator->getLastPage();
	} else {
		return '';
	}
}

function previous()	{
	return ($this->paginator->getPrevPage() > $this->paginator->getFirstPage()) ? $this->paginator->getPrevPage() : '';
}

function next()	{
	return ($this->paginator->getNextPage() < $this->paginator->getLastPage()) ? $this->paginator->getNextPage() : '';
}

function page()	{
	return $this->paginator->getCurrentPage();
}

function before()	{
	$before = new Collection();
	if ($this->paginator->getCurrentPage()-5 > $this->paginator->getFirstPage()) {
		$before->add(-$this->size + 1, $this->paginator->getPrevPage(5));
	}
	$first = $this->paginator->getFirstPage();
	if ($this->paginator->getCurrentPage() > $first) {
		for ($a = $this->size; $a >= 1; $a--):
			$before->add($a, $this->paginator->getPrevPage($a));
			if ($this->paginator->getPrevPage($a) == $first) {
				break;
			}
		endfor;
	}
	return $before;
}

function after()	{
	$after = new Collection();
	$last = $this->paginator->getLastPage();
	if ($this->paginator->getCurrentPage() < $last) {
		for ($a = 1; $a <= $this->size; $a++):
			$after->add($a, $this->paginator->getNextPage($a));
			if ($this->paginator->getNextPage($a) == $last) {
				break;
			}
		endfor;
	}
	if ($this->paginator->getCurrentPage()+5 < $this->paginator->getLastPage()) {
		$after->add ($this->size + 1, $this->paginator->getNextPage(5));
	}
	return $after;
}

function render()	{
	$this->template->set('paginator', $this->paginator);
	$this->template->set('previous', $this->previous());
	$this->template->set('next', $this->next());
	$this->template->set('first', $this->first());
	$this->template->set('last', $this->last());
	$this->template->set('page', $this->page());
	$this->template->set('before', $this->before());
	$this->template->set('after', $this->after());
	return $this->template->render();
}

}

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

$datasource = new A_Pager_Array($collection->toArray());	// temporary hack until pager supports Collections

$pager = new A_Pager($datasource);
$pager->setPageSize(3);

// create a request processor to set pager from GET parameters
$request = new A_Pager_Request($pager);
$request->process();

$template = new A_Template_Include('templates/standard_pagination.tpl');

// create a HTML writer to output
#$helper = new A_Pager_HTMLWriter($pager);

// get rows of data
$start_row = $pager->getStartRow();
$end_row = $pager->getEndRow();
$rows = $datasource->getRows($start_row, $end_row);

$helper = new PagerHelper ($pager, $template, 2);

?>

<p><?php echo $helper->render(); ?></p>
<?php foreach ($rows as $key => $value): ?>
<strong><?php echo $key; ?>:</strong> <?php echo $value ?><br />
<?php endforeach; ?>
<p><?php echo $helper->render(); ?></p>