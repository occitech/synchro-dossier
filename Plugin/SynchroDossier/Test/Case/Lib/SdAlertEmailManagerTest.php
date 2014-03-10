<?php

App::uses('CroogoTestCase', 'Croogo.TestSuite');
App::uses('SdAlertEmailManager', 'SynchroDossier.Lib');
App::uses('CakeEvent', 'Event');

class SdAlertEmailManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.settings.setting',
		'plugin.uploader.uploader_user',
		'plugin.uploader.uploader_role',
		'plugin.sd_users.profile',
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

		$methods = array('template', 'emailFormat', 'from', 'to', 'subject', 'viewVars', 'send');

		$this->SdAlertEmailManager->cakeEmail = $this->getMockBuilder('CakeEmail')
			->disableOriginalConstructor()
			->getMock('CakeEmail', $methods);

		foreach ($methods as $method) {
			$this->SdAlertEmailManager->cakeEmail
				->expects($this->once())
				->method($method)
				->will($this->returnSelf());
		}

		$event->data['user']['id'] = 4;

		$this->SdAlertEmailManager->sendInvitedOnFolderEmail($event);
	}

	public function testGetMailWhenInvitedOnFolder_IsSendToConcernedPerson() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$methods = array('template', 'emailFormat', 'from', 'to', 'subject', 'viewVars', 'send');

		$this->SdAlertEmailManager->cakeEmail = $this->getMockBuilder('CakeEmail')
			->disableOriginalConstructor()
			->getMock('CakeEmail', $methods);

		foreach ($methods as $method) {
			$this->SdAlertEmailManager->cakeEmail
				->expects($this->once())
				->method($method)
				->will($this->returnSelf());
		}

		$this->SdAlertEmailManager->cakeEmail
			->expects($this->once())
			->method('to')
			->with($this->equalTo(array(
				'toto@derbois.com' => 'tata toto'
			)));

		$event->data['user']['id'] = 4;

		$this->SdAlertEmailManager->sendInvitedOnFolderEmail($event);
	}
}
