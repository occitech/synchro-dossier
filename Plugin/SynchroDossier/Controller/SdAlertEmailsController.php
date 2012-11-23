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

	/**
	 * Cette méthode est appelée lorsque tous les fichiers ont été
	 * envoyés. (Grâce à un event de Plupload)
	 * cf http://projets.occi-tech.com/projects/sd/wiki/AlertEmail 
	 */
	public function sendEmails() {

		$this->getEventManager()->dispatch(new CakeEvent(
				'Controller.SdAlertEmail.SendAlertsEmail',
				$this,
				array('user' => $this->Auth->user())
		));

		$this->autoRender = false;
	}
}