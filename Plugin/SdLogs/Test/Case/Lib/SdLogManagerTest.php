<?php

App::uses('CroogoTestCase', 'Croogo.TestSuite');
App::uses('SdLogManager', 'SdLogs.Lib');
App::uses('SdLog', 'SdLogs.SdLog');
App::uses('CakeEvent', 'Event');

class SdLogManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.uploader.uploader_sd_information',
		'plugin.uploader.uploader_user',
		'plugin.uploader.uploader_role',
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
