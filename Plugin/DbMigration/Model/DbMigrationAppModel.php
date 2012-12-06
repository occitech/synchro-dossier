<?php

class DbMigrationAppModel extends AppModel {

	public $useDbConfig = 'old';

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		if (!is_null(Configure::read('DbMigration.db'))) {
			$this->useDbConfig = Configure::read('DbMigration.db');
		}
	}
}

