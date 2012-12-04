<?php
App::uses('DbMigrationAppModel', 'DbMigration.Model');

class DbMigrationUser extends DbMigrationAppModel {

	public $useDbConfig = 'old';

	public $useTable = 'users';

	public $hasMany = array(
		// 'File' => array(
		// 	'className' => 'DbMigrationFile',
		// 	'foreignKey' => 'user_id',
		// ),
		// 'FilesComment' => array(
		// 	'className' => 'DbMigrationFilesComment',
		// 	'foreignKey' => 'user_id',
		// ),
		// 'UserAlert' => array(
		// 	'className' => 'DbMigrationUserAlert',
		// 	'foreignKey' => 'user_id',
		// ),
		// 'UserFolder' => array(
		// 	'className' => 'DbMigrationUserFolder',
		// 	'foreignKey' => 'user_id',
		// )
	);

}
