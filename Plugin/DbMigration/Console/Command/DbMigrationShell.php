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
		'Aro',
		'Aco',
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
		$this->UploadedFile->query('TRUNCATE TABLE ' . $this->UploadedFile->useTable);
		$this->Comment->query('TRUNCATE TABLE ' . $this->Comment->useTable);
		$this->UploadedFile->FileStorage->query('TRUNCATE TABLE ' . $this->UploadedFile->FileStorage->useTable);

		$this->out('All migrations removed');
	}

	public function main() {
		$result =
			$this->_migrateUser() &&
			$this->_migrateFolders() &&
			$this->_migrateFiles() &&
			$this->_migrateComments();

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
		$this->out('User Migration');
		$this->out('===============');

		$oldUsers = $this->DbMigrationUser->find('all');
		$newUsers = array();
		$result = true;

		foreach ($oldUsers as $user) {
			$user = $user['DbMigrationUser'];
			$newUser = array(
				'User' => array(
					'id' => null,
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

/**
 * Migration des dossiers
 */
	protected function _migrateFolders() {
		$this->out('Folders Migration');
		$this->out('=================');
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
		$this->out('Files Migration');
		$this->out('=================');
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
					'type' => $file['mime'],
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

/**
 * Migrate comments
 */
	protected function _migrateComments() {
		$this->out('Comments Migration');
		$this->out('=================');


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
				'created' => $comment['created']
			);
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