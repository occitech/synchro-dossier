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
			'aymeric@derbois.com' => 'aymeric',
			'admin@occitech.fr' => 'admin'
		);

		$this->assertEqual($result['to'], $expected);
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
