<?php

App::uses('CroogoTestCase', 'Croogo.TestSuite');
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

	public function testAfterChangeRight_LogAdded() {
		$this->markTestIncomplete('Test me');
	}
}
