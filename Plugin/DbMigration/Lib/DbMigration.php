<?php

ini_set('memory_limit', -1);

App::uses('DbMigrationOrdersUser', 'DbMigration.Model');
App::uses('DbMigrationUser', 'DbMigration.Model');
App::uses('DbMigrationOrder', 'DbMigration.Model');
App::uses('DbMigrationFolder', 'DbMigration.Model');
App::uses('DbMigrationFile', 'DbMigration.Model');
App::uses('DbMigrationComment', 'DbMigration.Model');
App::uses('DbMigrationAlert', 'DbMigration.Model');
App::uses('DbMigrationUserFolder', 'DbMigration.Model');
App::uses('DbMigrationUserFolder', 'DbMigration.Model');
App::uses('SdUser', 'SdUsers.Model');
App::uses('UploadedFile', 'Uploader.Model');
App::uses('FileStorage', 'FileStorage.Model');
App::uses('Comment', 'Uploader.Model');
App::uses('SdAlertEmail', 'SynchroDossier.Model');
App::uses('Aro', 'Model');
App::uses('Aco', 'Model');
App::uses('Permission', 'Model');

class DbMigration {

	private $__Shell;

/**
 * key => old
 * value => new
 */
	public $relationOldUserNewUser = array();
	public $relationOldFolderNewFolder = array();
	public $relationOldFileNewFile = array();

	public $oldUploadFolder = '/home/occitechprod/sd/espaceclient/files';
	
	public $isInTest = false;

	public function __construct($Shell) {
		$this->__Shell = $Shell;
		$this->DbMigrationUser = ClassRegistry::init('DbMigration.DbMigrationUser');
		$this->DbMigrationOrder = ClassRegistry::init('DbMigration.DbMigrationOrder');
		$this->DbMigrationFolder = ClassRegistry::init('DbMigration.DbMigrationFolder');
		$this->DbMigrationFile = ClassRegistry::init('DbMigration.DbMigrationFile');
		$this->DbMigrationComment = ClassRegistry::init('DbMigration.DbMigrationComment');
		$this->DbMigrationAlert = ClassRegistry::init('DbMigration.DbMigrationAlert');
		$this->DbMigrationUserFolder = ClassRegistry::init('DbMigration.DbMigrationUserFolder');
		$this->DbMigrationOrdersUser = ClassRegistry::init('DbMigration.DbMigrationOrdersUser');
		$this->DbMigrationUsersUser = ClassRegistry::init('DbMigration.DbMigrationUsersUser');
		$this->SdUser = ClassRegistry::init('SdUsers.SdUser');
		$this->UploadedFile = ClassRegistry::init('Uploader.UploadedFile');
		$this->Comment = ClassRegistry::init('Uploader.Comment');
		$this->SdAlertEmail = ClassRegistry::init('SynchroDossier.SdAlertEmail');
		$this->SdInformation = ClassRegistry::init('SynchroDossier.SdInformation');
		$this->Aro = ClassRegistry::init('Aro');
		$this->Aco = ClassRegistry::init('Aco');
		$this->Permission = ClassRegistry::init('Permission');
	}

/**
 * Reset Migration
 */
	public function reset() {
		$this->SdUser->deleteAll(array($this->SdUser->alias . '.id !=' => 1));
		$this->Aro->deleteAll(array($this->Aro->alias . '.id >' => 7));
		$this->Aco->deleteAll(array($this->Aco->alias . '.model' => 'UploadedFile'));
		$this->Permission->deleteAll(array($this->Permission->alias . '.id >' => 63));
		$this->UploadedFile->query('TRUNCATE TABLE ' . $this->UploadedFile->useTable);
		$this->Comment->query('TRUNCATE TABLE ' . $this->Comment->useTable);
		$this->SdAlertEmail->query('TRUNCATE TABLE ' . $this->SdAlertEmail->useTable);
		$this->UploadedFile->FileStorage->query('TRUNCATE TABLE ' . $this->UploadedFile->FileStorage->useTable);
	}

/**
 * Migration des utilisateurs et des profiles
 */
	public function migrateUser() {
		$this->__Shell->out('');
		$this->__Shell->out('User Migration');

		$oldUsersNotSoftDeleted = $this->DbMigrationUsersUser->find('all', array(
			'fields' => 'DISTINCT user_id',
			'recursive' => -1
		));
		$conditions = array('OR' => array(
			'DbMigrationUser.type' => array('root', 'admin', 'superadmin'),
			'DbMigrationUser.id' => Hash::extract($oldUsersNotSoftDeleted, '{n}.DbMigrationUsersUser.user_id')
		));
		$oldUsers = $this->DbMigrationUser->find('all', compact('conditions'));

		$result = true;
		foreach ($oldUsers as $user) {
			$role = $user['DbMigrationUser']['type'];
			if (!is_null($user['OrdersUser']['type']) && $user['DbMigrationUser']['type'] != 'root') {
				$role = $user['OrdersUser']['type'];
			}

			$user = $user['DbMigrationUser'];
			$newUser = array(
				'User' => array(
					'id' => null,
					'role_id' => $this->__getRoleIdFromOldType($role),
					'username' => strtolower($user['lastname'] . '.' . $user['firstname']),
					'name' => strtolower($user['lastname'] . '.' . $user['firstname']),
					'password' => $user['password'],
					'email' => $user['email'],
					'status' => 1,
					'updated' => $user['modified'],
					'created' => $user['created']
				),
				'Profile' => array(
					'name' => $user['lastname'],
					'firstname' => $user['firstname'],
					'society' => $user['sct'],
					'gender' => $user['gender'],
				)
			);
			$this->SdUser->create();
			$result = $this->SdUser->saveAssociated($newUser, array('callbacks' => 'after'));
			$this->relationOldUserNewUser[$user['id']] = $this->SdUser->id;

			if (!$result) {
				debug($newUser);
				break;
			}
		}

		if ($result) {
			$this->__Shell->out('User Migration Ok');
		} else {
			debug($this->SdUser->invalidFields());
			$this->__Shell->out('User Migration Error');
		}
		return $result;
	}

	private function __getRoleIdFromOldType($type) {
		switch ($type) {
			case 'admin':
				return Configure::read('sd.Admin.roleId');
			case 'superadmin':
				return Configure::read('sd.SuperAdmin.roleId');
			case 'root':
				return Configure::read('sd.Occitech.roleId');
			default:
				return Configure::read('sd.Utilisateur.roleId');
		}
	}

/**
 * Migration des dossiers
 */
	public function migrateFolders() {
		$this->__Shell->out('');
		$this->__Shell->out('Folders Migration');
		$this->__detachEvent('Model.UploadedFile.AfterSharingCreation');

		$oldFolders = $this->DbMigrationFolder->find('all');
		$result = true;

		foreach ($oldFolders as $folder) {
			$folder = $folder['DbMigrationFolder'];
			$newFolder = array('UploadedFile' => array(
					'filename' => $folder['name'],
			));

			$user['id'] = $this->__getNewUserId($folder['owner_id']);

			if ($folder['parent_id'] == 0) {
				$result = $this->UploadedFile->addSharing($newFolder, $user);
			} else {
				$result = $this->UploadedFile->addFolder(
					$newFolder,
					$this->relationOldFolderNewFolder[$folder['parent_id']],
					$user['id']
				);
			}

			$this->relationOldFolderNewFolder[$folder['id']] = $this->UploadedFile->id;

			if (!$result) {
				debug($newFolder);
				break;
			}
		}

		if ($result) {
			$this->__Shell->out('Folder Migration Ok');
		} else {
			debug($this->UploadedFile->invalidFields());
			$this->__Shell->out('Folder Migration Error');
		}
		return $result;
	}

/**
 * Migration des Fichiers
 */

	public function migrateFiles() {
		$this->__Shell->out('');
		$this->__Shell->out('Files Migration');
		$this->__detachEvent('Model.UploadedFile.AfterSharingCreation');
		$this->__detachEvent('Model.UploadedFile.afterUploadFailed');
		$this->__detachEvent('Model.UploadedFile.afterUploadSuccess');


		$oldFiles = $this->DbMigrationFile->find('all');
		$result = true;

		foreach ($oldFiles as $file) {
			$file = $file['DbMigrationFile'];

			$filePath = $this->__getFilePath($file['hash']);

			$data = array('file' => array(
					'name' => $file['name'],
					'tmp_name' => $filePath,
					'size' => $file['size'],
					'type' => $this->__cleanMimeType($file['mime']),
					'error' => 0
			));

			$user['id'] = $this->__getNewUserId($file['user_id']);
			$parentId = $this->relationOldFolderNewFolder[$file['folder_id']];

			$this->UploadedFile->upload($data, $user, $parentId);

			$newFile = $this->UploadedFile->findById($this->UploadedFile->id);
			if ($newFile['UploadedFile']['current_version'] == 1) {
				$AcoModel = ClassRegistry::init('Aco');
				$acoParent = $AcoModel->findByForeign_key($parentId);
				$newAco = array('Aco' => array(
					'id' => null,
					'model' => 'UploadedFile',
					'foreign_key' => $this->UploadedFile->id,
					'parent_id' => $acoParent['Aco']['id']
				));

				$AcoModel->save($newAco);
			}

			$this->UploadedFile->FileStorage->saveField('created', $file['created']);
		}

		if ($result) {
			$this->__Shell->out('File Migration Ok');
		} else {
			debug($this->UploadedFile->invalidFields());
			debug($this->UploadedFile->FileStorage->invalidFields());
			$this->__Shell->out('File Migration Error');
		}
		return $result;
	}

	private function __getFilePath($filename) {
		if ($this->isInTest) {
			return tempnam('RandomFolder_LikeThat_Tempnam_CreateATempFolder','myPrefix');
		}
		return $this->oldUploadFolder . '/' . $filename[0] . '/' . $filename[1] . '/' . $filename;
	}

	private function __cleanMimeType($oldMime) {
		return str_replace('; charset=binary', '', $oldMime);
	}

/**
 * Migrate comments
 */
	public function migrateComments() {
		$this->__Shell->out('');
		$this->__Shell->out('Comments Migration');


		$oldComments = $this->DbMigrationComment->find('all');
		$result = true;

		foreach ($oldComments as $comment) {
			$comment = $comment['DbMigrationComment'];

			list($name, $email) = $this->__getNameAndEmailByUserId($comment['user_id']);
			$foreignKey = $this->__getForeignKeyFromFolderIdAndFilename($comment['folder_id'], $comment['file_name']);

			$newComment = array(
				'model' => 'UploadedFile',
				'foreign_key' => $foreignKey,
				'user_id' => $this->__getNewUserId($comment['user_id']),
				'name' => $name,
				'email' => $email,
				'title' => $comment['file_name'],
				'body' => $comment['comment'],
				'created' => $comment['created'],
				'status' => 1
			);
			$this->Comment->create();
			$result = $this->Comment->save($newComment);
			if (!$result) {
				break;
			}
		}

		if ($result) {
			$this->__Shell->out('Comments Migration Ok');
		} else {
			$this->__Shell->out('Comments Migration Error');
		}
		return $result;
	}

	private function __getForeignKeyFromFolderIdAndFilename($oldFolderId, $filename) {
		$newFolderId = $this->relationOldFolderNewFolder[$oldFolderId];
		$file = $this->UploadedFile->findByFilenameAndParent_id($filename, $newFolderId);
		return $file['UploadedFile']['id'];
	}

	private function __getNameAndEmailByUserId($userId) {
		$user = $this->DbMigrationUser->findById($userId);
		$user = $user['DbMigrationUser'];

		$name = strtolower($user['lastname'] . '.' . $user['firstname']);
		$email = $user['email'];
		return array($name, $email);
	}

/**
 * Migration Alert subcription
 */
	public function migrateAlertsSubcription() {
		$this->__Shell->out('');
		$this->__Shell->out('Alerts Subscription Migration');


		$oldAlertsSubscipt = $this->DbMigrationAlert->find('all');
		$result = true;

		foreach ($oldAlertsSubscipt as $alertSubscipt) {
			$alertSubscipt = $alertSubscipt['DbMigrationAlert'];

			// Le if est la pour un cas spécial, lorsque le dossier a été supprimé et pas l'alerte 
			if (array_key_exists($alertSubscipt['folder_id'], $this->relationOldFolderNewFolder)) {
				$newAlertSubscipt = array(
					'user_id' => $this->relationOldUserNewUser[$alertSubscipt['user_id']],
					'uploaded_file_id' => $this->relationOldFolderNewFolder[$alertSubscipt['folder_id']]	
				);
				$this->SdAlertEmail->create();
				$result = $this->SdAlertEmail->save($newAlertSubscipt);
				if (!$result) {
					break;
				}
			}
		}

		if ($result) {
			$this->__Shell->out('Alerts Subscription Migration Ok');
		} else {
			$this->__Shell->out('Alerts Subscription Migration Error');
		}
		return $result;
	}

/**
 * Migrate ACL
 */
	public function migrateAcl() {
		$this->__Shell->out('');
		$this->__Shell->out('Acls Migration');

		$oldAcls = $this->DbMigrationUserFolder->find('all');
		
		foreach ($oldAcls as $acl) {
			$acl = $acl['DbMigrationUserFolder'];

			$rights = $this->__getAclRigthNewFormat($acl['perms']);
			$newAcl = array(
				'id' => null,
				'aro_id' => $this->__getAroIdFromOldUserId($acl['user_id']),
				'aco_id' => $this->__getAcoIdFromOldFolderId($acl['group_id']),
				'_create' => $rights['_create'],
				'_read' => $rights['_read'],
				'_update' => $rights['_update'],
				'_delete' => $rights['_delete'],
				'_change_right' => $rights['_change_right'],
			);
			$result = $this->Permission->save($newAcl);
			if (!$result) {
				break;
			}
		}

		if ($result) {
			$this->__Shell->out('Acl Migration Ok');
		} else {
			debug($newAcl);
			debug($this->Permission->invalidFields());
			$this->__Shell->out('Acl Migration Error');
		}
		return $result;
	}

	private function __getAroIdFromOldUserId($userId) {
		$newUserId = $this->relationOldUserNewUser[$userId];
		$aro = $this->Aro->findByModelAndForeign_key('User', $newUserId);

		return $aro['Aro']['id'];
	}

	private function __getAcoIdFromOldFolderId($folderId) {
		$newFolderId = $this->relationOldFolderNewFolder[$folderId];
		$aco = $this->Aco->findByModelAndForeign_key('UploadedFile', $newFolderId);

		return $aco['Aco']['id'];
	}

	private function __getAclRigthNewFormat($lastFormat) {
		$rights = array(
			'_create' => 0,
			'_read' => 0,
			'_delete' => 0,
			'_update' => 0,
			'_change_right' => 0,
		);

		if (strpos($lastFormat, 'r') !== false) {
			$rights['_read'] = 1;
		}
		if (strpos($lastFormat, 'w') !== false) {
			$rights['_create'] = 1;
		}

		return $rights;
	}

/**
 * Migrate SdInformation
 */
	public function migrateInfos() {
		$this->__Shell->out('');
		$this->__Shell->out('Default Settings Migration');

		$oldSettings = $this->DbMigrationOrder->find('first', array('fields' => array('quota', 'used')));
		$quota_mb = $oldSettings['DbMigrationOrder']['quota'];
		$current_quota_mb = $oldSettings['DbMigrationOrder']['used'] / 1024;

		$this->SdInformation->id = 1;
		$this->SdInformation->saveField('quota_mb', $quota_mb);
		$this->SdInformation->saveField('current_quota_mb', $current_quota_mb);
		return true;
	}

/**
 * General private method
 */
	private function __detachEvent($eventName) {
		CakeEventManager::instance()->detach(null, $eventName);
		foreach(CakeEventManager::instance()->listeners($eventName) as $listner) {
			CakeEventManager::instance()->detach($listner['callable'], $eventName);
		}
	}

	private function __getNewUserId($oldId) {
		if (!array_key_exists($oldId, $this->relationOldUserNewUser)) {
			return 1;
		} else {
			return $this->relationOldUserNewUser[$oldId];
		}
	}
}