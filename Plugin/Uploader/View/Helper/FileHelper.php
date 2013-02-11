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

	public function iconPreview($UploadedFile) {
		$html = '';
		if (in_array($UploadedFile['mime_type'], $this->_imgMimeType)) {
			$html .= $this->Html->link(
				'<i class="icon-eye-open"></i>',
				'#',
				array(
					'escape' => false,
					'class' => 'file-preview',
					'html' => true,
					'data-placement' => 'right',
					'data-original-title' => __d('uploader', 'Chargement en cours de l\'image. Merci de patienter ...'),
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

	public function preview($content, $mimeType) {
		$html = '';
		if (in_array($mimeType, $this->_imgMimeType)) {
			$html .= $this->__imgPreview($content, $mimeType);
		} else {
			$html .= __d('uploader', 'Pas de prévisualisation possible pour ce document.');
		}
		return $html;
	}

	private function __imgPreview($content, $mimeType) {
		$base64 = base64_encode($content);
		$imgSrc = 'data:' . $mimeType . ';base64,' . $base64;
		return '<img src="' . $imgSrc . '">';
	}
}