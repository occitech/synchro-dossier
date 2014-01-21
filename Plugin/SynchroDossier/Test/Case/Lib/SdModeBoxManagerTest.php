<?php

App::uses('CroogoTestCase', 'Croogo.TestSuite');
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
		'plugin.uploader.comment',
		'plugin.uploader.taxonomy',
		'plugin.uploader.taxonomies_uploaded_file',
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

		Configure::write('sd.config.useModeBox', false);

		$user['id'] = 3;

		$UploadedFileModel->addSharing($this->data, $user);

		$UploadedFileModel->contain('ChildUploadedFile');
		$result = $UploadedFileModel->findByFilename('lastGreateSharing');

		$this->assertEqual(count($result['ChildUploadedFile']), 0);
	}

	public function testCreateSubDirectories_Rights() {
		$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
		$PermissionModel = ClassRegistry::init('Permission');

		Configure::write('sd.config.useModeBox', true);

		$user['id'] = 3;

		$UploadedFileModel->addSharing($this->data, $user);

		$UploadedFileModel->contain('ChildUploadedFile');
		$result = $UploadedFileModel->findByFilename('lastGreateSharing');

		$inboxId = $result['ChildUploadedFile'][0]['id'];
		$outboxId = $result['ChildUploadedFile'][1]['id'];

		$inboxUserWritePerm = $PermissionModel->check(
			array('model' => 'Role', 'foreign_key' => Configure::read('sd.Utilisateur.roleId')),
			array('model' => 'UploadedFile', 'foreign_key' => $inboxId),
			'create'
		);
		$outboxUserWritePerm = $PermissionModel->check(
			array('model' => 'Role', 'foreign_key' => Configure::read('sd.Utilisateur.roleId')),
			array('model' => 'UploadedFile', 'foreign_key' => $outboxId),
			'create'
		);

		$this->assertTrue($inboxUserWritePerm);
		$this->assertFalse($outboxUserWritePerm);
	}
}
