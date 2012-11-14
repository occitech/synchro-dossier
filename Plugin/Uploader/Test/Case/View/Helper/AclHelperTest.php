<?php

App::uses('CroogoTestCase', 'TestSuite');
App::uses('View', 'View');
App::uses('AclHelper', 'Uploader.View/Helper');

class AclHelperTest extends CroogoTestCase {

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
		$this->Acl = new AclHelper($this->View);
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

		$this->Acl = new AclHelper($this->View);
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

		$this->Acl = new AclHelper($this->View);

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

		$this->Acl = new AclHelper($this->View);

		$result = $this->Acl->userCan($uploadedFile, 'read');

		$this->assertFalse($result);
	}

	public function testUserCanChangeRight_UserCan_UserIsAdmin_UserIsCreatorOfFolder() {
		$loggedInUserId = 2;
		$loggedInUserRoleId = 5;
		$folderCreatorId = $loggedInUserId;
		$aroRoleId = 5;
		$aroUserId = 7;

		$result = $this->Acl->userCanChangeRight(
			$loggedInUserId,
			$loggedInUserRoleId,
			$aroUserId,
			$aroRoleId,
			$folderCreatorId
		);
		
		$this->assertTrue($result);
	}

	public function testUserCanChangeRight_UserCannot_UserIsAdmin_UserIsNotCreatorOfFolder() {
		$loggedInUserId = 2;
		$loggedInUserRoleId = 5;
		$folderCreatorId = 3;
		$aroRoleId = 5;
		$aroUserId = 7;

		$result = $this->Acl->userCanChangeRight(
			$loggedInUserId,
			$loggedInUserRoleId,
			$aroUserId ,
			$aroRoleId,
			$folderCreatorId
		);
		
		$this->assertFalse($result);
	}

	public function testUserCanChangeRight_UserCan_UserIsNotAdmin() {
		$loggedInUserId = 2;
		$loggedInUserRoleId = 4;
		$folderCreatorId = 3;
		$aroRoleId = 5;
		$aroUserId = 7;

		$result = $this->Acl->userCanChangeRight(
			$loggedInUserId,
			$loggedInUserRoleId,
			$aroUserId,
			$aroRoleId,
			$folderCreatorId
		);
		
		$this->assertTrue($result);
	}

	public function testUserCanChangeRight_UserCannotChangeHisOwnRights() {
		$loggedInUserId = 2;
		$loggedInUserRoleId = 5;
		$folderCreatorId = $loggedInUserId;
		$aroRoleId = 5;
		$aroUserId = 2;

		$result = $this->Acl->userCanChangeRight(
			$loggedInUserId,
			$loggedInUserRoleId,
			$aroUserId ,
			$aroRoleId,
			$folderCreatorId
		);
		
		$this->assertFalse($result);
	}
}