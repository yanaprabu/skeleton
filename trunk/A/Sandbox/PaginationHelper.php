<?php

class PaginationHelper	{

function __construct (Paginator $paginator, Template $template, $size)	{
	$this->paginator = $paginator;
	$this->template = $template;
	$this->size = $size;
}

function first()	{
	if ($this->paginator->previous ($this->size) && $this->paginator->previous ($this->size) > $this->paginator->first()) return $this->paginator->first();
}

function last()	{
	if ($this->paginator->last() && $this->paginator->next ($this->size) && ($this->paginator->next ($this->size) < $this->paginator->last())) return $this->paginator->last();
}

function previous()	{
	return $this->paginator->previous();
}

function next()	{
	return $this->paginator->next();
}

function page()	{
	return $this->paginator->page();
}

function before()	{
	$before = new Collection();
	if ($this->paginator->previous (5) > $this->paginator->first()) $before->add (-$this->size + 1, $this->paginator->previous (5));
	for ($a = $this->size; $a >= 1; $a--):
	if ($this->paginator->previous ($a)) $before->add ($a, $this->paginator->previous ($a));
	endfor;
	return $before;
}

function after()	{
	$after = new Collection();
	for ($a = 1; $a <= $this->size; $a++):
	if ($this->paginator->next ($a)) $after->add ($a, $this->paginator->next ($a));
	endfor;
	if ($this->paginator->next (5) < $this->paginator->last()) $after->add ($this->size + 1, $this->paginator->next (5));
	return $after;
}

function render()	{
	$this->template->set ('paginator', $this->paginator);
	$this->template->set ('previous', $this->previous());
	$this->template->set ('next', $this->next());
	$this->template->set ('first', $this->first());
	$this->template->set ('last', $this->last());
	$this->template->set ('page', $this->page());
	$this->template->set ('before', $this->before());
	$this->template->set ('after', $this->after());
	return $this->template->render();
}

}