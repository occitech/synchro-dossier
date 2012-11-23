<?php

App::uses('CakeEmail', 'Network/Email');

class SdAlertEmailsController extends SynchroDossierAppController {

	public $uses = array(
		'SynchroDossier.SdAlertEmail',
		'SynchroDossier.SdFileEmail',
		'Uploader.UploadedFile'
	);

	public $cakeEmail = null;

	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);

		$this->cakeEmail = new CakeEmail();
	}

	public function sendEmails() {
		$usersToAlert = $this->SdAlertEmail->getUserToAlert($this->Auth->user('id'));

		if (!empty($usersToAlert['to'])) {
			$this->cakeEmail
				->template('SynchroDossier.alert_email_new_upload', 'SynchroDossier.default')
				->emailFormat('both')
				->helpers(array('Uploader.File'))
				->from(Configure::read('sd.mail.alertEmailNewUpload.from'))
				->to($usersToAlert['to'])
				->subject(Configure::read('sd.mail.alertEmailNewUpload.subject'))
				->viewVars(array('user' => $this->Auth->user(), 'files' => $usersToAlert['files']))
				->send();
		}

		$this->autoRender = false;
	}
}