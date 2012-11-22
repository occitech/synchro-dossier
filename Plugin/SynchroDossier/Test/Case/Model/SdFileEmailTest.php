<?php
App::uses('SdFileEmail', 'SynchroDossier.Model');

/**
 * SdFileEmail Test Case
 *
 */
class SdFileEmailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.synchro_dossier.sd_file_email',
		'plugin.synchro_dossier.uploaded_file',
		'plugin.synchro_dossier.user'
	);

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

}
