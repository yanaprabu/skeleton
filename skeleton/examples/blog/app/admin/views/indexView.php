<?php
class indexView extends A_Http_View {

	/*
	public function render() {
		
		$model = $this->load()->model();
		$model = 'This is the model';
		$template = $this->load()->template('admin');
		$template->set('content', $model);
		$template->set('maincontent', 'This i the ma in content');
		$template->set('subcontent', 'This is the sub content');//dump($template);
		$this->setRenderer($template);//dump($this);

		return parent::render();
		
	}*/
	public function render() {
		$model = $this->load()->model();

		$template = $this->load()->template();
		$template->set('model', $model);//dump($template);

		$this->setRenderer($template);

		return parent::render();
	}
}