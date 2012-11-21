<?php
App::uses('SdInformation', 'SynchroDossier.Model');

/**
 * SdInformation Test Case
 *
 */
class SdInformationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.synchro_dossier.sd_information'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SdInformation = ClassRegistry::init('SynchroDossier.SdInformation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SdInformation);

		parent::tearDown();
	}

/**
 * testGetUsedQuota method
 *
 * @return void
 */
	public function testGetUsedQuota() {
		$result = $this->SdInformation->getUsedQuota();

		$this->assertEqual($result, 3);
	}

	public function testRemainingQuota() {
		$result = $this->SdInformation->remainingQuota();

		$this->assertEqual($result, 7);
	}

}
