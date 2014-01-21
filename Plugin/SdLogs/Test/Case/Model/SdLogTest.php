<?php
App::uses('SdLog', 'SdLogs.Model');

/**
 * SdLog Test Case
 *
 */
class SdLogTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.sd_logs.sd_log'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SdLog = ClassRegistry::init('SdLogs.SdLog');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SdLog);

		parent::tearDown();
	}

/**
 * testGetLast method
 *
 * @return void
 */
	public function testGetLast() {
		$type = 1;
		$model = 'myModel';
		$foreignKey = 1;

		$expected = array(
			'id' => 1,
			'type' => 1,
			'model' => 'myModel',
			'foreign_key' => 1,
			'data' => 'a:4:{s:7:"user_id";s:1:"2";s:4:"name";s:6:"admin1";s:9:"firstname";s:6:"admin1";s:5:"email";s:17:"admin1@admin1.com";}',
			'created' => '2012-11-21 11:57:25'
		);

		$result = $this->SdLog->getLatest($type, $model, $foreignKey);

		$this->assertEqual($result['SdLog'], $expected);
	}

}
