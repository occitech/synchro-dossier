<?php
App::uses('DbMigrationAppModel', 'DbMigration.Model');

class DbMigrationOrdersUser extends DbMigrationAppModel {

	public $useTable = 'orders_users';
}
