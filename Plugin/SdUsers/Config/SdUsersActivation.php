<?php

class SdUsersActivation {

	public function beforeActivation(&$controller) {
		return true;
	}

	public function onActivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->migrate('SdUsers');

		$controller->Croogo->addAco('SdUsers/SdUsers/add', array('sdsuperadmin'));

	}

	public function beforeDeactivation(&$controller) {
		return true;
	}
	public function onDeactivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->unmigrate('SdUsers');
		
		$controller->Croogo->addAco('SdUsers/SdUsers/add');
		$controller->Croogo->addAco('SdUsers/Users/add');
	}
}
