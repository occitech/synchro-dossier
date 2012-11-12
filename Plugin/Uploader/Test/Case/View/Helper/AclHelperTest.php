<?php

App::uses('CroogoTestCase', 'TestSuite');
App::uses('View', 'View');
App::uses('AclHelper', 'Uploader.View/Helper');

class AclHelperTest extends CroogoTestCase {

	public function setUp() {
		$controller = null;
		$this->View = new View($controller);
	}

	public function tearDown() {
		unset($this->View);
		unset($this->Acl);
	}

	public function testUserCan_UserCan() {
		$this->View->viewVars = array(
			'userRights' => array(
				'Aro' => array(
					'Aco' => array(
						array(
							'model' => 'UploadedFile',
							'foreign_key' => '7',
							'Permission' => array(
								'_create' => '1',
								'_read' => '1',
								'_update' => '1',
								'_delete' => '1'
							)
						),
						array(
							'model' => 'UploadedFile',
							'foreign_key' => '1',
							'Permission' => array(
								'_create' => '0',
								'_read' => '1',
								'_update' => '0',
								'_delete' => '0'
							)
						)
					)
				)
			)
		);

		$this->Acl = new AclHelper($this->View);
		$result = $this->Acl->userCan(7, 'read');

		$this->assertTrue($result);
	}

	public function testUserCan_UserCannot() {
		$this->View->viewVars = array(
			'userRights' => array(
				'Aro' => array(
					'Aco' => array(
						array(
							'model' => 'UploadedFile',
							'foreign_key' => '7',
							'Permission' => array(
								'_create' => '0',
								'_read' => '0',
								'_update' => '0',
								'_delete' => '0'
							)
						),
						array(
							'model' => 'UploadedFile',
							'foreign_key' => '1',
							'Permission' => array(
								'_create' => '0',
								'_read' => '1',
								'_update' => '0',
								'_delete' => '0'
							)
						)
					)
				)
			)
		);

		$this->Acl = new AclHelper($this->View);
		$result = $this->Acl->userCan(7, 'read');

		$this->assertFalse($result);
	}
}