<?php

App::uses('CakeEventListener', 'Event');
App::uses('SdUser', 'SdUsers.Model');
App::uses('CakeEmail', 'Network/Email');

class SdQuotaManager implements CakeEventListener {

	public $cakeEmail = null;

	public function __construct() {
		$this->cakeEmail = new CakeEmail();
	}

	public function implementedEvents() {
		return array(
			'Model.UploadedFile.beforeUpload' => 'checkUploadAllowed',
			'Model.UploadedFile.afterUploadFailed' => 'sendInsufficientQuotaNotification'
		);
	}

	public function checkUploadAllowed($event) {
		$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');
		$maxSizeKb = $SdInformationModel->remainingQuota() * 1024;

		$messages = array(
			Configure::read('sd.Occitech.roleId') => __('Il n\'y a plus de place pour ce dossier'),
			Configure::read('sd.Utilisateur.roleId') => __('Désolé, l\'envoie de fichier n\'est actuellement pas disponible.'),
			Configure::read('sd.Admin.roleId') => __('Le quota est atteint, vous devez contacter un SuperAdmin, ou supprimer des fichiers.'),
			Configure::read('sd.SuperAdmin.roleId') => __('Le quota est atteint, vous devez commander plus de quota ou supprimer des fichiers.')
		);

		$event->result['hasError'] = false;
		$filesizeKb = $event->data['data']['file']['size'] / 1024;
		if ($maxSizeKb < $filesizeKb) {
			$event->result['hasError'] = true;
			$event->result['message'] = $messages[$event->data['user']['role_id']];
		}
	}

	public function sendInsufficientQuotaNotification($event) {
		if ($event->data['user']['role_id'] != Configure::read('sd.SuperAdmin.roleId')) {
			$UserModel = ClassRegistry::init('SdUsers.SdUser');
			$superAdmins = $UserModel->find('superAdmin');

			$to = array();
			foreach ($superAdmins as $superAdmin) {
				$to[$superAdmin['User']['email']] = $superAdmin['Profile']['name'] . ' ' . $superAdmin['Profile']['firstname'];
			}

			$this->cakeEmail
				->template('SynchroDossier.quota_exceeded', 'SynchroDossier.default')
				->emailFormat('both')
				->helpers(array('Uploader.File'))
				->from(Configure::read('sd.mail.quotaExceeded.from'))
				->to($to)
				->subject(Configure::read('sd.mail.quotaExceeded.subject'))
				->viewVars(array('user' => $event->data['user'], 'data' => $event->data['data']))
				->send();
		}
	}
}