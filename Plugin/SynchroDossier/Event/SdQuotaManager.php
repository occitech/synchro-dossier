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
			'Model.UploadedFile.afterUploadFailed' => 'sendInsufficientQuotaNotification',
			'Model.UploadedFile.afterUploadSuccess' => 'updateCurrentQuota',
			'Model.UploadedFile.afterRemoveData' => 'updateCurrentQuota'
		);
	}

	public function checkUploadAllowed($event) {
		$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');
		$maxSizeKb = $SdInformationModel->remainingQuota() * 1024;

		$messages = array(
			Configure::read('sd.Occitech.roleId') => __d('synchro_dossier', 'Il n\'y a plus de place pour ce dossier'),
			Configure::read('sd.Utilisateur.roleId') => __d('synchro_dossier', 'Désolé, l\'envoie de fichier n\'est actuellement pas disponible.'),
			Configure::read('sd.Admin.roleId') => __d('synchro_dossier', 'Le quota est atteint, vous devez contacter un SuperAdmin, ou supprimer des fichiers.'),
			Configure::read('sd.SuperAdmin.roleId') => __d('synchro_dossier', 'Le quota est atteint, vous devez commander plus de quota ou supprimer des fichiers.')
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

			$originalLang = Configure::read('Config.language');
			$LangModel = ClassRegistry::init('Croogo.Language');

			foreach ($superAdmins as $superAdmin) {
				$to = array($superAdmin['User']['email'] => $superAdmin['Profile']['name'] . ' ' . $superAdmin['Profile']['firstname']);

				Configure::write('Config.language', $LangModel->field(
					'alias',
					array('id' => $superAdmin['Profile']['language_id'])
				));

				$this->cakeEmail->reset();
				$this->cakeEmail
					->template('SynchroDossier.quota_exceeded', 'SynchroDossier.default')
					->emailFormat('both')
					->helpers(array('Uploader.File'))
					->from(Configure::read('sd.mail.quotaExceeded.from'))
					->to($to)
					->subject(__d('synchro_dossier', Configure::read('sd.mail.quotaExceeded.subject')))
					->viewVars(array('user' => $event->data['user'], 'data' => $event->data['data']))
					->send();
			}

			Configure::write('Config.language', $originalLang);
		}
	}

	public function updateCurrentQuota($event) {
		$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
		$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');

		$UploadedFileModel->recursive = -1;
		$files = $UploadedFileModel->find('all');

		$currentQuotaOctect = 0;

		foreach ($files as $file) {
			$currentQuotaOctect += $file['UploadedFile']['size'];
		}

		$currentQuotaMb = $currentQuotaOctect / 1024 / 1024;

		$sdInfo = $SdInformationModel->find('first');
		$SdInformationModel->id = $sdInfo['SdInformation']['id'];
		$SdInformationModel->saveField('current_quota_mb', round($currentQuotaMb, 0));
	}
}
