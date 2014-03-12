<?php

App::uses('CroogoTestCase', 'Croogo.TestSuite');
App::uses('SdAlertEmailManager', 'SynchroDossier.Event');
App::uses('CakeEvent', 'Event');

class SdAlertEmailManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.settings.setting',
		'plugin.uploader.uploader_user',
		'plugin.uploader.uploader_role',
		'plugin.sd_users.profile',
		'plugin.synchro_dossier.sd_alert_email',
	);

	public function setUp() {
		parent::setUp();
		$this->SdAlertEmailManager = ClassRegistry::init('SynchroDossier.SdAlertEmailManager');
	}

	public function tearDown() {
		unset($this->SdAlertEmailManager);
		parent::tearDown();
	}

	public function testSendMailWhenInvitedOnFolder() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$this->__mockCakeEmailAndCheckSendCount(1);

		$event->data['user']['id'] = 4;
		$event->data['folder']['id'] = '1';
		$event->data['folder']['name'] = 'A super folder';

		$this->SdAlertEmailManager->sendInvitedOnFolderEmail($event);
	}

	public function testGetMailWhenInvitedOnFolder_IsSendToConcernedPerson() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$this->__mockCakeEmailAndCheckSendCount(1);

		$this->SdAlertEmailManager->cakeEmail
			->expects($this->once())
			->method('to')
			->with($this->equalTo(array(
				'toto@derbois.com' => 'tata toto'
			)));

		$event->data['user']['id'] = 4;
		$event->data['folder']['id'] = '1';
		$event->data['folder']['name'] = 'A super folder';

		$this->SdAlertEmailManager->sendInvitedOnFolderEmail($event);
	}

	private function __mockCakeEmailAndCheckSendCount($numberOfEmailToBeSend) {
		$methods = array('template', 'theme', 'emailFormat', 'from', 'to', 'subject', 'viewVars', 'send');

		$this->SdAlertEmailManager->cakeEmail = $this->getMockBuilder('CakeEmail')
			->disableOriginalConstructor()
			->getMock('CakeEmail', array('to'));

		foreach ($methods as $method) {
			$this->SdAlertEmailManager->cakeEmail
				->expects($this->exactly($numberOfEmailToBeSend))
				->method($method)
				->will($this->returnSelf());
		}
	}

}
