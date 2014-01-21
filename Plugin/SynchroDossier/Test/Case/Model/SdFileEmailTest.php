<?php
App::uses('SdFileEmail', 'SynchroDossier.Model');

/**
 * SdFileEmail Test Case
 *
 */
class SdFileEmailTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SdFileEmail = ClassRegistry::init('SynchroDossier.SdFileEmail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SdFileEmail);

		parent::tearDown();
	}

	public function testFirstTest() {
		$this->markTestIncomplete('Test me');
	}

}
