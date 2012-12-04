<?php

class DbMigrationShell extends AppShell {

	public $uses = array(
		'DbMigration.DbMigrationUser', 'SdUsers.SdUser'
	);

	public function main() {
		$this->out('User Migration');
		$this->out('===============');
		$result = $this->_migrateUser();

		if ($result) {
			$this->out('Toutes les migrations ont réussies, normal je m\'appelle Chuck Norris !');
		} else {
			$this->out('Une migration a échouée ... même Chuck ne peut rien faire !');
		}
	}

	protected function _migrateUser() {
		$oldUsers = $this->DbMigrationUser->find('all');
		$newUsers = array();
	
		foreach ($oldUsers as $user) {
			$user = $user['DbMigrationUser'];
			$newUser = array(
				'User' => array(
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
			$newUsers[] = $newUser;
		}
		if ($this->SdUser->saveAll($newUsers, array('deep' => true))) {
			$this->out('User Migration Ok');
			return true;
		} else {
			debug($this->SdUser->invalidFields());
			$this->out('User Migration Error');
			return false;
		}
	}
}