<?php

App::uses('CroogoTestCase', 'Croogo.TestSuite');
App::uses('SdQuotaManager', 'SynchroDossier.Event');
App::uses('CakeEvent', 'Event');

class SdQuotaManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.synchro_dossier.synchro_dossier_sd_information',
		'plugin.uploader.uploader_user',
		'plugin.uploader.uploader_role',
		'plugin.sd_users.profile',
		'plugin.sd_users.sd_users_language',
		'plugin.uploader.uploaded_file',
		'plugin.settings.setting',
	);

	public function setUp() {
		parent::setUp();
		$this->SdQuotaManager = ClassRegistry::init('SynchroDossier.SdQuotaManager');
	}

	public function tearDown() {
		unset($this->SdQuotaManager);
		parent::tearDown();
	}

	public function testSendInsufficientQuotaNotification_NoMailSend_WhenUserIsSuperAdmin() {
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

		$this->SdQuotaManager->sendInsufficientQuotaNotification($event);
	}

	public function testSendInsufficientQuotaNotification_MailSendToSuperAdminWhenOnlyOne() {
		$precondition = ClassRegistry::init('User')->updateAll(
			array('User.role_id' => 5),
			array('User.id !=' => 5)
		);
		$this->assertTrue($precondition);

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
		$this->SdQuotaManager->cakeEmail
			->expects($this->once())
			->method('to')
			->with($this->equalTo(array(
				'spadm2@spadm2.com' => 'spadm2 '
			)));
		$this->SdQuotaManager->cakeEmail
			->expects($this->once())
			->method('subject')
			->with($this->equalTo('Synchro-Dossier - Quota dépassé'));

		$event->data['user']['role_id'] = 5;
		$event->data['user']['email'] = 'coucou@coucou.fr';
		$event->data['data']['file']['size'] = 150;
		$event->data['data']['file']['name'] = 'coucou';
		$this->SdQuotaManager->sendInsufficientQuotaNotification($event);
	}

	public function testSendInsufficientQuotaNotification_MailSendToEachSuperAdmin() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$methods = array('template', 'emailFormat', 'helpers', 'from', 'to', 'subject', 'viewVars', 'send');

		$this->SdQuotaManager->cakeEmail = $this->getMockBuilder('CakeEmail')
			->disableOriginalConstructor()
			->getMock('CakeEmail', $methods);

		foreach ($methods as $method) {
			$this->SdQuotaManager->cakeEmail
				->expects($this->exactly(2))
				->method($method)
				->will($this->returnSelf());
		}

		$event->data['user']['role_id'] = 5;
		$event->data['user']['email'] = 'coucou@coucou.fr';
		$event->data['data']['file']['size'] = 150;
		$event->data['data']['file']['name'] = 'coucou';
		$this->SdQuotaManager->sendInsufficientQuotaNotification($event);
	}

	public function testSendInsufficientQuotaNotification_MailSendToFrenchSuperAdminIsInFrench() {
		$precondition = ClassRegistry::init('User')->updateAll(
			array('User.role_id' => 5),
			array('User.id !=' => 3)
		);
		$this->assertTrue($precondition);

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

		$this->SdQuotaManager->cakeEmail
			->expects($this->once())
			->method('subject')
			->with($this->equalTo('Synchro-Dossier - Quota dépassé'));

		$event->data['user']['role_id'] = 5;
		$event->data['user']['email'] = 'coucou@coucou.fr';
		$event->data['data']['file']['size'] = 150;
		$event->data['data']['file']['name'] = 'coucou';
		$this->SdQuotaManager->sendInsufficientQuotaNotification($event);
	}

	public function testUpdateCurrentQuota() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$SdInformationModel = ClassRegistry::init('SynchroDossier.SdInformation');

		$return = $this->SdQuotaManager->updateCurrentQuota($event);

		$sdInfo = $SdInformationModel->find('first');
		$result = $sdInfo['SdInformation']['current_quota_mb'];

		$this->assertNull($return);
		$this->assertEqual($result, 131);
	}
}
