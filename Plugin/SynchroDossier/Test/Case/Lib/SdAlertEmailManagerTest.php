<?php

App::uses('CroogoTestCase', 'TestSuite');
App::uses('SdQuotaManager', 'SynchroDossier.Lib');
App::uses('CakeEvent', 'Event');

class SdAlertEmailManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.uploader.sd_information',
		'plugin.uploader.user',
		'plugin.uploader.role',
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
}