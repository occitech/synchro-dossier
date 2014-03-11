<?php
/**
 * UserFixture
 *
 */
class SdUsersCollaborationFixture extends CakeTestFixture {

	public $name = 'UsersCollaboration';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' =>'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'false' => null, 'length' => 20),
		'parent_id' => array('type' => 'integer', 'null' => false, 'false' => null, 'length' => 20),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
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
			'user_id' => '2',
			'parent_id' => '1'
		),
		array(
			'id' => '2',
			'user_id' => '3',
			'parent_id' => '1'
		),
		array(
			'id' => '3',
			'user_id' => '4',
			'parent_id' => '1'
		),
		array(
			'id' => '4',
			'user_id' => '5',
			'parent_id' => '1'
		),
		array(
			'id' => '5',
			'user_id' => '6',
			'parent_id' => '4'
		),
		array(
			'id' => '6',
			'user_id' => '2',
			'parent_id' => '4'
		),
		array(
			'id' => '7',
			'user_id' => '2',
			'parent_id' => '5'
		),
	);

}
