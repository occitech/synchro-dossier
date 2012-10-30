<?php

App::uses('CroogoPlugin', 'Extensions.Lib');

class UploaderActivation {

	public function __construct() {
		$this->CroogoPlugin = new CroogoPlugin();
		
	}

	public function onActivation(&$controller) {
		$this->CroogoPlugin->migrate('Uploader');
		$this->CroogoPlugin->migrate('FileStorage');
	}


	public function onDeactivation(&$controller) {
		$this->CroogoPlugin->unmigrate('Uploader');
		$this->CroogoPlugin->unmigrate('FileStorage');
	}

	public function beforeActivation(&$controller) {
		return $this->CroogoPlugin->activate('FileStorage');
	}

	public function beforeDeactivation(&$controller) {
		return $this->CroogoPlugin->deactivate('FileStorage');
	}
}
