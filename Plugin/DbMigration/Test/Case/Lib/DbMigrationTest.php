<?php

App::uses('CroogoTestCase', 'TestSuite');
App::uses('DbMigration', 'DbMigration.Lib');
App::uses('Shell', 'Console');

class DbMigrationTest extends CroogoTestCase {

	public $fixtures = array(
		'plugin.db_migration.old_user',
		'plugin.db_migration.old_folder',
		'plugin.db_migration.old_file',
		'plugin.db_migration.old_files_comment',
		'plugin.db_migration.old_user_alert',
		'plugin.db_migration.old_user_folder',
		'plugin.db_migration.old_orders_user',
		'plugin.db_migration.user',
		'plugin.db_migration.role',
		'plugin.db_migration.profile',
		'plugin.db_migration.aro',
		'plugin.db_migration.aco',
		'plugin.db_migration.aros_aco',
		'plugin.db_migration.uploaded_file',
		'plugin.db_migration.file_storage',
		'plugin.db_migration.comment',
		'plugin.db_migration.sd_information',
		'plugin.db_migration.sd_alert_email',
	);

	protected function _rmContentDir($dir) { 
		if (is_dir($dir)) { 
			$contents = scandir($dir); 
			foreach ($contents as $content) { 
				if ($content != "." && $content != "..") { 
					if (is_dir($dir."/".$content)) {
						$this->_rmContentDir($dir."/".$content);
					} else {
						unlink($dir."/".$content);
					}
				}
			}
			reset($contents); 
		} 
	}

	public function setUp() {
		Configure::write('Acl.database', 'test');
		Configure::write('DbMigration.db', 'testold');
		parent::setUp();
		$this->DbMigration = new DbMigration(new Shell());
	}

	public function tearDown() {
		unset($this->DbMigration);
		parent::tearDown();
	}

	public function testMigrateUser() {
		$UserModel = ClassRegistry::init('SdUsers.SdUser');
		$OldUserModel = ClassRegistry::init('DbMigration.DbMigrationUser');

		$this->DbMigration->migrateUser();

		$old = $OldUserModel->find('all');
		$new = $UserModel->find('all');
		for ($i = 0; $i < sizeof($old); $i++) {
			$role = $old[$i]['DbMigrationUser']['type'];
			if (!is_null($old[$i]['OrdersUser']['type']) && empty($old[$i]['DbMigrationUser']['type'])) {
				$role = $old[$i]['OrdersUser']['type'];
			}
			$this->assertEqual($new[$i]['Role']['title'], $this->__getRoleFromOldType($role));
			$this->assertEqual($new[$i]['Profile']['name'], $old[$i]['DbMigrationUser']['lastname']);
			$this->assertEqual($new[$i]['Profile']['firstname'], $old[$i]['DbMigrationUser']['firstname']);
			$this->assertEqual($new[$i]['Profile']['society'], $old[$i]['DbMigrationUser']['sct']);
			$this->assertEqual($new[$i]['Profile']['gender'], $old[$i]['DbMigrationUser']['gender']);
			$this->assertEqual($new[$i]['User']['email'], $old[$i]['DbMigrationUser']['email']);
			$this->assertEqual($new[$i]['User']['created'], $old[$i]['DbMigrationUser']['created']);
		}
	}

	private function __getRoleFromOldType($type) {
		switch ($type) {
			case 'admin':
				return 'Admin';
			case 'superadmin':
				return 'SuperAdmin';
			case 'root':
				return 'Occitech';
			default:
				return 'Utilisateur';				
		}
	}

	public function testMigrateFolders() {
		$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
		$OldFolderModel = ClassRegistry::init('DbMigration.DbMigrationFolder');

		$this->DbMigration->relationOldUserNewUser = array(
			1 => 2, 2 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7,	8 => 8
		);

		$this->DbMigration->migrateFolders();

		$old = $OldFolderModel->find('all');
		$new = $UploadedFileModel->find('all');

		for ($i = 0; $i < sizeof($old); $i++) {
			$newUserIdExpected = 2;
			if (array_key_exists($old[$i]['DbMigrationFolder']['owner_id'], $this->DbMigration->relationOldUserNewUser)) {
				$newUserIdExpected = $this->DbMigration->relationOldUserNewUser[$old[$i]['DbMigrationFolder']['owner_id']];
			}
			$newParentIdExpected = null;
			if (!empty($old[$i]['DbMigrationFolder']['parent_id'])) {
				$newParentIdExpected = $this->DbMigration->relationOldFolderNewFolder[$old[$i]['DbMigrationFolder']['parent_id']];
			}
			$this->assertEqual($new[$i]['UploadedFile']['filename'], $old[$i]['DbMigrationFolder']['name']);
			$this->assertEqual($new[$i]['UploadedFile']['user_id'], $newUserIdExpected);
			$this->assertEqual($new[$i]['UploadedFile']['parent_id'], $newParentIdExpected);
		}
	}

	public function testMigrateFiles() {
		$this->_generateUploadFolder();
		$this->DbMigration->isInTest = true;
		$UploadedFileModel = ClassRegistry::init('Uploader.UploadedFile');
		$OldFileModel = ClassRegistry::init('DbMigration.DbMigrationFile');

		$this->DbMigration->relationOldUserNewUser = array(
			1 => 2, 2 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7,	8 => 8
		);

		$this->DbMigration->migrateFolders();
		$this->DbMigration->migrateFiles();

		$countAllFilesWithVersionOld = $OldFileModel->find('count');
		$countAllFilesWithVersionNew = $UploadedFileModel->FileStorage->find('count');

		$this->assertEqual($countAllFilesWithVersionNew, $countAllFilesWithVersionOld);

		$countAllFilesOld = $OldFileModel->find('count', array('conditions' => array(
			'version' => 1
		)));
		$countAllFilesNew = $UploadedFileModel->find('count', array('conditions' => array(
			'UploadedFile.is_folder' => 0
		)));

		$this->assertEqual($countAllFilesOld, $countAllFilesNew);
	}

	protected function _generateUploadFolder() {
		$tmpUploadFolder  = Configure::read('FileStorage.testFolder');
		if (file_exists($tmpUploadFolder)) {
			$this->_rmContentDir($tmpUploadFolder);
		} else {
			mkdir($tmpUploadFolder);
			chmod($tmpUploadFolder, 0777);
		}
		Configure::write('FileStorage.adapter', 'Test');
	}

	public function testMigrateComments() {
		$CommentModel = ClassRegistry::init('Comments.Comment');
		$OldCommentModel = ClassRegistry::init('DbMigration.DbMigrationComment');
		$UploadedFileModel = ClassRegistry::init('UploadedFile');

		$this->DbMigration->relationOldUserNewUser = array(
			1 => 2, 2 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7,	8 => 8
		);
		$this->DbMigration->relationOldFolderNewFolder = array(
			1 => 1, 2 => 2, 3 => 3, 16 => 4, 17 => 5, 26 => 6, 27 => 7,
			28 => 8, 29 => 9, 30 => 10, 31 => 11, 32 => 12, 36 => 13
		);
		$this->DbMigration->relationOldFileNewFile = array(
			1 => 13, 2 => 14, 3 => 15, 4 => 16, 5 => 17
		);

		$files = array(
			0 => array(
				'id' => 3,
				'filename' =>  'My folder',
				'parent_id' => null,
				'size' => 0,
				'user_id' => 3,
				'current_version' => 1,
				'available' => 1,
				'is_folder' => 1
			),
			1 => array(
				'id' => 16,
				'filename' =>  'Apress Beginning CakePHP From Novice to Professional Jul 2008.pdf',
				'parent_id' => $this->DbMigration->relationOldFolderNewFolder[3],
				'size' => 0,
				'user_id' => 3,
				'current_version' => 1,
				'available' => 1,
				'is_folder' => 1
			)
		);

		$UploadedFileModel->saveAll($files);

		$this->DbMigration->migrateComments();

		$old = $OldCommentModel->find('all');
		$new = $CommentModel->find('all');

		for ($i = 0; $i < sizeof($old); $i++) {
			$this->assertEqual($new[$i]['Comment']['user_id'], $this->DbMigration->relationOldUserNewUser[$old[$i]['DbMigrationComment']['user_id']]);
			$this->assertEqual($new[$i]['Comment']['title'], $old[$i]['DbMigrationComment']['file_name']);
			$this->assertEqual($new[$i]['Comment']['body'], $old[$i]['DbMigrationComment']['comment']);
			$this->assertEqual($new[$i]['Comment']['created'], $old[$i]['DbMigrationComment']['created']);
		}
	}

	public function testMigrateAlert() {
		$AlertModel = ClassRegistry::init('SynchroDossier.SdAlertEmail');
		$OldAlertModel = ClassRegistry::init('DbMigration.DbMigrationAlert');

		$this->DbMigration->relationOldUserNewUser = array(
			1 => 2, 2 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7,	8 => 8
		);
		$this->DbMigration->relationOldFolderNewFolder = array(
			1 => 1, 2 => 2, 3 => 3, 16 => 4, 17 => 5, 26 => 6, 27 => 7,
			28 => 8, 29 => 9, 30 => 10, 31 => 11, 32 => 12, 36 => 13
		);
		$this->DbMigration->relationOldFileNewFile = array(
			1 => 13, 2 => 14, 3 => 15, 4 => 16, 5 => 17
		);

		$this->DbMigration->migrateAlertsSubcription();

		$old = $OldAlertModel->find('all');
		$new = $AlertModel->find('all');

		for ($i = 0; $i < sizeof($old); $i++) {
			$this->assertEqual($new[$i]['SdAlertEmail']['user_id'], $this->DbMigration->relationOldUserNewUser[$old[$i]['DbMigrationAlert']['user_id']]);
			$this->assertEqual($new[$i]['SdAlertEmail']['uploaded_file_id'], $this->DbMigration->relationOldFolderNewFolder[$old[$i]['DbMigrationAlert']['folder_id']]);
		}
	}

	public function testMigrateAcl() {
		$PermissionModel = ClassRegistry::init('Permission');
		$OldAclModel = ClassRegistry::init('DbMigration.DbMigrationUserFolder');

		$this->DbMigration->migrateUser();
		$this->DbMigration->migrateFolders();
		$this->DbMigration->migrateAcl();

		$old = $OldAclModel->find('all');
		$new = $PermissionModel->find('all');

		for ($i = 0; $i < sizeof($old); $i++) {
			$this->assertEqual($new[$i]['Aco']['foreign_key'], $this->DbMigration->relationOldFolderNewFolder[$old[$i]['DbMigrationUserFolder']['group_id']]);
			$this->assertEqual($new[$i]['Aco']['model'], 'UploadedFile');
			$this->assertEqual($new[$i]['Aro']['foreign_key'], $this->DbMigration->relationOldUserNewUser[$old[$i]['DbMigrationUserFolder']['user_id']]);
			$this->assertEqual($new[$i]['Aro']['model'], 'User');

			if ($old[$i]['DbMigrationUserFolder']['perms'] == 'rw') {
				$this->assertEqual($new[$i]['Permission']['_create'], 1);
				$this->assertEqual($new[$i]['Permission']['_read'], 1);
			} elseif ($old[$i]['DbMigrationUserFolder']['perms'] == 'r') {
				$this->assertEqual($new[$i]['Permission']['_create'], 0);
				$this->assertEqual($new[$i]['Permission']['_read'], 1);
			}
		}
	}
}