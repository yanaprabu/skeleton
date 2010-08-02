<?php
/**
 * 
 */
class A_File_Info {
	protected $info;
	
	public function __construct($path) {
		$this->init($path);
	}

	public function init($path) {
		if (!isset($this->info) && $path) {
			$this->info = stat($path);
		}
	}

	public function size($power='B') {
		if (isset($this->info['size'])) {
			$powers = array(
				'B' => 1,
				'K' => 1024,
				'M' => 1048576,
				'G' => 1073741824,
				);
			$div = isset($powers[$power]) ? $powers[$power] : 1;
			return $this->info['size'] / $div;
		}
	}

}