<?php

App::uses('CroogoTestCase', 'TestSuite');
App::uses('SdQuotaManager', 'SynchroDossier.Lib');
App::uses('CakeEvent', 'Event');

class SdQuotaManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.synchro_dossier.sd_information',
		'plugin.uploader.user',
		'plugin.uploader.role',
		'plugin.sd_users.profile',
		'plugin.uploader.uploaded_file',
	);

	public function setUp() {
		parent::setUp();
		$this->SdQuotaManager = ClassRegistry::init('SynchroDossier.SdQuotaManager');
	}

	public function tearDown() {
		unset($this->SdQuotaManager);
		parent::tearDown();
	}

	public function testCheckUploadAllowed_NoError() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$event->data['data']['file']['size'] = 100;
		$event->data['user']['role_id'] = 4;

		$this->SdQuotaManager->checkUploadAllowed(&$event);

		$this->assertFalse($event->result['hasError']);
	}

	public function testCheckUploadAllowed_QuotaExceeded() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$event->data['data']['file']['size'] = 1000000000000;
		$event->data['user']['role_id'] = 4;

		$this->SdQuotaManager->checkUploadAllowed(&$event);

		$this->assertTrue($event->result['hasError']);
	}

	public function testSendInsufficientQuotaNotification_NoMailSend() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$methods = array('template', 'emailFormat', 'helpers', 'from', 'to', 'subject', 'viewVars', 'send');

		$this->SdQuotaManager->cakeEmail = $this->getMockBuilder('CakeEmail')
			->disableOriginalConstructor()
			->getMock('CakeEmail', $methods);

		foreach ($methods as $method) {
			$this->SdQuotaManager->cakeEmail
				->expects($this->never())
				->method($method)
				->will($this->returnSelf());
		}

		$event->data['user']['role_id'] = 4;

		$this->SdQuotaManager->sendInsufficientQuotaNotification(&$event);
	}

	public function testSendInsufficientQuotaNotification_MailSend() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$methods = array('template', 'emailFormat', 'helpers', 'from', 'to', 'subject', 'viewVars', 'send');

		$this->SdQuotaManager->cakeEmail = $this->getMockBuilder('CakeEmail')
			->disableOriginalConstructor()
			->getMock('CakeEmail', $methods);

		foreach ($methods as $method) {
			$this->SdQuotaManager->cakeEmail
				->expects($this->once())
				->method($method)
				->will($this->returnSelf());
		}

		$event->data['user']['role_id'] = 5;
		$event->data['user']['email'] = 'coucou@coucou.fr';
		$event->data['data']['file']['size'] = 150;
		$event->data['data']['file']['name'] = 'coucou';
		$this->SdQuotaManager->sendInsufficientQuotaNotification(&$event);
	}

	public function testUpdateCurrentQuota() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');

		$return = $this->SdQuotaManager->updateCurrentQuota(&$event);

		$sdInfo = $SdInformationModel->find('first');
		$result = $sdInfo['SdInformation']['current_quota_mb'];

		$this->assertNull($return);
		$this->assertEqual($result, 131);
	}
}