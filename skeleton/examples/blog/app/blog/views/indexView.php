<?php
class indexView extends A_Http_View {

	public function render() {
		$model = $this->load()->model();

		$template = $this->load()->template();
		$template->set('model', $model);

		$this->setRenderer($template);

		return parent::render();
	}
}