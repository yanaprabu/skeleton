<?php

class Icebox_Pagination_View	{

	function __construct (Icebox_Pagination $pagination, $size, Icebox_Template $template = null)	{
		$this->pagination = $pagination;
		if ($template)	{
			$this->template = $template;
			$this->template->setOverloadCallback ($this);
		}
		else $this->template = new Template ('templates/standard_pagination.tpl', array(), $this);
		$this->size = $size;
	}

function first()	{
	if (($this->pagination->currentPage() - $this->size) > $this->pagination->firstPage()) return $this->pagination->firstPage();
}

function last()	{
	if (($this->pagination->currentPage() + $this->size) < $this->pagination->lastPage()) return $this->pagination->lastPage();
}

function previous()	{
	if ($this->pagination->isValid ($this->pagination->currentPage() - 1)) return $this->pagination->currentPage() - 1;
}

function next()	{
	if ($this->pagination->isValid ($this->pagination->currentPage() + 1)) return $this->pagination->currentPage() + 1;
}

function count()	{
	return $this->pagination->count();
}

function current()	{
	return $this->pagination->currentPage();
}

function lastPage()	{
	return $this->pagination->lastPage();
}

function currentItemLow()	{
	return $this->pagination->currentItemLow();
}

function currentItemHigh()	{
	return $this->pagination->currentItemHigh();
}

function before()	{
	$intervals = func_num_args() > 0 ? func_get_args() : array(); 
	$before = new Icebox_Collection();
	foreach ($intervals as $interval)	{
		if (($this->pagination->currentPage() - $interval) > $this->pagination->firstPage()) $before->add ($this->pagination->currentPage() - $interval);
	}
	for ($a = $this->size; $a >= 1; $a--)	{
		if ($this->pagination->isValid ($this->pagination->currentPage() - $a)) $before->add ($this->pagination->currentPage() - $a);
	}
	return $before;
}

function after()	{
	$intervals = func_num_args() > 0 ? func_get_args() : array();
	$after = new Icebox_Collection();
	for ($a = 1; $a <= $this->size; $a++)	{
		if ($this->pagination->isValid ($this->pagination->currentPage() + $a)) $after->add ($this->pagination->currentPage() + $a);
	}
	foreach ($intervals as $interval)	{
		if (($this->pagination->currentPage() + $interval) < $this->pagination->lastPage()) $after->add ($this->pagination->currentPage() + $interval);
	}
	return $after;
}

function render()	{
	$this->template->set ('pagination', $this->pagination);
	$this->template->set ('previous', $this->previous());
	$this->template->set ('next', $this->next());
	$this->template->set ('first', $this->first());
	$this->template->set ('last', $this->last());
	$this->template->set ('current', $this->current());
	$this->template->set ('lastPage', $this->lastPage());
	$this->template->set ('currentItemLow', $this->currentItemLow());
	$this->template->set ('currentItemHigh', $this->currentItemHigh());
	$this->template->set ('lastItem', $this->count());
	$this->template->set ('before', $this->before());
	$this->template->set ('after', $this->after());
	return $this->template->render();
}

function __toString()	{
	return $this->render();
}

}