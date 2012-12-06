<?php

App::uses('DbMigration', 'DbMigration.Lib');

class DbMigrationShell extends AppShell {

	public function initialize() {
		parent::initialize();
		$this->DbMigration = new DbMigration($this);
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
			$this->DbMigration->migrateAcl();

		if ($result) {
			$this->success('Toutes les migrations ont réussies, normal je m\'appelle Chuck Norris !');
		} else {
			$this->warning('Une migration a échouée ... même Chuck ne peut rien faire !');
		}
	}
}