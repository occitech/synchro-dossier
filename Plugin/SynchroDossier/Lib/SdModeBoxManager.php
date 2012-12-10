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
			$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
			$PermissionModel = ClassRegistry::init('Permission');

			$parentId = $event->data['data']['UploadedFile']['id'];
			$userId = $event->data['user']['id'];

			$data['UploadedFile']['filename'] = 'Inbox';
			$UploadedFileModel->addFolder($data, $parentId, $userId);
			$inboxId = $UploadedFileModel->id;

			$data['UploadedFile']['filename'] = 'Outbox';
			$UploadedFileModel->addFolder($data, $parentId, $userId);
			$outboxId = $UploadedFileModel->id;

			$PermissionModel->allow(
				array('model' => 'Role', 'foreign_key' => Configure::read('sd.Utilisateur.roleId')),
				array('model' => 'UploadedFile', 'foreign_key' => $inboxId),
				'create',
				1
			);

			$PermissionModel->allow(
				array('model' => 'Role', 'foreign_key' => Configure::read('sd.Utilisateur.roleId')),
				array('model' => 'UploadedFile', 'foreign_key' => $outboxId),
				'create',
				-1
			);
		}
	}
}