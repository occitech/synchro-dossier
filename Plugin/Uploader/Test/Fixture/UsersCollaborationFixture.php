<?php
/**
 * UsersCollaborationFixture
 *
 */
class UsersCollaborationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
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
		'parent_id' => '1',
		'created' => null,
		'modified' => null
	),
	array(
		'id' => '2',
		'user_id' => '3',
		'parent_id' => '1',
		'created' => null,
		'modified' => null
	),
	array(
		'id' => '3',
		'user_id' => '4',
		'parent_id' => '1',
		'created' => null,
		'modified' => null
	),
	array(
		'id' => '4',
		'user_id' => '5',
		'parent_id' => '1',
		'created' => null,
		'modified' => null
	),
	array(
		'id' => '5',
		'user_id' => '6',
		'parent_id' => '4',
		'created' => null,
		'modified' => null
	),
	array(
		'id' => '6',
		'user_id' => '2',
		'parent_id' => '4',
		'created' => null,
		'modified' => null
	),
	array(
		'id' => '7',
		'user_id' => '2',
		'parent_id' => '5',
		'created' => null,
		'modified' => null
	),
);

}
