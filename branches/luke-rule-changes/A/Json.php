<?php
/**
 * JSON encoding and decoding 
 * 
 * @package A_Json 
 */

class A_Json {
	protected $encoded = null;  // holds passed or last encoded JSON strings
	protected $decoded = null;  // holds passed or last decoded PHP object or array

	public function __construct($mixed = null) {
		if (is_string($mixed) && (substr($mixed, 0, 1) == '{')) {
			$this->encoded = $mixed;	// var is JSON string
		} else {
			$this->decoded = $mixed;	// var is PHP var
		}
	}

	public function encode($mixed) {
		$this->encoded = json_encode(isset($mixed) ? $mixed : $this->decoded);
		return $this->encoded;
	}

	public function decode($mixed=null, $array=false) {
		$this->decoded = json_decode(isset($mixed) ? $mixed : $this->encoded, $array);
		return $this->decoded;
	}

	public function decodeInto($into, $mixed=null, $intersect=true) {
		$array = json_decode(isset($mixed) ? $mixed : $this->encoded, true);
		if (is_array($into)) {
			if ($intersect) {
				$keys = array_intersect(array_keys($into), array_keys($array));
			} else {
				$keys = array_keys($array);
			}
			foreach ($keys as $key) {
				$into[$key] = $array[$key];
			}
		} elseif (is_object($into)) {
			if ($intersect) {
				$keys = array_intersect(get_object_vars($into), array_keys($array));
			} else {
				$keys = array_keys($array);
			}
			foreach ($keys as $key) {
				$into->$key = $array[$key];
			}
		}
		
		return $into;
	}

}