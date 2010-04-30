<?php

class upload_files_iframe extends A_Controller_Action {
	protected $content;
	protected $template_main;

	function index($locator) {
		$page_template = $this->_load()->template('upload_files_iframe');
		
		$content = '';
		$errmsg = '';
				
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
			$upload->setMaxFilesize(100000);		// cap size of file with this value, zero no cap
			$upload->setAllowedTypes(array());		// set array of allowed file MIME types
		
			for ($i=0; $i<$nfiles; $i++) {
				if ($upload->isAllowed($i)) {
					$filename = $upload->getFileName($i);
					if ($upload->moveUploadedFile($i)) {
						$content .= "<p>The file <b>$filename</b> has been uploaded successfully.</p>";
					} else {
						$errmsg = $upload->getFileErrorMsg($i);
						$content .= "<p style=\"color:red;\">An GOOD error occured uploading $filename. $errmsg</p>";
					}
				} else {
					$errmsg = $upload->getFileErrorMsg($i);
					$content .= "<p style=\"color:red;\">An error occured. $errmsg</p>";
				}
			}
			
		} else {
		
			$uploadform = new A_Http_UploadForm($upload);
			$page_template->set('uploadform', $uploadform);		
			$page_template->set('select_path', $this->_request('select_path'));		
			$page_template->set('multi_file', $this->_request('multi_file'));		
		}

		$page_template->set('content', $content);
		$page_template->set('errmsg', $errmsg);
		$this->_response()->setContent($page_template->render());
	}

	function upload($locator) {
		$upload = new A_Http_Upload();
		
		// destination directory for uploaded file
		$upload->setBasePath('./files/');
		
		// destination directory names for select
		$upload->addPath(1, 'test1/', 'One');
		$upload->addPath(2, 'test2/', 'Two');
		
		$content = '';
		$errmsg = '';
		$filename = '';
		$nfiles = $upload->fileCount();
		if ($nfiles) {
		
			$upload->setReplace(true);		// if destination file exists delete before moving new file
			$upload->setMinFilesize(1);		// set min size of file with this value
			$upload->setMaxFilesize(100000);		// cap size of file with this value, zero no cap
			$upload->setAllowedTypes(array());		// set array of allowed file MIME types
		
			for ($i=0; $i<$nfiles; $i++) {
				if ($upload->isAllowed($i)) {
					$filename = $upload->getFileName($i);
					if ($upload->moveUploadedFile($i)) {
						$content .= "<p>The file <b>$filename</b> has been uploaded successfully.</p>";
					} else {
						$errmsg = $upload->getFileErrorMsg($i);
						$content .= "<p style=\"color:red;\">An BIG error occured uploading $filename. $errmsg</p>";
					}
				} else {
					$errmsg = $upload->getFileErrorMsg($i);
					$content .= "<p style=\"color:red;\">An error occured. $errmsg</p>";
				}
			}
		}
		$size = 555;
		if($filename) { // Upload Successful
#			$details = stat("image_uploads/$name");
#			$size = $details['size'] / 1024;
			echo json_encode(array(
				"success"	=>	true,
				"file_name"	=>	$filename,	// Name of the file - JS should get this value
				"size"		=>	$size,	// Size of the file - JS should get this as well.
			));
		} else { // Upload failed for some reason.
			echo json_encode(array(
				"success"	=>	false,
				"errmsg"	=>	$errmsg,
			));
		}
		exit;
	}

}

