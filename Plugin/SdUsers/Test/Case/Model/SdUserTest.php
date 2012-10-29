<?php
App::uses('SdUser', 'SdUsers.Model');

/**
 * SdUser Test Case
 *
 */
class SdUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.sd_users.user',
		'plugin.sd_users.profile',
		'plugin.sd_users.role'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SdUser = ClassRegistry::init('SdUsers.SdUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SdUser);

		parent::tearDown();
	}

	public function testAddOk() {
		$creatorId = 3;
		$roleId = 1;
		$data = array(
			'SdUser' => array(
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
		$lastUserAdded = $this->SdUser->find('first', array('order' => 'SdUser.id DESC'));

		$this->assertTrue($result);
		$this->assertEqual($this->SdUser->find('count'), 4);
		$this->assertEqual($creatorId, $lastUserAdded['SdUser']['creator_id']);
	}

	public function testAddNoUsername() {
		$creatorId = 3;
		$roleId = 4;
		$data = array(
			'SdUser' => array(
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
			'SdUser' => array(
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
