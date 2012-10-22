<?php

class SdUsersActivation {

	private $__usersAcos = array(
		'Users/Users/admin_add',
		'Users/Users/admin_index'
	);

	private $__filesAcos = array(
		'Uploader/Files/browse',
		'Uploader/Files/upload',
		'Uploader/Files/download',
		'Uploader/Files/rename',
		'Uploader/Files/createFolder',
		'Uploader/Files/downloadZipFolder'
	);

	private $__sdRoles = array('sdSuperAdmin', 'sdUtilisateur', 'sdAdmin');

	public function beforeActivation(&$controller) {
		return true;
	}

	public function onActivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->migrate('SdUsers');

		foreach ($this->__usersAcos as $aco) {
			$controller->Croogo->removeAco($aco);
			$controller->Croogo->addAco($aco, array('sdSuperAdmin'));
		}

		foreach ($this->__filesAcos as $aco) {
			$controller->Croogo->addAco($aco, $this->__sdRoles);
		}
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}

	public function onDeactivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->unmigrate('SdUsers');

		$allAcos = array_merge($this->__usersAcos, $this->__filesAcos);
		foreach ($allAcos as $aco) {
			$controller->Croogo->removeAco($aco);
		}
	}
}
