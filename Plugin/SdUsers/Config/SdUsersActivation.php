<?php

class SdUsersActivation {

	private $__usersCRUDAcos = array(
		'SdUsers/SdUsers/admin_index',
		'SdUsers/SdUsers/admin_edit',
		'SdUsers/SdUsers/admin_add',
		'SdUsers/SdUsers/admin_delete',
		'Users/Users/admin_logout'
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

		foreach ($this->__usersCRUDAcos as $aco) {
			$controller->Croogo->removeAco($aco);
			$controller->Croogo->addAco($aco, array('sdSuperAdmin', 'sdAdmin'));
		}

		foreach ($this->__filesAcos as $aco) {
			$controller->Croogo->addAco($aco, $this->__sdRoles);
		}

		$controller->Croogo->addAco('Users/Users/logout', $this->__sdRoles);		
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}

	public function onDeactivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->unmigrate('SdUsers');

		$allAcos = array_merge($this->__usersCRUDAcos, $this->__filesAcos);
		foreach ($allAcos as $aco) {
			$controller->Croogo->removeAco($aco);
		}
	}
}
