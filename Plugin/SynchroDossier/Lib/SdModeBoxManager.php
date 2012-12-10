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
			
			$parentId = $event->data['data']['UploadedFile']['id'];
			$userId = $event->data['user']['id'];

			$data['UploadedFile']['filename'] = 'Inbox';
			$UploadedFileModel->addFolder($data, $parentId, $userId);

			$data['UploadedFile']['filename'] = 'Outbox';
			$UploadedFileModel->addFolder($data, $parentId, $userId);
		}
	}
}