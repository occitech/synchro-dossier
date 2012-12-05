<?php

class DbMigrationShell extends AppShell {

	public $uses = array(
		'DbMigration.DbMigrationUser',
		'SdUsers.SdUser',
		'DbMigration.DbMigrationFolder',
		'DbMigration.DbMigrationFile',
		'Uploader.UploadedFile',
		'DbMigration.DbMigrationComment',
		'Uploader.Comment',
		'DbMigration.DbMigrationAlert',
		'SynchroDossier.SdAlertEmail',
		'Aro',
		'Aco',
		'DbMigration.DbMigrationUserFolder',
		'Permission',

	);

/**
 * key => old
 * value => new
 */
	private $__RelationOldUserNewUser = array();
	private $__RelationOldFolderNewFolder = array();

	private $__oldUploadFolder = 'uploads/old/';

	public function reset() {
		$this->SdUser->deleteAll(array($this->SdUser->alias . '.id !=' => 1));
		$this->Aro->deleteAll(array($this->Aro->alias . '.id >' => 7));
		$this->Aco->deleteAll(array($this->Aco->alias . '.model' => 'UploadedFile'));
		$this->Permission->deleteAll(array($this->Permission->alias . '.id >' => 63));
		$this->UploadedFile->query('TRUNCATE TABLE ' . $this->UploadedFile->useTable);
		$this->Comment->query('TRUNCATE TABLE ' . $this->Comment->useTable);
		$this->SdAlertEmail->query('TRUNCATE TABLE ' . $this->SdAlertEmail->useTable);
		$this->UploadedFile->FileStorage->query('TRUNCATE TABLE ' . $this->UploadedFile->FileStorage->useTable);

		$this->out('All migrations removed');
	}

	public function main() {
		$result =
			$this->_migrateUser() &&
			$this->_migrateFolders() &&
			$this->_migrateFiles() &&
			$this->_migrateComments() &&
			$this->_migrateAlertsSubcription() &&
			$this->_migrateAcl();

		if ($result) {
			$this->out('Toutes les migrations ont réussies, normal je m\'appelle Chuck Norris !');
		} else {
			$this->out('Une migration a échouée ... même Chuck ne peut rien faire !');
		}
	}

/**
 * Migration des utilisateurs et des profiles
 */
	protected function _migrateUser() {
		$this->out('');
		$this->out('User Migration');

		$oldUsers = $this->DbMigrationUser->find('all');
		$newUsers = array();
		$result = true;

		foreach ($oldUsers as $user) {
			$user = $user['DbMigrationUser'];

			$newUser = array(
				'User' => array(
					'id' => null,
					'role_id' => $this->__getRoleIdFromOldType($user['type']),
					'username' => strtolower($user['lastname'] . '.' . $user['firstname']),
					'password' => '@TODO : Mot de passe à regénérer ?',
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
			$result = $this->SdUser->saveAssociated($newUser);
			$this->__RelationOldUserNewUser[$user['id']] = $this->SdUser->id;
	
			if (!$result) {
				debug($newUser);
				break;
			}
		}

		if ($result) {
			$this->out('User Migration Ok');
		} else {
			debug($this->SdUser->invalidFields());
			$this->out('User Migration Error');
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
	protected function _migrateFolders() {
		$this->out('');
		$this->out('Folders Migration');
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
					$this->__RelationOldFolderNewFolder[$folder['parent_id']],
					$user['id']
				);
			}

			$this->__RelationOldFolderNewFolder[$folder['id']] = $this->UploadedFile->id;

			if (!$result) {
				debug($newFolder);
				break;
			}
		}

		if ($result) {
			$this->out('Folder Migration Ok');
		} else {
			debug($this->UploadedFile->invalidFields());
			$this->out('Folder Migration Error');
		}
		return $result;
	}

/**
 * Migration des Fichiers
 */

	protected function _migrateFiles() {
		$this->out('');
		$this->out('Files Migration');
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
			$parentId = $this->__RelationOldFolderNewFolder[$file['folder_id']];

			$this->UploadedFile->upload($data, $user, $parentId);

			$this->UploadedFile->FileStorage->saveField('created', $file['created']);
		}

		if ($result) {
			$this->out('File Migration Ok');
		} else {
			debug($this->UploadedFile->invalidFields());
			debug($this->UploadedFile->FileStorage->invalidFields());
			$this->out('File Migration Error');
		}
		return $result;
	}

	private function __getFilePath($filename) {
		return $this->__oldUploadFolder . '/' . $filename[0] . '/' . $filename[1] . '/' . substr($filename, 2);
	}

	private function __cleanMimeType($oldMime) {
		return str_replace('; charset=binary', '', $oldMime);
	}

/**
 * Migrate comments
 */
	protected function _migrateComments() {
		$this->out('');
		$this->out('Comments Migration');


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
			$this->out('Comments Migration Ok');
		} else {
			$this->out('Comments Migration Error');
		}
		return $result;
	}

	private function __getForeignKeyFromFolderIdAndFilename($oldFolderId, $filename) {
		$newFolderId = $this->__RelationOldFolderNewFolder[$oldFolderId];
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
	protected function _migrateAlertsSubcription() {
		$this->out('');
		$this->out('Alerts Subscription Migration');


		$oldAlertsSubscipt = $this->DbMigrationAlert->find('all');
		$result = true;

		foreach ($oldAlertsSubscipt as $alertSubscipt) {
			$alertSubscipt = $alertSubscipt['DbMigrationAlert'];
			$newAlertSubscipt = array(
				'user_id' => $this->__RelationOldUserNewUser[$alertSubscipt['user_id']],
				'uploaded_file_id' => $this->__RelationOldFolderNewFolder[$alertSubscipt['folder_id']]	
			);
			$this->SdAlertEmail->create();
			$result = $this->SdAlertEmail->save($newAlertSubscipt);
			if (!$result) {
				break;
			}
		}

		if ($result) {
			$this->out('Alerts Subscription Migration Ok');
		} else {
			$this->out('Alerts Subscription Migration Error');
		}
		return $result;
	}

/**
 * Migrate ACL
 */
	protected function _migrateAcl() {
		$this->out('');
		$this->out('Acls Migration');

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
			$this->out('Acl Migration Ok');
		} else {
			debug($newAcl);
			debug($this->Permission->invalidFields());
			$this->out('Acl Migration Error');
		}
		return $result;
	}

	private function __getAroIdFromOldUserId($userId) {
		$newUserId = $this->__RelationOldUserNewUser[$userId];
		$aro = $this->Aro->findByModelAndForeign_key('User', $newUserId);

		return $aro['Aro']['id'];
	}

	private function __getAcoIdFromOldFolderId($folderId) {
		$newFolderId = $this->__RelationOldFolderNewFolder[$folderId];
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
 * General private method
 */
	private function __detachEvent($eventName) {
		CakeEventManager::instance()->detach(null, $eventName);
		foreach(CakeEventManager::instance()->listeners($eventName) as $listner) {
			CakeEventManager::instance()->detach($listner['callable'], $eventName);
		}
	}

	private function __getNewUserId($oldId) {
		if (!array_key_exists($oldId, $this->__RelationOldUserNewUser)) {
			return 1;
		} else {
			return $this->__RelationOldUserNewUser[$oldId];
		}
	}
}