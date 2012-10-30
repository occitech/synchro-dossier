<?php

class UploaderActivation {

	public function onActivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->migrate('Uploader');

		if (!CakePlugin::loaded('FileStorage')){
			CakePlugin::load('FileStorage');
		}
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->migrate('FileStorage');
	}


	public function onDeactivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->unmigrate('Uploader');

		if (!CakePlugin::loaded('FileStorage')){
			CakePlugin::load('FileStorage');
		}
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->unmigrate('FileStorage');
	}

	public function beforeActivation(&$controller) {
		return true;
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}
}
