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

	public function testGetPaginateAll() {
		$result = $this->SdUser->getPaginateAll();

		$this->assertEqual(array(), $result);
	}

	public function testGetPaginateByCreatorId() {
		$result = $this->SdUser->getPaginateByCreatorId(1);
		$expected = array(
			'conditions' => array('SdUser.creator_id' => 1)
		);

		$this->assertEqual($expected, $result);
	}

	public function testAddOk() {
		$creatorId = 3;
		$data = array(
			'SdUser' => array(
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
		$this->SdUser->add($data, $creatorId);
		$lastUserAdded = $this->SdUser->find('first', array('order' => 'SdUser.id DESC'));

		$this->assertEqual(4, $this->SdUser->find('count'));
		$this->assertEqual($creatorId, $lastUserAdded['SdUser']['creator_id']);
	}

	public function testAddNoUsername() {
		$creatorId = 3;
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
		$result = $this->SdUser->add($data, $creatorId);
		$this->assertFalse($result);
	}

}
