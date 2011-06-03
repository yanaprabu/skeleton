<?php
class exampleView extends A_Http_View {

	public function render($template='', $scope='') {
		$model = $this->_load()->model();

		$template = $this->_load()->template();
		$template->set('model', $model);

		$this->setRenderer($template);

		return parent::render();
	}
}
