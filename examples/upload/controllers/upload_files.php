<?php
include_once 'A/Http/Upload.php';
include_once 'A/Http/UploadForm.php';
include_once 'A/Template/Strreplace.php';

class upload_files {
	protected $content;
	protected $template_main;

	function upload_files($locator) {
	}
	
	function index($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$page_template = new A_Template_Strreplace('templates/upload_files.html');
		
		$content = '';
				
		$upload = new A_Http_Upload();
		
		// destination directory for uploaded file
		$upload->setBasePath('./files/');
		
		// destination directory names for select
		$upload->addPath(1, 'test1/', 'One');
		$upload->addPath(2, 'test2/', 'Two');
		
/*
		$n = ini_get('file_uploads');
		#echo "file_uploads=$n<br>";
		$n = ini_get('upload_max_filesize');
		#echo "upload_max_filesize=$n<br>";
		
		$n = $upload->getMaxFilesize();
		#echo "check_max_filesize=$n<br>";
*/
		
		$nfiles = $upload->fileCount();
#echo "nfiles=$nfiles<br>";
#echo '<pre>' . print_r($_FILES, 1) . '</pre>';

		if ($nfiles) {
		
			$upload->setReplace(false);		// if destination file exists delete before moving new file
			$upload->setMinFilesize(1);		// set min size of file with this value
			$upload->setMaxFilesize(1000);		// cap size of file with this value, zero no cap
			$upload->setAllowedTypes(array());		// set array of allowed file MIME types
		
			for ($i=0; $i<$nfiles; $i++) {
				if ($upload->isAllowed($i)) {
					$filename = $upload->getFileName($i);
					if ($upload->moveUploadedFile($i)) {
						$content .= "<p>The file <b>$filename</b> has been uploaded successfully.</p>";
					} else {
						$errmsg = $upload->getFileErrorMsg($i);
						$content .= "<p style=\"color:red;\">An error occured uploading $filename. $errmsg</p>";
					}
				} else {
					$errmsg = $upload->getFileErrorMsg($i);
					$content .= "<p style=\"color:red;\">An error occured. $errmsg</p>";
				}
			}
			
		} else {
		
			$uploadform = new A_Http_UploadForm($upload);
			$uploadform->addHidden('action', 'upload_files');
#			$content .= $uploadform->form('index.php');
			$content .= $uploadform->formOpen() . "<br/>\n";
			if ($request->get('select_path')) {
				$content .= $uploadform->formSelectPath() . "<br/>\n";
			}
			$content .= $uploadform->formInput() . "<br/>\n";
			if ($request->get('multi_file')) {
				$content .= $uploadform->formInput() . "<br/>\n";
			}
			$content .= $uploadform->formSubmit() . "<br/>\n";
			$content .= $uploadform->formClose() . "<br/>\n";
		
		}

		$page_template->set('uploadform', $content);
		$response->setContent($page_template->render());
	}
}

?>