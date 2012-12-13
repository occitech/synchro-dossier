<?php

App::uses('CroogoPlugin', 'Extensions.Lib');

class SdLogsActivation {

	private $__CroogoPlugin;

	public function __construct() {
		$this->__CroogoPlugin = new CroogoPlugin();
	}

	public function beforeActivation(&$controller) {
		return true;
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}

	public function onActivation(&$controller) {
		return $this->__CroogoPlugin->migrate('SdLogs');
	}

	public function onDeactivation(&$controller) {
		$this->__CroogoPlugin->unmigrate('SdLogs');
	}
}
