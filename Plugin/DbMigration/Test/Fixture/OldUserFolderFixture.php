<?php
/**
 * UserFolderFixture
 *
 */
class OldUserFolderFixture extends CakeTestFixture {

	public $useDbConfig = 'testold';

	public $table = 'user_folders';

	public $fields = array(
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'perms' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => array('user_id', 'group_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'user_id' => '2',
			'group_id' => '2',
			'perms' => 'rw'
		),
		array(
			'user_id' => '2',
			'group_id' => '3',
			'perms' => 'rw'
		),
		array(
			'user_id' => '2',
			'group_id' => '16',
			'perms' => 'rw'
		),
		array(
			'user_id' => '2',
			'group_id' => '17',
			'perms' => 'rw'
		),
		array(
			'user_id' => '2',
			'group_id' => '26',
			'perms' => 'rw'
		),
		array(
			'user_id' => '2',
			'group_id' => '30',
			'perms' => 'rw'
		),
		array(
			'user_id' => '2',
			'group_id' => '31',
			'perms' => 'rw'
		),
		array(
			'user_id' => '2',
			'group_id' => '32',
			'perms' => 'rw'
		),
		array(
			'user_id' => '2',
			'group_id' => '36',
			'perms' => 'rw'
		),
		array(
			'user_id' => '4',
			'group_id' => '2',
			'perms' => 'r'
		),
		array(
			'user_id' => '6',
			'group_id' => '3',
			'perms' => 'r'
		),
		array(
			'user_id' => '7',
			'group_id' => '3',
			'perms' => 'r'
		),
		array(
			'user_id' => '7',
			'group_id' => '26',
			'perms' => 'rw'
		),
	);

}
