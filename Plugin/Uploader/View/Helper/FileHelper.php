<?php

App::uses('Helper', 'View');

class FileHelper extends AppHelper {

	protected $_unit = array('o', 'Ko', 'Mo', 'Go');

/**
 * Return a readable size.
 * If the size is less than 1Ko that return 1Ko
 */
	public function size($size, $unity = 'o') {
		$unit = array_search($unity, $this->_unit);
		while ($size > 1024 && $unit != sizeof($this->_unit) - 1) {
		 	$size /= 1024;
		 	$unit++;
		}
		if ($unit == 0) {
			$unit++;
			$size = 1;
		}
		return round($size, $unit - 1) . ' ' . $this->_unit[$unit];
	}

	public function mimeType($mimeType) {
		$type = explode('/', $mimeType);
		return $type[sizeof($type) - 1];
	}
}