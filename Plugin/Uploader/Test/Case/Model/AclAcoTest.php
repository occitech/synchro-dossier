<?php
App::uses('AclAco', 'Uploader.Model');

/**
 * Aco Test Case
 *
 */
class AclAcoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.uploader.uploaded_file',
		'plugin.uploader.file_storage',
		'plugin.uploader.user',
		'plugin.uploader.role',
		'plugin.uploader.aros_aco',
		'plugin.uploader.aco',
		'plugin.uploader.aro'
	);

	public function setUp() {
		parent::setUp();
		$this->Aco = ClassRegistry::init('Uploader.AclAco');
	}

	public function tearDown() {
		unset($this->Aco);

		parent::tearDown();
	}

	public function testGetRightsHasAros() {
		$result = $this->Aco->getRights('UploadedFile', 1);

		$this->assertTrue(!empty($result['Aro']));
		$this->assertEqual($result['Aro'][0]['alias'], 'aymeric');
	}

	public function testGetRightsHasNotAro() {
		$result = $this->Aco->getRights('UploadedFile', 3);

		$this->assertTrue(empty($result['Aro']));
	}
}
