<?php

class filemgr extends A_Controller_Action {

/**
 *
 *  * Browse directories
    * Search files
    * Upload files
    * View files
    * Edit files
    * Rename files
    * Delete files
    * Download files
    * Define root directory, user cannot 'break out' of it
    * Sorting up and down, by name, size, type and modification date
    * Move files (cut/copy/paste functionality)
    * Create and delete folders
    * Create text files
    * User Interface supports language packs. You can easily create your own. Available languages in the download: english and german.

 */

	public function index ($locator) {
		$basedir = $_SERVER['DOCUMENT_ROOT'];
		$maxlength = 20;
		
		$browser = new A_File_Browser($this->_request(), $basedir);
		
		$template = $this->_load()->template();
		$template->set('maxlength', $maxlength);
		$template->set('browser', $browser);
		$this->_response()->set('content', $template);
	}

	public function browse ($locator) {
		$this->index($locator);
	}
	
	public function deletefile ($locator) {
		$file = $this->_request('file', '/[^A-Za-z0-9\_\-\.\ ]/');
		$this->_response()->set('content', "DELETE $file");
	}
	
	public function renamefile ($locator) {
		$file = $this->_request('file', '/[^A-Za-z0-9\_\-\.\ ]/');
		$this->_response()->set('content', "RENAME $file");
	}
	
	public function movefile ($locator) {
		$file = $this->_request('file', '/[^A-Za-z0-9\_\-\.\ ]/');
		$this->_response()->set('content', "MOVE $file");
	}
	
}
