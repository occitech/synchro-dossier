<?php

App::uses('SdAlertEmail', 'SynchroDossier.Model');

class SdAlertEmailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.uploader.user',
		'plugin.uploader.uploaded_file',
		'plugin.synchro_dossier.sd_alert_email',
		'plugin.synchro_dossier.sd_file_email',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SdAlertEmail = ClassRegistry::init('SynchroDossier.SdAlertEmail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SdAlertEmail);

		parent::tearDown();
	}

	public function testGetUserToAlert_NoBodyToAlert() {
		$userId = 1;
		$result = $this->SdAlertEmail->getUserToAlert($userId);

		$this->assertFalse($result);
	}

	public function testGetUserToAlert_PeopleToAlert() {
		$userId = 2;
		$result = $this->SdAlertEmail->getUserToAlert($userId);

		$expected = array(
			'files' => array(
				(int) 0 => array(
					'SdFileEmail' => array(
						'id' => '1',
						'uploaded_file_id' => '1',
						'user_id' => '2'
					),
					'UploadedFile' => array(
						'id' => '1',
						'filename' => 'Photos',
						'size' => '0',
						'user_id' => '1',
						'current_version' => '0',
						'available' => '0',
						'parent_id' => null,
						'is_folder' => '1',
						'lft' => '1',
						'rght' => '8',
						'mime_type' => null
					),
					'User' => array(
						'password' => '935dce4494121f848ffe2d3337ed2c05192526b1',
						'id' => '2',
						'role_id' => '6',
						'creator_id' => '0',
						'username' => 'aymeric',
						'name' => 'Derbois',
						'email' => 'aymeric@derbois.com',
						'website' => '',
						'activation_key' => 'd6b0ca85517794669b14460dec519714',
						'image' => null,
						'bio' => null,
						'timezone' => '0',
						'status' => true,
						'updated' => '2012-10-31 17:21:32',
						'created' => '2012-10-31 17:21:32'
					)
				)
			),
			'to' => array(
				'aymeric@derbois.com' => 'aymeric'
			)
		);

		$this->assertEqual($result, $expected);
	}
}
