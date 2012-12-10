<?php

App::uses('CroogoTestCase', 'TestSuite');
App::uses('SdModeBoxManagerTest', 'SynchroDossier.Lib');
App::uses('UploadedFile', 'Uploader.Model');
App::uses('CakeEvent', 'Event');

class SdModeBoxManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.uploader.sd_information',
		'plugin.uploader.user',
		'plugin.uploader.role',
		'plugin.uploader.uploaded_file',
		'plugin.uploader.aco',
		'plugin.uploader.aro',
		'plugin.uploader.aros_aco',
		'plugin.uploader.file_storage',
		'plugin.sd_users.profile',
		'plugin.uploader.comment'
	);

	public function setUp() {
		parent::setUp();
		$this->SdModeBoxManager = ClassRegistry::init('SynchroDossier.SdModeBoxManager');
		$this->data['SdAlertEmail']['subscribe'] = 0;
		$this->data['UploadedFile']['filename'] = 'lastGreateSharing';
	}

	public function tearDown() {
		unset($this->SdModeBoxManager);
		parent::tearDown();
	}

	public function testCreateSubDirectories_ModeBoxActivated() {
		$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		Configure::write('sd.config.useModeBox', true);

		$user['id'] = 3;

		$UploadedFileModel->addSharing($this->data, $user);

		$UploadedFileModel->contain('ChildUploadedFile');
		$result = $UploadedFileModel->findByFilename('lastGreateSharing');

		$this->assertEqual(count($result['ChildUploadedFile']), 2);
		$this->assertEqual($result['ChildUploadedFile'][0]['filename'], 'Inbox');
		$this->assertEqual($result['ChildUploadedFile'][1]['filename'], 'Outbox');
	}

	public function testCreateSubDirectories_ModeBoxDeactivated() {
		$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		Configure::write('sd.config.useModeBox', false);

		$user['id'] = 3;

		$UploadedFileModel->addSharing($this->data, $user);

		$UploadedFileModel->contain('ChildUploadedFile');
		$result = $UploadedFileModel->findByFilename('lastGreateSharing');

		$this->assertEqual(count($result['ChildUploadedFile']), 0);
	}
}