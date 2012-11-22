<?php

App::uses('CakeEventListener', 'Event');
App::uses('SdUser', 'SdUsers.Model');
App::uses('CakeEmail', 'Network/Email');

class SdAlertEmailManager implements CakeEventListener {

	public $cakeEmail = null;

	public function __construct() {
		$this->cakeEmail = new CakeEmail();
	}

	public function implementedEvents() {
		return array(
			'Model.UploadedFile.afterUploadSuccess' => 'saveFileForAlertEmail'
		);
	}

	public function saveFileForAlertEmail($event) {
		$SdFileEmailModel = ClassRegistry::init('SynchroDossier.SdFileEmail');

		$data['user_id'] = $event->data['user']['id'];
		$data['uploaded_file_id'] = $event->data['data']['file']['id'];
		$SdFileEmailModel->save($data);
	}
}