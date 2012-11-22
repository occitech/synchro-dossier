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
		$files = $this->SdFileEmail->findAllByUser_id($this->Auth->user('id'));
		if (count($files) > 0) {
			$rootFolderId = $this->_getRootFolderId($files[0]['UploadedFile']['id']);
			$userToAlert = $this->SdAlertEmail->findAllByUploaded_file_id($rootFolderId);

			$to = array();
			foreach ($userToAlert as $user) {
				$to[$user['User']['email']] = $user['User']['username'];
			}

			if (!empty($to)) {
				$this->cakeEmail
					->template('SynchroDossier.alert_email_new_upload', 'SynchroDossier.default')
					->emailFormat('both')
					->helpers(array('Uploader.File'))
					->from(Configure::read('sd.mail.alertEmailNewUpload.from'))
					->to($to)
					->subject(Configure::read('sd.mail.alertEmailNewUpload.subject'))
					->viewVars(array('user' => $files[0]['User'], 'files' => $files))
					->send();

				$this->SdFileEmail->deleteAll(array('SdFileEmail.user_id' => $this->Auth->user('id')));
			}
		}
		die;
	}

	protected function _getRootFolderId($uploadedFileId) {
		$path = $this->UploadedFile->getPath($uploadedFileId);

		return $path[0]['UploadedFile']['id'];
	}
}