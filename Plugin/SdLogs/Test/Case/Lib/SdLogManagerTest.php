<?php

App::uses('CroogoTestCase', 'TestSuite');
App::uses('SdLogManager', 'SdLogs.Lib');
App::uses('SdLog', 'SdLogs.SdLog');
App::uses('CakeEvent', 'Event');

class SdLogManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.uploader.sd_information',
		'plugin.uploader.user',
		'plugin.uploader.role',
		'plugin.sd_users.profile',
		'plugin.sd_logs.sd_log',
	);

	public function setUp() {
		parent::setUp();
		$this->SdLogs = ClassRegistry::init('SdLogs.SdLogManager');
	}

	public function tearDown() {
		unset($this->SdLogs);
		parent::tearDown();
	}

	public function testAfterUpladSuccess_LogAdded() {
		$SdLogModel = ClassRegistry::init('SdLogs.SdLog');

		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock('CakeEvent', array('subject'));
		$event->expects($this->any())
			->method(('subject'))
			->will($this->returnValue(new Model()));

		$event->data['user']['id'] = 1;

		$nbLogBefore = $SdLogModel->find('count');

		$this->SdLogs->afterUploadSuccess($event);

		$nbLogAfter = $SdLogModel->find('count');

		$this->assertEqual($nbLogAfter, $nbLogBefore + 1);
	}
}