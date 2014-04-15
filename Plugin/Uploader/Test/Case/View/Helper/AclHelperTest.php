<?php

App::uses('CroogoTestCase', 'Croogo.TestSuite');
App::uses('View', 'View');
App::uses('UploaderAclHelper', 'Uploader.View/Helper');

class UploaderAclHelperTest extends CroogoTestCase {

	public function setUp() {
		$controller = null;
		$this->View = new View($controller);
		$this->View->viewVars = array(
			'userRights' => array(
				'User' => array(
					'id' => 3
				),
				'Aro' => array(
					'Aco' => array()
				)
			)
		);
		$this->Acl = new UploaderAclHelper($this->View);
	}

	public function tearDown() {
		unset($this->View);
		unset($this->Acl);
	}

	public function testUserCan_UserCan_OnRootFolder() {
		unset($this->Acl);
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

		$this->Acl = new UploaderAclHelper($this->View);
		$result = $this->Acl->userCan($uploadedFile, 'read');

		$this->assertTrue($result);
	}

	public function testUserCan_UserCan_OnSubFolder () {
		unset($this->Acl);
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

		$this->Acl = new UploaderAclHelper($this->View);

		$result = $this->Acl->userCan($uploadedFile, 'read');

		$this->assertTrue($result);
	}

	public function testUserCan_UserCan_OnSubFolder_Extends () {
		unset($this->Acl);
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
							'rght' => 6,
							'Permission' => array(
								'_create' => '0',
								'_read' => '1',
								'_update' => '0',
								'_delete' => '0'
							)
						),
						array(
							'model' => 'UploadedFile',
							'foreign_key' => '1',
							'lft' => 2,
							'rght' => 5,
							'Permission' => array(
								'_create' => '0',
								'_read' => '0',
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
			'lft' => 3,
			'rght' => 4
		);

		$this->Acl = new UploaderAclHelper($this->View);

		$result = $this->Acl->userCan($uploadedFile, 'read');

		$this->assertTrue($result);
	}


	public function testUserCan_UserCannotOnRootFolder() {
		unset($this->Acl);
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

		$this->Acl = new UploaderAclHelper($this->View);

		$result = $this->Acl->userCan($uploadedFile, 'read');

		$this->assertFalse($result);
	}
}
