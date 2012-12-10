<?php

App::uses('CakeEventListener', 'Event');
App::uses('UploadedFile', 'Uploader.Model');

class SdModeBoxManager implements CakeEventListener {

	public function implementedEvents() {
		return array(
			'Model.UploadedFile.AfterSharingCreation' => 'createSubDirectories',
		);
	}

	public function createSubDirectories($event) {
		if (Configure::read('sd.config.useModeBox')) {
			$this->__createFolderWithPermissions($event, 'Inbox', 'create', 1);
			$this->__createFolderWithPermissions($event, 'Outbox', 'create', -1);
		}
	}

	private function __createFolderWithPermissions($event, $folderName, $permission, $value) {
		$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
		$PermissionModel = ClassRegistry::init('Permission');

		$parentId = $event->data['data']['UploadedFile']['id'];
		$userId = $event->data['user']['id'];
		$data['UploadedFile']['filename'] = $folderName;

		$UploadedFileModel->addFolder($data, $parentId, $userId);
		$folderId = $UploadedFileModel->id;

		$PermissionModel->allow(
			array('model' => 'Role', 'foreign_key' => Configure::read('sd.Utilisateur.roleId')),
			array('model' => 'UploadedFile', 'foreign_key' => $folderId),
			$permission,
			$value
		);
	}
}