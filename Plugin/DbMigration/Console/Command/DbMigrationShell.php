<?php

class DbMigrationShell extends AppShell {

	public $uses = array(
		'DbMigration.DbMigrationUser',
		'SdUsers.SdUser',
		'DbMigration.DbMigrationFolder',
		'DbMigration.DbMigrationFile',
		'Uploader.UploadedFile',
	);

	/**
	 * key => old
	 * value => new
	 */
	private $__RelationOldUserNewUser = array();
	private $__RelationOldFolderNewFolder = array();

	private $__oldUploadFolder = 'uploads/old/';

	public function main() {
		$result =
			$this->_migrateUser() &&
			$this->_migrateFolders() &&
			$this->_migrateFiles();

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
				)
			);
			$result = $this->SdUser->save($newUser);
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

	private function __addUploadedFile($oldFile) {
		$newFile = array(
			'filename' => $oldFile['name'],
			'size' => $oldFile['size'],
			'user_id' => $this->__getNewUserId($oldFile['user_id']),
			'mime_type' => $oldFile['mime'],
			'current_version' => 1,
			'parent_id' => $this->__RelationOldFolderNewFolder[$oldFile['folder_id']],
			'is_folder' => 0
		);
		return $this->UploadedFile->save($newFile);
	}

	private function __addFileStorage($oldFile) {
		// @FIXME
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