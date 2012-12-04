<?php
App::uses('DbMigrationAppModel', 'DbMigration.Model');
/**
 * Folder Model
 *
 * @property Folder $ParentFolder
 * @property Owner $Owner
 * @property File $File
 * @property FilesComment $FilesComment
 * @property Folder $ChildFolder
 * @property User $User
 */
class DbMigrationFolder extends DbMigrationAppModel {

	public $useDbConfig = 'old';

	public $useTable = 'folders';

	public $belongsTo = array(
		// 'ParentFolder' => array(
		// 	'className' => 'DbMigrationFolder',
		// 	'foreignKey' => 'parent_id'
		// ),
		// 'User' => array(
		// 	'className' => 'DbMigrationUser',
		// 	'foreignKey' => 'owner_id'
		// )
	);

	public $hasMany = array(
		// 'File' => array(
		// 	'className' => 'DbMigrationFile',
		// 	'foreignKey' => 'folder_id'
		// ),
		// 'FilesComment' => array(
		// 	'className' => 'DbMigrationFilesComment',
		// 	'foreignKey' => 'folder_id'
		// ),
		// 'ChildFolder' => array(
		// 	'className' => 'DbMigrationFolder',
		// 	'foreignKey' => 'parent_id'
		// )
	);
}
