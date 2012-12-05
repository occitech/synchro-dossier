<?php
App::uses('DbMigrationAppModel', 'DbMigration.Model');
/**
 * UserFolder Model
 *
 */
class DbMigrationUserFolder extends DbMigrationAppModel {

	public $useDbConfig = 'old';

	public $useTable = 'user_folders';
}
