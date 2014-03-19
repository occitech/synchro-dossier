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
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array();

}
