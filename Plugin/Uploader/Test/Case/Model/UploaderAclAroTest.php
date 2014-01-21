<?php
App::uses('AclAro', 'Uploader.Model');

/**
 * Aro Test Case
 *
 */
class UploaderAclAroTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.uploader.uploaded_file',
		'plugin.uploader.file_storage',
		'plugin.uploader.uploader_user',
		'plugin.uploader.uploader_role',
		'plugin.uploader.roles_user',
		'plugin.uploader.uploader_aros_aco',
		'plugin.uploader.uploader_aco',
		'plugin.uploader.uploader_aro'
	);

	public function setUp() {
		Configure::write('Acl.database', 'test');
		parent::setUp();
		$this->Aro = ClassRegistry::init('Uploader.UploaderAclAro');
	}

	public function tearDown() {
		unset($this->Aro);

		parent::tearDown();
	}

	public function testGetUserNotInFolder() {
		$result = $this->Aro->getUserNotInFolder(1);

		$expected = array('4' => 'toto');

		$this->assertEqual($result, $expected);
	}

}
