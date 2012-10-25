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
		'plugin.sd_users.user'
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

}
