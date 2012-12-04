<?php
App::uses('DbMigrationAppModel', 'DbMigration.Model');

class DbMigrationFile extends DbMigrationAppModel {

	public $useDbConfig = 'old';

	public $useTable = 'files';

	public $belongsTo = array(
		// 'User' => array(
		// 	'className' => 'DbMigrationUser',
		// 	'foreignKey' => 'user_id'
		// ),
		// 'Folder' => array(
		// 	'className' => 'DbMigrationFolder',
		// 	'foreignKey' => 'folder_id'
		// )
	);
}
