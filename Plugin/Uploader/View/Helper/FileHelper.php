<?php

App::uses('Helper', 'View');

class FileHelper extends AppHelper {

	public $helpers = array('Html');

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
		$type = $this->_View->response->getMimeType($mimeType);
		if ($type === false) {
			$type = explode('/', $mimeType);
			$type = $type[sizeof($type) - 1];
		}
		return $type;
	}

	public function preview($UploadedFile) {
		$html = '';
		if (true) {
			$html .= $this->Html->link(
				'<i class="icon-eye-open"></i>',
				'#',
				array(
					'escape' => false,
					'class' => 'file-preview',
					'rel' => 'popover',
					'data-placement' => 'right',
					'data-content' => '',
					'data-original-title' => __('En chargement'),
					'data-preview-url' => $this->Html->url(array(
						'plugin' => 'uploader',
						'controller' =>'files',
						'action' => 'preview',
						$UploadedFile['id']
					))
				)
			);
		}

		return $html;
	}
}