<?php

class DbMigrationShell extends AppShell {

	public $uses = array(
		'DbMigration.DbMigrationUser',
		'SdUsers.SdUser',
		'DbMigration.DbMigrationFolder',
		'Uploader.UploadedFile',
	);

	/**
	 * key => old
	 * value => new
	 */
	private $__RelationOldUserNewUser = array();
	private $__RelationOldFolderNewFolder = array();

	public function main() {
		$result =
			$this->_migrateUser() &&
			$this->_migrateFolders();

		if ($result) {
			$this->out('Toutes les migrations ont réussies, normal je m\'appelle Chuck Norris !');
		} else {
			$this->out('Une migration a échouée ... même Chuck ne peut rien faire !');
		}
	}

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

	protected function _migrateFolders() {
		$this->out('Folders Migration');
		$this->out('=================');
		$this->__detachEvent('Model.UploadedFile.AfterSharingCreation');

		$oldFolders = $this->DbMigrationFolder->find('all');
		$newFolders = array();
		$result = true;

		foreach ($oldFolders as $folder) {
			$folder = $folder['DbMigrationFolder'];
			$newFolder = array('UploadedFile' => array(
					'filename' => $folder['name'],
			));

			if (is_null($folder['owner_id'])) {
				$user['id'] = 1;
			} else {
				$user['id'] = $this->__RelationOldUserNewUser[$folder['owner_id']];
			}

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

	private function __detachEvent($eventName) {
		CakeEventManager::instance()->detach(null, $eventName);
		foreach(CakeEventManager::instance()->listeners($eventName) as $listner) {
			CakeEventManager::instance()->detach($listner['callable'], $eventName);
		}
	}
}