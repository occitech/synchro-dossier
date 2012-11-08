<?php

App::uses('Controller', 'Controller');
App::uses('CroogoTestCase', 'TestSuite');
App::uses('SdUser', 'SdUsers.Model');
App::uses('User', 'Users.Model');
App::uses('AclCachedAuthorize', 'Acl.Controller/Component/Auth');
App::uses('CroogoTestCase', 'TestSuite');
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');
App::uses('User', 'Users.Model');
App::uses('AuthComponent', 'Controller/Component');
App::uses('CroogoTestCase', 'TestSuite');

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
		'plugin.sd_users.user',
		'plugin.sd_users.profile',
		'plugin.sd_users.role',
		'plugin.sd_users.aros_aco',
		'plugin.sd_users.aro',
		'plugin.sd_users.aco'
	);

	public function setUp() {
		parent::setUp();
		$this->SdUser = ClassRegistry::init('SdUsers.SdUser');
		$this->Aro = ClassRegistry::init('Aro');
	}

	public function tearDown() {
		unset($this->SdUser);
		unset($this->Aro);
		parent::tearDown();
	}

	public function testAddOk() {
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
		$this->assertEqual($this->SdUser->find('count'), 4);
		$this->assertEqual($creatorId, $lastUserAdded['User']['creator_id']);
	}

	public function testAddAroCorrectlyAdded() {
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

	public function testAddOkAcl() {
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
		
		$request = new CakeRequest('/admin/sd_users/sd_users/index');
		$request->addParams(array(
			'controller' => 'sd_users',
			'action' => 'admin_index',
		));
		$user = $this->SdUser->find('first', array('order' => 'User.id desc'));
		$user = $user['User'];
		$AclCachedAuthorize = new AclCachedAuthorize(new ComponentCollection());

		$result = $AclCachedAuthorize->authorize($user, $request);
		$this->assertTrue($result);
	}

	public function testAddNoUsername() {
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
		$this->assertFalse($result);
	}

	public function testEditOk() {
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
		$this->assertEqual($this->SdUser->find('count'), 3);
	}

	public function testFindCreatedByTwoResult() {
		$result = $this->SdUser->find('createdBy', array('creatorId' => 1));
		$this->assertEqual(count($result), 2);
	}

	public function testFindCreatedByNoResult() {
		$result = $this->SdUser->find('createdBy', array('creatorId' => 2));
		$this->assertEqual(count($result), 0);
	}

}
