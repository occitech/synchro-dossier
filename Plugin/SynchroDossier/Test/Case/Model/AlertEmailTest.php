<?php

App::uses('AlertEmail', 'SynchroDossier.Model');

class AlertEmailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.synchro_dossier.alert_email',
		'plugin.synchro_dossier.user',
		'plugin.synchro_dossier.uploaded_file'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AlertEmail = ClassRegistry::init('SynchroDossier.AlertEmail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AlertEmail);

		parent::tearDown();
	}

}
