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
		$controller->Croogo->addAco('Users/Users/admin_index', array('SdSuperAdmin'));
		$controller->Croogo->addAco('Users/Users/admin_edit', array('SdSuperAdmin'));

		$controller->Croogo->addAco('Uploader/Files/browse', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->addAco('Uploader/Files/upload', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->addAco('Uploader/Files/download', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->addAco('Uploader/Files/rename', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->addAco('Uploader/Files/createFolder', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->addAco('Uploader/Files/downloadZipFolder', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}
	public function onDeactivation(&$controller) {
		App::uses('CroogoPlugin', 'Extensions.Lib');
		$CroogoPlugin = new CroogoPlugin();
		$CroogoPlugin->unmigrate('SdUsers');

		$controller->Croogo->removeAco('Users/Users/admin_add', array('SdSuperAdmin'));
		$controller->Croogo->removeAco('Users/Users/admin_index', array('SdSuperAdmin'));
		$controller->Croogo->removeAco('Users/Users/admin_edit', array('SdSuperAdmin'));

		$controller->Croogo->removeAco('Uploader/Files/browse', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->removeAco('Uploader/Files/upload', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->removeAco('Uploader/Files/download', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->removeAco('Uploader/Files/rename', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->removeAco('Uploader/Files/createFolder', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
		$controller->Croogo->removeAco('Uploader/Files/downloadZipFolder', array('SdSuperAdmin', 'sdUtilisateur', 'sdAdmin'));
	}
}
