<?php
class articlesView extends A_Http_View {

	public function render() {
		$model = $this->load()->model();
		$content = $model->listAll();
		$template = $this->load()->template();
		$template->set('content', $content);

		$this->setRenderer($template);

		return parent::render();
	}
}