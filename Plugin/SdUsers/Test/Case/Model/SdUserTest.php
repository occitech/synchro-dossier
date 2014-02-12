<?php

App::uses('SdUser', 'SdUsers.Model');
App::uses('Controller', 'Controller');
App::uses('CroogoTestCase', 'Croogo.TestSuite');
App::uses('AclCachedAuthorize', 'Acl.Controller/Component/Auth');
App::uses('AuthComponent', 'Controller/Component');
App::uses('AclComponent', 'Controller/Component');


class SdUserTestController extends Controller {

	public $components = array(
		'Auth',
		'Acl',
		'Session',
		'Acl.AclFilter',
	);
}

class SdUserTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.sd_users.sd_users_user',
		'plugin.sd_users.profile',
		'plugin.sd_users.sd_users_role',
		'plugin.sd_users.sd_users_aros_aco',
		'plugin.sd_users.sd_users_aro',
		'plugin.sd_users.sd_users_aco'
	);

	public function setUp() {
		Configure::write('Acl.database', 'test');
		parent::setUp();
		$this->SdUser = ClassRegistry::init('SdUsers.SdUser');
		$this->Aro = ClassRegistry::init('Aro');
	}

	public function tearDown() {
		unset($this->SdUser);
		unset($this->Aro);
		parent::tearDown();
	}

	public function testAdd_Ok() {
		$creatorId = 3;
		$roleId = 1;
		$data = array(
			'User' => array(
				'role_id' => '6',
				'username' => 'coucou',
				'email' => 'coucou@coucou.com',
				'status' => '1'
			),
			'Profile' => array(
				'name' => 'sdfsqfsdf',
				'firstname' => 'sdf',
				'society' => 'qsqssdf'
			)
		);
		$result = $this->SdUser->add($data, $creatorId, $roleId);
		$lastUserAdded = $this->SdUser->find('first', array('order' => 'User.id DESC'));

		$this->assertTrue($result);
		$this->_assertCountSdUsers(4, array('noRoleChecking' => true));
		$this->assertEqual($creatorId, $lastUserAdded['User']['creator_id']);
	}

	protected function _assertCountSdUsers($expected, $parameters=array()) {
		$parameters['fields'] = array($this->SdUser->primaryKey);
		$result = $this->SdUser->find('all', $parameters);
		$resultCount = $this->_countUniqueUsers($result);
		$this->assertEqual($expected, $resultCount);
	}

	protected function _countUniqueUsers($result) {
		$uniqueIds = array_unique(Hash::extract($result, '{n}.User.' . $this->SdUser->primaryKey));
		return count($uniqueIds);

	}

	public function testAdd_FillUsernameField() {
		$creatorId = 3;
		$roleId = 1;
		$data = array(
			'User' => array(
				'role_id' => '6',
				'username' => '',
				'email' => 'coucou@coucou.com',
				'status' => '1'
			),
			'Profile' => array(
				'name' => 'sdfsqfsdf',
				'firstname' => 'sdf',
				'society' => 'qsqssdf'
			)
		);
		$result = $this->SdUser->add($data, $creatorId, $roleId);
		$lastUserAdded = $this->SdUser->find('first', array('order' => 'User.id DESC'));

		$this->assertTrue($result);
		$this->assertTrue(!empty($lastUserAdded['User']['username']));

		$this->assertEquals('sdfsqfsdfsdf', $lastUserAdded['User']['username']);
	}

	public function testAdd_AroCorrectlyAdded() {
		$creatorId = 3;
		$roleId = 1;
		$data = array(
			'User' => array(
				'role_id' => '4',
				'username' => 'coucou',
				'email' => 'coucou@coucou.com',
				'status' => '1'
			),
			'Profile' => array(
				'name' => 'sdfsqfsdf',
				'firstname' => 'sdf',
				'society' => 'qsqssdf'
			)
		);

		$this->SdUser->add($data, $creatorId, $roleId);

		$result = $this->Aro->find('first', array('order' => 'id DESC'));

		$expected = array(
			'id' => '10',
			'parent_id' => '5',
			'model' => 'User',
			'foreign_key' => '4',
			'alias' => 'coucou',
			'lft' => '12',
			'rght' => '13'
		);
		$this->assertEqual($result['Aro'], $expected);
	}

	public function testAdd_NewUserCanAccesToFile_WithInheritsRights() {
		$creatorId = 3;
		$roleId = 1;
		$data = array(
			'User' => array(
				'role_id' => '4',
				'username' => 'coucou',
				'email' => 'coucou@coucou.com',
				'status' => '1'
			),
			'Profile' => array(
				'name' => 'sdfsqfsdf',
				'firstname' => 'sdf',
				'society' => 'qsqssdf'
			)
		);

		$result = $this->SdUser->add($data, $creatorId, $roleId);

		$this->SdUser->bindModel(array(
			'hasOne' => array(
				'Aro' => array(
					'className' => 'Aro',
					'foreignKey' => 'foreign_key'
				)
			)
		));

		$user = $this->SdUser->find('first', array(
			'order' => 'User.id desc',
			'contain' => array('Aro' => array('conditions' => 'Aro.model = "User"')),
		));

		$Acl = new AclComponent(new ComponentCollection);
		$result = $Acl->check(
			$user['Aro'],
			array('model' => 'UploadedFile', 'foreign_key' => 4)
		);

		$this->assertTrue($result);
	}

	public function testAdd_NoUsernameGiven() {
		$creatorId = 3;
		$roleId = 4;
		$data = array(
			'User' => array(
				'role_id' => '4',
				'email' => 'coucou@coucou.com',
				'status' => '1'
			),
			'Profile' => array(
				'name' => 'sdfsqfsdf',
				'firstname' => 'sdf',
				'society' => 'qsqssdf'
			)
		);
		$result = $this->SdUser->add($data, $creatorId, $roleId);
		$this->assertTrue($result);
	}

	public function testEdit_Ok() {
		$creatorId = 3;
		$roleId = 1;
		$data = array(
			'User' => array(
				'id' => '1',
				'role_id' => '5',
				'username' => 'coucou',
				'email' => 'coucou@coucou.com',
				'status' => '1'
			),
			'Profile' => array(
				'name' => 'sdfsqfsdf',
				'firstname' => 'sdf',
				'society' => 'qsqssdf'
			)
		);
		$this->assertTrue($this->SdUser->edit($data, $roleId));
		$this->_assertCountSdUsers(3);
	}

/**
 * Test the custom find 'CreatedBy'
 */
	public function testFindCreatedBy_TwoResultNedded() {
		$result = $this->SdUser->find('createdBy', array('creatorId' => 1));
		$this->assertEqual($this->_countUniqueUsers($result), 2);
	}

	public function testFindCreatedBy_NoResult() {
		$result = $this->SdUser->find('createdBy', array('creatorId' => 2));
		$this->assertEqual(count($result), 0);
	}

/**
 * Test the custom find 'SuperAdmin'
 */
	public function testFindSuperAdmin() {
		$result = $this->SdUser->find('superAdmin');
		$this->assertEqual($this->_countUniqueUsers($result), 1);
	}

/**
 * Test : getAllRights
 */
	public function testGetAllRights_UserHasRightOnTwoFile() {
		$userId = 3;
		$result = $this->SdUser->getAllRights($userId);

		$this->assertEqual(count($result['Aro']['Aco']), 2);
	}

/**
 * Test: changePassword();
 */
	public function testChangePassword(){
		$userId = 2;
		$this->SdUser->id = $userId;
		$success = (bool) $this->SdUser->saveField('password', 'password');
		$this->assertTrue($success);

		$oldPassword = 'password';
		$newPassword = 'new-password';
		$newPasswordConfirmation = 'new-password';

		$this->assertTrue($this->SdUser->changePassword($userId, $oldPassword, $newPassword, $newPasswordConfirmation));
		$this->SdUser->id = $userId;
		$this->assertEquals(Security::hash($newPassword, null, true), $this->SdUser->field('password'));
	}

	public function testChangePassword_WhenInvalidUserId() {
		$this->setExpectedException('NotFoundException');

		$userId = 42;
		$oldPassword = 'password';
		$newPassword = 'new-password';
		$newPasswordConfirmation = 'new-password';

		$this->SdUser->changePassword($userId, $oldPassword, $newPassword, $newPasswordConfirmation);
	}

	public function testChangePassword_WhenEmptyNewPassword() {
		$this->setExpectedException('InvalidArgumentException');
		$userId = 2;
		$oldPassword = 'password';
		$newPassword = '';
		$newPasswordConfirmation = '';

		$this->SdUser->changePassword($userId, $oldPassword, $newPassword, $newPasswordConfirmation);
	}

}
