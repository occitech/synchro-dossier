<?php

App::uses('SdAlertEmail', 'SynchroDossier.Model');

class SdAlertEmailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.uploader.uploader_user',
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
		$uploaderId = 3;
		$result = $this->SdAlertEmail->getUserToAlert($uploaderId);

		$expected = array(
			'aymeric@derbois.com' => array(
				'id' => '2',
				'role_id' => '6',
				'username' => 'aymeric',
				'password' => '935dce4494121f848ffe2d3337ed2c05192526b1',
				'name' => 'Derbois',
				'email' => 'aymeric@derbois.com',
				'website' => '',
				'activation_key' => 'd6b0ca85517794669b14460dec519714',
				'image' => null,
				'bio' => null,
				'timezone' => '0',
				'status' => 1,
				'updated' => '2012-10-31 17:21:32',
				'created' => '2012-10-31 17:21:32'
			),
			'admin@occitech.fr' => array(
				'id' => '1',
				'role_id' => '1',
				'username' => 'admin',
				'password' => 'd1e03fcbc79398c3f93a7c875a86baae3aa99d42',
				'name' => 'admin',
				'email' => 'admin@occitech.fr',
				'website' => null,
				'activation_key' => '4a150a31c5b8e892b6e21251d4d8f884',
				'image' => null,
				'bio' => null,
				'timezone' => '0',
				'status' => 1,
				'updated' => '2012-10-31 12:51:05',
				'created' => '2012-10-31 12:51:05'
			)
		);

		$this->assertEquals($expected, $result['to']);
	}

	public function testGetUserToAlert_DontAlertMyselfWhenIUploadAFile() {
		$uploaderId = 2;
		$result = $this->SdAlertEmail->getUserToAlert($uploaderId);

		$this->assertArrayNotHasKey('aymeric@derbois.com', $result['to']);
	}

	public function testToggleAlertMailWithUserNotYetSubscribed_ShouldSubscribeUser() {
		$userId = 2;
		$folderId = 2;
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$event->data['userId'] = $userId;
		$event->data['folderId'] = $folderId;
		$this->SdAlertEmail->toggleEmailAlert($event);
		$this->assertTrue($this->SdAlertEmail->hasAny(array('user_id' => $userId, 'uploaded_file_id' => $folderId)));
	}

	public function testToggleAlertMailWithUserAlreadySubscribed_ShouldUnsubscribeUser() {
		$userId = 2;
		$folderId = 1;
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$event->data['userId'] = $userId;
		$event->data['folderId'] = $folderId;
		$this->SdAlertEmail->toggleEmailAlert($event);
		// debug($this->SdAlertEmail->find('all'));
		$this->assertFalse($this->SdAlertEmail->hasAny(array('user_id' => $userId, 'uploaded_file_id' => $folderId)));
	}
}
