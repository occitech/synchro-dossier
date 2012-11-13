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

	public function testUserCan_UserCan_OnRootFolder() {
		$this->View->viewVars = array(
			'userRights' => array(
				'User' => array(
					'id' => 3
				),
				'Aro' => array(
					'Aco' => array(
						array(
							'model' => 'UploadedFile',
							'foreign_key' => '7',
							'lft' => 9,
							'rght' => 12,
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
							'lft' => 1,
							'rght' => 8,
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
		$uploadedFile = array(
			'id' => 1,
			'model' => 'UploadedFile',
			'foreign_key' => 1,
			'lft' => 1,
			'rght' => 8
		);

		$this->Acl = new AclHelper($this->View);
		$result = $this->Acl->userCan($uploadedFile, 'read');

		$this->assertTrue($result);
	}

	public function testUserCan_UserCan_OnSubFolder () {
		$this->View->viewVars = array(
			'userRights' => array(
				'User' => array(
					'id' => 3
				),
				'Aro' => array(
					'Aco' => array(
						array(
							'model' => 'UploadedFile',
							'foreign_key' => '1',
							'lft' => 1,
							'rght' => 5,
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
		$uploadedFile = array(
			'id' => 8,
			'model' => 'UploadedFile',
			'foreign_key' => 18,
			'lft' => 2,
			'rght' => 3
		);

		$this->Acl = new AclHelper($this->View);

		$result = $this->Acl->userCan($uploadedFile, 'read');

		$this->assertTrue($result);
	}

	public function testUserCan_UserCannotOnRootFolder() {
		$this->View->viewVars = array(
			'userRights' => array(
				'User' => array(
					'id' => 3
				),
				'Aro' => array(
					'Aco' => array(
						array(
							'model' => 'UploadedFile',
							'foreign_key' => '7',
							'lft' => 5,
							'rght' => 12,
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
							'lft' => 1,
							'rght' => 5,
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
		$uploadedFile = array(
			'id' => 1,
			'model' => 'UploadedFile',
			'foreign_key' => 1,
			'lft' => 1,
			'rght' => 8
		);

		$this->Acl = new AclHelper($this->View);

		$result = $this->Acl->userCan($uploadedFile, 'read');

		$this->assertFalse($result);
	}
}