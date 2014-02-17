<?php

App::uses('CroogoPlugin', 'Extensions.Lib');

class UploaderActivation {

	private $__CroogoPlugin;

	public function __construct() {
		$this->__CroogoPlugin = new CroogoPlugin();
	}

	public function onActivation(&$controller) {
		$this->__CroogoPlugin->migrate('FileStorage');
		$this->__CroogoPlugin->migrate('Uploader');
		$this->__addRootUploadedFileAco();
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

	private function __addRootUploadedFileAco() {
		$Aco = ClassRegistry::init('Aco');
		$data = array(
			'parent_id' => null,
			'foreign_key' => null,
			'model' => null,
			'alias' => 'uploadedFileAco' // Todo : Use Configure::read('sd.uploadedFileRootAco.alias')
		);
		$Aco->save($data);
	}
}
