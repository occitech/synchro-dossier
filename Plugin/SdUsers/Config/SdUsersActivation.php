<?php

class SdUsersActivation {

	public function beforeActivation(&$controller) {
		return true;
	}

	public function onActivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->migrate('SdUsers');

		$controller->Croogo->addAco('Users/Users/admin_add', array('SdSuperAdmin'));
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}
	public function onDeactivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->unmigrate('SdUsers');

		$controller->Croogo->removeAco('Users/Users/admin_add', array('SdSuperAdmin'));
	}
}
