<?php

App::uses('CroogoPlugin', 'Extensions.Lib');

class UploaderActivation {

	private $__CroogoPlugin;

	public function __construct() {
		$this->__CroogoPlugin = new CroogoPlugin();
	}

	public function onActivation(&$controller) {
		$this->__CroogoPlugin->migrate('Uploader');
		$this->__CroogoPlugin->migrate('FileStorage');
	}


	public function onDeactivation(&$controller) {
		$this->__CroogoPlugin->unmigrate('Uploader');
		$this->__CroogoPlugin->unmigrate('FileStorage');
	}

	public function beforeActivation(&$controller) {
		return $this->__CroogoPlugin->activate('FileStorage');
	}

	public function beforeDeactivation(&$controller) {
		return $this->__CroogoPlugin->deactivate('FileStorage');
	}
}
