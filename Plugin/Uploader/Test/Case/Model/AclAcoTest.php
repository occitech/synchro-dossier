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

	public function testGetRights_HasAros() {
		$result = $this->Aco->getRights('UploadedFile', 1);

		$this->assertTrue(!empty($result['Aro']));
		$this->assertEqual($result['Aro'][0]['alias'], 'admin');
	}

	public function testGetRights_HasNotAro() {
		$result = $this->Aco->getRights('UploadedFile', 3);

		$this->assertTrue(empty($result['Aro']));
	}

/**
 * test can()
 */
	public function testUserCanChangeRight_UserCan_UserIsAdmin_UserIsCreatorOfFolder() {
		$userData = array(
			'id' => 2,
			'role_id' => 5
		);
		$folderCreatorId = $userData['id'];
		$aroRoleId = 5;
		$aroUserId = 7;

		$result = $this->Aco->can(
			$userData,
			'canChangeRight',
			array($aroUserId, $aroRoleId, $folderCreatorId)
		);

		$this->assertTrue($result);
	}

	public function testUserCanChangeRight_UserCannot_UserIsAdmin_UserIsNotCreatorOfFolder() {
		$userData = array(
			'id' => 2,
			'role_id' => 5
		);
		$folderCreatorId = 3;
		$aroRoleId = 5;
		$aroUserId = 7;

		$result = $this->Aco->can(
			$userData,
			'canChangeRight',
			array($aroUserId, $aroRoleId, $folderCreatorId)
		);

		
		$this->assertFalse($result);
	}

	public function testUserCanChangeRight_UserCan_UserIsNotAdmin() {
		$userData = array(
			'id' => 2,
			'role_id' => 4
		);
		$folderCreatorId = 3;
		$aroRoleId = 5;
		$aroUserId = 7;

		$result = $this->Aco->can(
			$userData,
			'canChangeRight',
			array($aroUserId, $aroRoleId, $folderCreatorId)
		);
		
		$this->assertTrue($result);
	}

	public function testUserCanChangeRight_UserCannotChangeHisOwnRights() {
		$userData = array(
			'id' => 2,
			'role_id' => 5
		);
		$folderCreatorId = $userData['id'];
		$aroRoleId = 5;
		$aroUserId = 2;

		$result = $this->Aco->can(
			$userData,
			'canChangeRight',
			array($aroUserId, $aroRoleId, $folderCreatorId)
		);
		
		$this->assertFalse($result);
	}
}
