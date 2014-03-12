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

	private $__usersCount;

	public $fixtures = array(
		'plugin.sd_users.sd_users_user',
		'plugin.sd_users.profile',
		'plugin.sd_users.sd_users_role',
		'plugin.sd_users.sd_users_aros_aco',
		'plugin.sd_users.sd_users_aro',
		'plugin.sd_users.sd_users_aco',
		'plugin.settings.setting',
		'plugin.sd_users.sd_users_collaboration',
	);

	public function setUp() {
		Configure::write('Acl.database', 'test');
		parent::setUp();
		$this->SdUser = ClassRegistry::init('SdUsers.SdUser');
		$this->__usersCount = $this->SdUser->find('count', array('recursive' =>  -1, 'noRoleChecking' => true));
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
		$data = $this->__userData;
		$result = $this->SdUser->add($data, $creatorId, $roleId);
		$lastUserAdded = $this->SdUser->find('first', array('order' => 'User.id DESC'));

		$this->assertTrue($result);
		$this->_assertCountSdUsers($this->__usersCount + 1, array('noRoleChecking' => true));
		$this->assertEquals($creatorId, $lastUserAdded['User']['creator_id']);
	}

	public function testAddUser_ShouldCreateACollaboration() {
		$creatorId = 3;
		$roleId = 1;
		$data = $this->__userData;
		$oldCount = $this->SdUser->Collaboration->find('count');
		$this->SdUser->add($data, $creatorId, $roleId);
		$newCount = $this->SdUser->Collaboration->find('count');

		$this->assertEquals($oldCount + 1, $newCount);
	}

	public function testAddExistingUser_ShouldNotCreateANewUser() {
		$creatorId = 3;
		$roleId = 1;
		$data = $this->__userData;
		$data['User']['email'] = 'user1@user1.com';

		$this->_assertCountSdUsers($this->__usersCount, array('noRoleChecking' => true));
	}

	public function testAddExistingUser_ShouldCreateACollaboration() {
		$creatorId = 3;
		$roleId = 1;
		$data = $this->__userData;
		$data['User']['email'] = 'user1@user1.com';

		$this->SdUser->add($data, $creatorId, $roleId);
		$result = $this->SdUser->UsersCollaboration->hasAny(array(
			'user_id' => 6,
			'parent_id' => 3,
		));

		$this->assertTrue($result);
	}

	public function testAddExistingCollaboration_ShouldNotCreateANewCollaboration() {
		$creatorId = 1;
		$roleId = 1;
		$data = $this->__userData;
		$data['User']['email'] = 'aymeric@derbois.com';

		$oldCount = $this->SdUser->Collaboration->find('count');
		$this->SdUser->add($data, $creatorId, $roleId);
		$newCount = $this->SdUser->Collaboration->find('count');

		$this->assertEquals($oldCount, $newCount);
	}

	protected function _assertCountSdUsers($expected, $parameters=array()) {
		$parameters['fields'] = array($this->SdUser->primaryKey);
		$result = $this->SdUser->find('all', $parameters);
		$resultCount = $this->_countUniqueUsers($result);
		$this->assertEquals($expected, $resultCount);
	}

	protected function _countUniqueUsers($result) {
		$uniqueIds = array_unique(Hash::extract($result, '{n}.User.' . $this->SdUser->primaryKey));
		return count($uniqueIds);

	}

	public function testAdd_FillUsernameField() {
		$creatorId = 3;
		$roleId = 1;
		$data = $this->__userData;
		$data['User']['username'] = 'user1';

		$result = $this->SdUser->add($data, $creatorId, $roleId);
		$lastUserAdded = $this->SdUser->find('first', array('order' => 'User.id DESC'));

		$this->assertTrue($result);
		$this->assertTrue(!empty($lastUserAdded['User']['username']));

		$this->assertEquals('user1', $lastUserAdded['User']['username']);
	}

	public function testAdd_AroCorrectlyAdded() {
		$creatorId = 3;
		$roleId = 1;
		$data = $this->__userData;
		$data['User']['role_id'] = 4;

		$this->SdUser->add($data, $creatorId, $roleId);
		$result = $this->Aro->find('first', array('order' => 'id DESC'));
		$expected = array(
			'id' => '10',
			'parent_id' => '5',
			'model' => 'User',
			'foreign_key' => $this->__usersCount + 1,
			'alias' => 'coucou',
			'lft' => '12',
			'rght' => '13'
		);
		$this->assertEquals($result['Aro'], $expected);
	}

	public function testAdd_NewUserCanAccesToFile_WithInheritsRights() {
		$creatorId = 3;
		$roleId = 1;
		$data = $this->__userData;
		$data['User']['role_id'] = 4;
		$data['User']['username'] = 'user1';

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
		$data = $this->__userData;
		$data['User']['role_id'] = 4;

		$result = $this->SdUser->add($data, $creatorId, $roleId);
		$this->assertTrue($result);
	}

	public function testEdit_Ok() {
		$creatorId = 3;
		$roleId = 1;
		$data = $this->__userData;
		$data['User']['id'] = 1;
		$data['User']['password'] = '';
		$data['User']['role_id'] = 5;
		$data['User']['email'] = 'coucou@coucou.com';

		$this->assertTrue($this->SdUser->edit($data, $roleId));
		$this->_assertCountSdUsers($this->__usersCount);
	}

/**
 * Test the custom find 'VisibleBy'
 */
	public function testFindVisibleByOccitech_FiveResultsNeeded() {
		$result = $this->SdUser->find('visibleBy', array('userId' => 1));
		$this->assertEquals($this->_countUniqueUsers($result), 5);
	}

	public function testFindVisibleBySuperAdmin_FourResultsNeeded() {
		$result = $this->SdUser->find('visibleBy', array('userId' => 3));
		$this->assertEquals($this->_countUniqueUsers($result), 4);
	}

	public function testFindVisibleByShouldReturnsUniqueUsers() {
		$result = $this->SdUser->find('visibleBy', array(
			'userId' => 4,
			'fields' => array('id')
		));
		$userIds = Hash::extract($result, '{n}.User.id');
		sort($userIds);
		$expected = array(2,5,6);

		$this->assertEquals($expected, $userIds);
	}

	public function testFindVisibleByUser_NoResult() {
		$result = $this->SdUser->find('visibleBy', array('userId' => 6));
		$this->assertEquals(count($result), 0);
	}

	public function testUserCanBeVisibleBy2Admins() {
		$visibleByAdmin1 = $this->SdUser->find('visibleBy', array(
			'userId' => 4,
			'fields' => 'id'
		));

		$visibleByAdmin1 = Hash::extract($visibleByAdmin1, '{n}.User.id');
		$visibleByAdmin2 = $this->SdUser->find('visibleBy', array(
			'userId' => 5,
			'fields' => 'id'
		));
		$visibleByAdmin2 = Hash::extract($visibleByAdmin2, '{n}.User.id');

		$this->assertContains(2, $visibleByAdmin1);
		$this->assertContains(2, $visibleByAdmin2);
	}

/**
 * Test the custom find 'SuperAdmin'
 */
	public function testFindSuperAdmin() {
		$result = $this->SdUser->find('superAdmin');
		$this->assertEquals($this->_countUniqueUsers($result), 1);
	}

/**
 * Test : getAllRights
 */
	public function testGetAllRights_UserHasRightOnTwoFile() {
		$userId = 3;
		$result = $this->SdUser->getAllRights($userId);

		$this->assertEquals(count($result['Aro']['Aco']), 2);
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

	private $__userData = array(
			'User' => array(
				'role_id' => 6,
				'username' => 'coucou',
				'email' => 'Coucou@coucou.com',
				'status' => '1'
			),
			'Profile' => array(
				'name' => 'user',
				'firstname' => 'name',
				'society' => 'occitech'
			)
		);

}
