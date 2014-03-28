<?php
/**
 * AroFixture
 *
 */
class SdUsersAroFixture extends CakeTestFixture {

	public $name = 'Aro';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'parent_id' => '2',
			'model' => 'Role',
			'foreign_key' => '1',
			'alias' => 'Role-admin',
			'lft' => '3',
			'rght' => '6'
		),
		array(
			'id' => '2',
			'parent_id' => '3',
			'model' => 'Role',
			'foreign_key' => '2',
			'alias' => 'Role-registered',
			'lft' => '2',
			'rght' => '7'
		),
		array(
			'id' => '3',
			'parent_id' => null,
			'model' => 'Role',
			'foreign_key' => '3',
			'alias' => 'Role-public',
			'lft' => '1',
			'rght' => '8'
		),
		array(
			'id' => '4',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '1',
			'alias' => 'admin',
			'lft' => '4',
			'rght' => '5'
		),
		array(
			'id' => '5',
			'parent_id' => null,
			'model' => 'Role',
			'foreign_key' => '4',
			'alias' => 'Role-sdSuperAdmin',
			'lft' => '9',
			'rght' => '12'
		),
		array(
			'id' => '6',
			'parent_id' => null,
			'model' => 'Role',
			'foreign_key' => '5',
			'alias' => 'Role-sdAdmin',
			'lft' => '13',
			'rght' => '14'
		),
		array(
			'id' => '7',
			'parent_id' => null,
			'model' => 'Role',
			'foreign_key' => '6',
			'alias' => 'Role-sdUtilisateur',
			'lft' => '15',
			'rght' => '18'
		),
		array(
			'id' => '8',
			'parent_id' => '7',
			'model' => 'User',
			'foreign_key' => '2',
			'alias' => 'aymeric',
			'lft' => '16',
			'rght' => '17'
		),
		array(
			'id' => '9',
			'parent_id' => '5',
			'model' => 'User',
			'foreign_key' => '3',
			'alias' => 'spadm1',
			'lft' => '10',
			'rght' => '11'
		),
		array(
			'id' => '10',
			'parent_id' => '6',
			'model' => 'User',
			'foreign_key' => '4',
			'alias' => 'adm1',
			'lft' => '19',
			'rght' => '20'
		),
		array(
			'id' => '11',
			'parent_id' => '6',
			'model' => 'User',
			'foreign_key' => '5',
			'alias' => 'adm2',
			'lft' => '21',
			'rght' => '22'
		),
	);

}
