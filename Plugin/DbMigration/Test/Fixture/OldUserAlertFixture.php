<?php

class OldUserAlertFixture extends CakeTestFixture {

	public $useDbConfig = 'testold';

	public $table = 'user_alerts';

	public $fields = array(
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'key' => 'primary'),
		'folder_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'key' => 'primary'),
		'alert' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => array('user_id', 'folder_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'user_id' => '2',
			'folder_id' => '3',
			'alert' => 1
		),
		array(
			'user_id' => '2',
			'folder_id' => '30',
			'alert' => 1
		),
		array(
			'user_id' => '2',
			'folder_id' => '31',
			'alert' => 1
		),
		array(
			'user_id' => '2',
			'folder_id' => '36',
			'alert' => 1
		),
		array(
			'user_id' => '4',
			'folder_id' => '2',
			'alert' => 1
		),
		array(
			'user_id' => '7',
			'folder_id' => '2',
			'alert' => 1
		),
		array(
			'user_id' => '7',
			'folder_id' => '3',
			'alert' => 1
		),
		array(
			'user_id' => '7',
			'folder_id' => '16',
			'alert' => 1
		),
		array(
			'user_id' => '7',
			'folder_id' => '26',
			'alert' => 1
		),
	);

}
