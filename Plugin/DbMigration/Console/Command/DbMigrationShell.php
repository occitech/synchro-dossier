<?php

App::uses('DbMigration', 'DbMigration.Lib');

class DbMigrationShell extends AppShell {

	public function initialize() {
		parent::initialize();
		$this->DbMigration = new DbMigration($this);
	}

	public function admin_reset() {
		$newPassword = rand (111111, 999999);
		$email = 'admin@occi-tech.com';

		$UserModel = ClassRegistry::init('Users.User');
		$admin = $UserModel->findById(1);
		$UserModel->id = 1;
		$UserModel->Behaviors->unload('Acl');
		$UserModel->saveField('password', $newPassword);
		$UserModel->saveField('email', 'admin@occi-tech.com');

		$this->out('Le nouvel email admin est : ' . $email);
		$this->out('Le nouveau password admin est : ' . $newPassword);
	}

	public function reset() {
		$this->DbMigration->reset();
		$this->success('All migrations removed');
	}

	public function main() {
		$result =
			$this->DbMigration->migrateUser() &&
			$this->DbMigration->migrateFolders() &&
			$this->DbMigration->migrateFiles() &&
			$this->DbMigration->migrateComments() &&
			$this->DbMigration->migrateAlertsSubcription() &&
			$this->DbMigration->migrateAcl() &&
			$this->DbMigration->migrateInfos();	

		if ($result) {
			$this->success('Toutes les migrations ont réussies, normal je m\'appel Chuck Norris !');
			$this->hr();
			$this->out('ATTENTION IL FAUT IMPORTER DANS LES CONFS DE CROOGO LE SALT DU SYNCHRODOSSER');
		} else {
			$this->warn('Une migration a échouée ... même Chuck ne peut rien faire !');
		}
	}
}