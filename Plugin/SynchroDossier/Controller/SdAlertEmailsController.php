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

		$this->getEventManager()->dispatch(new CakeEvent(
				'Controller.SdAlertEmail.SendAlertsEmail',
				$this,
				array('user' => $this->Auth->user())
		));

		$this->autoRender = false;
	}
}