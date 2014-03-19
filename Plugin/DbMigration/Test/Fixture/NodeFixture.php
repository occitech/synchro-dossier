<?php

class NodeFixture extends CroogoTestFixture {

	public $name = 'Node';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
		'title' => array('type' => 'string', 'null' => false, 'default' => null),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique'),
		'body' => array('type' => 'text', 'null' => false, 'default' => null, 'key' => 'index'),
		'excerpt' => array('type' => 'text', 'null' => true, 'default' => null),
		'status' => array('type' => 'integer', 'length' => 1, 'null' => false, 'default' => '0'),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'comment_status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1),
		'comment_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'promote' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null),
		'terms' => array('type' => 'text', 'null' => true, 'default' => null),
		'sticky' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null),
		'visibility_roles' => array('type' => 'text', 'null' => true, 'default' => null),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'node', 'length' => 100),
		'publish_start' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'publish_end' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'slug' => array('column' => 'slug', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $records = array();
}
