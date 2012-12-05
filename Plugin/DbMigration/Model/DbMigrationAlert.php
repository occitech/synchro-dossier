<?php
App::uses('DbMigrationAppModel', 'DbMigration.Model');
/**
 * UserAlert Model
 *
 */
class DbMigrationAlert extends DbMigrationAppModel {

	public $useDbConfig = 'old';

	public $useTable = 'user_alerts';
}
