<?php

class SettingFixture extends CroogoTestFixture {

	public $name = 'Setting';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'key' => 'unique'),
		'value' => array('type' => 'text', 'null' => false, 'default' => null),
		'title' => array('type' => 'string', 'null' => false, 'default' => null),
		'description' => array('type' => 'string', 'null' => false, 'default' => null),
		'input_type' => array('type' => 'string', 'null' => false, 'default' => 'text'),
		'editable' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'weight' => array('type' => 'integer', 'null' => true, 'default' => null),
		'params' => array('type' => 'text', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'key' => array('column' => 'key', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $records = array();
}
