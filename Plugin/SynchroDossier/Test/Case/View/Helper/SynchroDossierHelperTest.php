<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('SynchroDossierHelper', 'SynchroDossier.View/Helper');

/**
 * SynchroDossierHelper Test Case
 *
 */
class SynchroDossierHelperTest extends CakeTestCase {

	public $defaultQuota = array(
		'quota_mb' => '10000',
		'current_quota_mb' => '245'
	);

	public function setUp() {
		parent::setUp();

		$this->View = $this->getMock('View');
		$this->SynchroDossier = new SynchroDossierHelper($this->View);
	}

	public function tearDown() {
		unset($this->SynchroDossier);
		unset($this->View);

		parent::tearDown();
	}


	public function testGetQuotaData() {
		$this->View
			->expects($this->any())
			->method('getVar')
			->will($this->returnValue($this->defaultQuota));

		$expected = $this->defaultQuota;
		$result = $this->SynchroDossier->getQuotaData();

		$this->assertEqual($result, $expected);
	}

	public function testDisplayQuota_HasDataToPrint() {
		$this->View
			->expects($this->any())
			->method('getVar')
			->will($this->returnValue($this->defaultQuota));

		$elementExpectedParams = array(
			'toPrint' => true,
			'quota' => 9.77,
			'currentQuota' => 0.24,
			'usedPercent' => 2.45
		);

		$this->View
			->expects($this->any())
			->method('element')
			->with('SynchroDossier.displayQuota', $elementExpectedParams);

		$this->SynchroDossier->displayQuota();
	}

	public function testDisplayQuota_HasNotDataToPrint() {
		$this->View
			->expects($this->any())
			->method('getVar')
			->will($this->returnValue(array()));

		$elementExpectedParams = array(
			'toPrint' => false
		);

		$this->View
			->expects($this->any())
			->method('element')
			->with('SynchroDossier.displayQuota', $elementExpectedParams);

		$this->SynchroDossier->displayQuota();
	}

}
