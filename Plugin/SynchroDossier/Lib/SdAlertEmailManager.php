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
			'Model.UploadedFile.AfterSharingCreation' => 'subscribeToAlertEmail',
			'Controller.Files.allFilesUploadedInBatch' => 'sendAlertsEmail'
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

	public function sendAlertsEmail($event) {
		$SdAlertEmailModel = ClassRegistry::init('SynchroDossier.SdAlertEmail');

		$usersToAlert = $SdAlertEmailModel->getUserToAlert($event->data['user']['id']);

		if (!empty($usersToAlert['to'])) {
			$this->cakeEmail
				->template('SynchroDossier.alert_email_new_upload', 'SynchroDossier.default')
				->emailFormat('both')
				->helpers(array('Uploader.File'))
				->from(Configure::read('sd.mail.alertEmailNewUpload.from'))
				->to($usersToAlert['to'])
				->subject(Configure::read('sd.mail.alertEmailNewUpload.subject'))
				->viewVars(array('user' => $event->data['user'], 'files' => $usersToAlert['files']))
				->send();
		}
	}
}