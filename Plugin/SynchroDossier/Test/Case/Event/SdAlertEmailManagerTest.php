<?php

App::uses('CroogoTestCase', 'Croogo.TestSuite');
App::uses('SdAlertEmailManager', 'SynchroDossier.Lib');
App::uses('CakeEvent', 'Event');

class SdAlertEmailManagerTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.settings.setting',
		'plugin.uploader.taxonomies_uploaded_file',
		'plugin.uploader.uploader_taxonomy',
		'plugin.uploader.file_storage',
		'plugin.uploader.roles_user',
		'plugin.uploader.uploader_profile',
		'plugin.uploader.uploader_role',
		'plugin.uploader.uploader_aco',
		'plugin.uploader.uploader_aro',
		'plugin.uploader.uploader_aros_aco',
		'plugin.uploader.uploader_comment',
		'plugin.uploader.uploader_sd_information',
		'plugin.sd_users.sd_users_collaboration',
		'plugin.sd_users.profile',
		'plugin.uploader.uploader_user',
		'plugin.uploader.uploaded_file',
		'plugin.synchro_dossier.sd_alert_email',
		'plugin.synchro_dossier.sd_file_email',
	);

	public function setUp() {
		parent::setUp();
		$this->SdAlertEmailManager = ClassRegistry::init('SynchroDossier.SdAlertEmailManager');
	}

	public function tearDown() {
		unset($this->SdAlertEmailManager);
		parent::tearDown();
	}

	public function testSendAlertsEmailShouldSendToEachUsers() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();
		$event->data['user']['id'] = 3;
		$this->__mockCakeEmailAndCheckSendCount(2, array('helpers'));
		$this->SdAlertEmailManager->sendAlertsEmail($event);
	}

	public function testSendMailWhenInvitedOnFolder() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$this->__mockCakeEmailAndCheckSendCount(1);

		$event->data['user']['id'] = 4;
		$event->data['folder']['id'] = '1';
		$event->data['folder']['name'] = 'A super folder';

		$this->SdAlertEmailManager->sendInvitedOnFolderEmail($event);
	}

	public function testGetMailWhenInvitedOnFolder_IsSendToConcernedPerson() {
		$event = $this->getMockBuilder('CakeEvent')
			->disableOriginalConstructor()
			->getMock();

		$this->__mockCakeEmailAndCheckSendCount(1);

		$this->SdAlertEmailManager->cakeEmail
			->expects($this->once())
			->method('to')
			->with($this->equalTo(array(
				'toto@derbois.com' => 'tata toto'
			)));

		$event->data['user']['id'] = 4;
		$event->data['folder']['id'] = '1';
		$event->data['folder']['name'] = 'A super folder';

		$this->SdAlertEmailManager->sendInvitedOnFolderEmail($event);
	}

	private function __mockCakeEmailAndCheckSendCount($numberOfEmailToBeSend, $extraMethods = array()) {
		$methods = array_merge(
			array('template', 'theme', 'emailFormat', 'from', 'to', 'subject', 'viewVars', 'send'),
			$extraMethods
		);

		$this->SdAlertEmailManager->cakeEmail = $this->getMockBuilder('CakeEmail')
			->disableOriginalConstructor()
			->getMock('CakeEmail', array('to'));

		foreach ($methods as $method) {
			$this->SdAlertEmailManager->cakeEmail
				->expects($this->exactly($numberOfEmailToBeSend))
				->method($method)
				->will($this->returnSelf());
		}
	}
}
