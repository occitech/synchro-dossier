<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('SynchroDossierHelper', 'SynchroDossier.View/Helper');

/**
 * SynchroDossierHelper Test Case
 *
 */
class SynchroDossierHelperTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
		$this->View = new View();
		$this->View->set('quota', array(
			'quota_mb' => '10000',
			'current_quota_mb' => '245'
		));
		$this->SynchroDossier = new SynchroDossierHelper($this->View);
	}

	public function tearDown() {
		unset($this->SynchroDossier);

		parent::tearDown();
	}

	public function testGetQuotaData() {
		$expected = array(
			'quota_mb' => '10000',
			'current_quota_mb' => '245'
		);
		$result = $this->SynchroDossier->getQuotaData();

		$this->assertEqual($result, $expected);
	}

	public function testDisplayQuota_HasDataToPrint() {
		$result = $this->SynchroDossier->displayQuota();

		$this->assertContains('9.77', $result);
		$this->assertContains('2.45%', $result);
	}

	public function testDisplayQuota_HasNotDataToPrint() {
		$this->View->set('quota', array());
		$result = $this->SynchroDossier->displayQuota();

		$this->assertEqual($result, '');
	}

}
