<?php

App::uses('CroogoTestCase', 'TestSuite');
App::uses('SdQuotaManager', 'SynchroDossier.Lib');
App::uses('CakeEvent', 'Event');

class SdQuotaManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.uploader.sd_information'
	);

	public function setUp() {
		parent::setUp();
		$this->SdQuotaManager = ClassRegistry::init('SynchroDossier.SdQuotaManager');
	}

	public function tearDown() {
		unset($this->SdQuotaManager);
		parent::tearDown();
	}

	public function testBeforeUpload_NoError() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$event->data['data']['FileStorage']['file']['size'] = 100;
		$event->data['user']['role_id'] = 4;

		$this->SdQuotaManager->beforeUpload(&$event);

		$this->assertFalse($event->result['hasError']);
	}

	public function testBeforeUpload_QuotaExceeded() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$event->data['data']['FileStorage']['file']['size'] = 100000000;
		$event->data['user']['role_id'] = 4;

		$this->SdQuotaManager->beforeUpload(&$event);

		$this->assertTrue($event->result['hasError']);
	}

}