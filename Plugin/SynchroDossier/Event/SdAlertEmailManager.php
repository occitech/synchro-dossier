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
			'Controller.Files.allFilesUploadedInBatch' => 'sendAlertsEmail',
			'Controller.FilesController.newUserRight' => 'sendInvitedOnFolderEmail',
			'Controller.FilesController.toggleEmailSubscription' => 'toggleSubscriptionToAlertEmail',
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

	public function toggleSubscriptionToAlertEmail($event) {
		$SdAlertEmailModel = ClassRegistry::init('SynchroDossier.SdAlertEmail');
		$SdAlertEmailModel->toggleEmailAlert($event);
	}

	public function sendAlertsEmail($event) {
		$SdAlertEmailModel = ClassRegistry::init('SynchroDossier.SdAlertEmail');
		$SdUserModel = ClassRegistry::init('SdUsers.SdUser');

		$usersToAlert = $SdAlertEmailModel->getUserToAlert($event->data['user']['id']);
		$userProfile = $SdUserModel->find('first', array(
			'conditions' => array('User.id' => $event->data['user']['id']),
			'noRoleChecking' => true
		));
		if (!empty($usersToAlert['to'])) {
			foreach((array) $usersToAlert['to'] as $receiver) {
				$this->cakeEmail->reset();
				$this->cakeEmail
					->config('default')
					->template('SynchroDossier.alert_email_new_upload', 'SynchroDossier.default')
					->theme(Configure::read('Site.theme'))
					->emailFormat('both')
					->helpers(array('Uploader.File', 'Html'))
					->from(Configure::read('sd.mail.alertEmailNewUpload.from'))
					->to($receiver['email'])
					->subject(__d('synchro_dossier', Configure::read('sd.mail.alertEmailNewUpload.subject')))
					->viewVars(array('receiver' => $receiver, 'uploader' => $event->data['user'], 'profile' => $userProfile['Profile'], 'files' => $usersToAlert['files'], 'rootFolder' => $usersToAlert['rootFolder']))
					->send();
			}
		}
	}

	public function sendInvitedOnFolderEmail($event) {
		$SdUserModel = ClassRegistry::init('SdUsers.SdUser');
		$user = $SdUserModel->find('first', array(
			'conditions' => array('User.id' => $event->data['user']['id']),
			'recursive' => -1,
			'contain' => array('Profile')
		));
		$username = $user['Profile']['firstname'] . ' ' . $user['Profile']['name'];
		$to = array($user['User']['email'] => $username);

		$this->cakeEmail->reset();
		$this->cakeEmail
			->config('default')
			->template('SynchroDossier.alert_email_folder_invitation', 'SynchroDossier.default')
			->theme(Configure::read('Site.theme'))
			->emailFormat('both')
			->from(Configure::read('sd.mail.alertEmailNewUpload.from'))
			->to($to)
			->subject(__d('synchro_dossier', Configure::read('sd.mail.alertEmailNewUpload.subject')))
			->viewVars(array('folder' => $event->data['folder']))
			->send();
	}
}
