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
		// 	'className' => 'Folder',
		// 	'foreignKey' => 'parent_id'
		// ),
		// 'User' => array(
		// 	'className' => 'User',
		// 	'foreignKey' => 'owner_id'
		// )
	);

	public $hasMany = array(
		// 'File' => array(
		// 	'className' => 'File',
		// 	'foreignKey' => 'folder_id'
		// ),
		// 'FilesComment' => array(
		// 	'className' => 'FilesComment',
		// 	'foreignKey' => 'folder_id'
		// ),
		// 'ChildFolder' => array(
		// 	'className' => 'Folder',
		// 	'foreignKey' => 'parent_id'
		// )
	);
}
