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
			'Model.UploadedFile.afterUploadSuccess' => 'saveFileForAlertEmail',
			'Model.UploadedFile.AfterSharingCreation' => 'subscribeToAlertEmail'
		);
	}

	public function saveFileForAlertEmail($event) {
		$SdFileEmailModel = ClassRegistry::init('SynchroDossier.SdFileEmail');

		$data['user_id'] = $event->data['user']['id'];
		$data['uploaded_file_id'] = $event->data['data']['file']['id'];
		$SdFileEmailModel->save($data);
	}

	public function subscribeToAlertEmail($event) {
		if ($event->data['data']['SdAlertEmail']['subscribe'] == 1) {
			$SdAlertEmailModel = ClassRegistry::init('SynchroDossier.SdAlertEmail');
			$data = array(
				'user_id' => $event->data['user']['id'],
				'uploaded_file_id' => $event->data['data']['UploadedFile']['id']
			);

			$SdAlertEmailModel->save($data);
		}
	}
}