<?php
App::uses('DbMigrationAppModel', 'DbMigration.Model');

class DbMigrationUser extends DbMigrationAppModel {

	public $useTable = 'users';

	public $hasOne = array(
		'OrdersUser' => array(
			'className' => 'DbMigrationOrdersUser',
			'foreignKey' => 'user_id',
		)
	);
}
