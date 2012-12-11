<?php

App::uses('Helper', 'View');

class FileHelper extends AppHelper {

	public $helpers = array('Html');

	protected $_unit = array('o', 'Ko', 'Mo', 'Go');

	protected $_imgMimeType = array(
		'image/gif',
		'image/jpeg',
		'image/png',
		'image/tiff',
	);
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
		if (in_array($UploadedFile['mime_type'], $this->_imgMimeType)) {
			$html .= $this->Html->link(
				'<i class="icon-eye-open"></i>',
				'#',
				array(
					'escape' => false,
					'class' => 'file-preview',
					'rel' => 'popover',
					'data-placement' => 'right',
					'data-original-title' => __('Preview de %s', $UploadedFile['filename']),
					'data-content' => __('Chargement de l\'image'),
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