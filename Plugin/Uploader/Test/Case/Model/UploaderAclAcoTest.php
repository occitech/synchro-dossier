<?php
App::uses('AclAco', 'Uploader.Model');

/**
 * Aco Test Case
 *
 */
class UploaderAclAcoTest extends CakeTestCase {

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
		$this->Aco = ClassRegistry::init('Uploader.UploaderAclAco');
	}

	public function tearDown() {
		unset($this->Aco);

		parent::tearDown();
	}

	public function testGetArosOfFolder_HasAros() {
		$result = $this->Aco->getArosOfFolder('UploadedFile', 1);

		$this->assertTrue(!empty($result['Aro']));
		$this->assertEqual($result['Aro'][0]['alias'], 'aymeric');
	}

	public function testGetArosOfFolder_HasNotAro() {
		$result = $this->Aco->getArosOfFolder('UploadedFile', 4);

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

	public function testUserCanUpdateUser_UserCan() {
		$userData = array(
			'id' => 2,
			'role_id' => Configure::read('sd.Admin.roleId')
		);
		$ressource['role_id'] = Configure::read('sd.Utilisateur.roleId');

		$result = $this->Aco->can(
			$userData,
			'canUpdateUser',
			array($ressource)
		);

		$this->assertTrue($result);
	}

	public function testUserCanUpdateUser_UserCannot() {
		$userData = array(
			'id' => 2,
			'role_id' => Configure::read('sd.Admin.roleId')
		);
		$ressource['role_id'] = Configure::read('sd.Admin.roleId');

		$result = $this->Aco->can(
			$userData,
			'canUpdateUser',
			array($ressource)
		);

		$this->assertFalse($result);
	}

}
