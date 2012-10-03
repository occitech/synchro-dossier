<?php
/**
 * UploadedFileFixture
 *
 */
class UploadedFileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'size' => array('type' => 'integer', 'null' => false, 'default' => null),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'current_version' => array('type' => 'integer', 'null' => false, 'default' => null),
		'available' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'is_folder' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => null),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 1,
			'user_id' => 1,
			'current_version' => 1,
			'available' => 1,
			'parent_id' => 1,
			'is_folder' => 1,
			'lft' => 1,
			'rght' => 1
		),
		array(
			'id' => 2,
			'filename' => 'monfichier.jpg',
			'size' => 2,
			'user_id' => 2,
			'current_version' => 1,
			'available' => 1,
			'parent_id' => 1,
			'is_folder' => 2,
			'lft' => 2,
			'rght' => 2
		),
		array(
			'id' => 3,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 3,
			'user_id' => 3,
			'current_version' => 3,
			'available' => 3,
			'parent_id' => 3,
			'is_folder' => 3,
			'lft' => 3,
			'rght' => 3
		),
		array(
			'id' => 4,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 4,
			'user_id' => 4,
			'current_version' => 4,
			'available' => 4,
			'parent_id' => 4,
			'is_folder' => 4,
			'lft' => 4,
			'rght' => 4
		),
		array(
			'id' => 5,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 5,
			'user_id' => 5,
			'current_version' => 5,
			'available' => 5,
			'parent_id' => 5,
			'is_folder' => 5,
			'lft' => 5,
			'rght' => 5
		),
		array(
			'id' => 6,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 6,
			'user_id' => 6,
			'current_version' => 6,
			'available' => 6,
			'parent_id' => 6,
			'is_folder' => 6,
			'lft' => 6,
			'rght' => 6
		),
		array(
			'id' => 7,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 7,
			'user_id' => 7,
			'current_version' => 7,
			'available' => 7,
			'parent_id' => 7,
			'is_folder' => 7,
			'lft' => 7,
			'rght' => 7
		),
		array(
			'id' => 8,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 8,
			'user_id' => 8,
			'current_version' => 8,
			'available' => 8,
			'parent_id' => 8,
			'is_folder' => 8,
			'lft' => 8,
			'rght' => 8
		),
		array(
			'id' => 9,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 9,
			'user_id' => 9,
			'current_version' => 9,
			'available' => 9,
			'parent_id' => 9,
			'is_folder' => 9,
			'lft' => 9,
			'rght' => 9
		),
		array(
			'id' => 10,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 10,
			'user_id' => 10,
			'current_version' => 10,
			'available' => 10,
			'parent_id' => 10,
			'is_folder' => 10,
			'lft' => 10,
			'rght' => 10
		),
		array(
			'id' => 11,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 10,
			'user_id' => 10,
			'current_version' => 10,
			'available' => 10,
			'parent_id' => 10,
			'is_folder' => 10,
			'lft' => 10,
			'rght' => 10
		),
		array(
			'id' => 12,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 10,
			'user_id' => 10,
			'current_version' => 10,
			'available' => 10,
			'parent_id' => 10,
			'is_folder' => 10,
			'lft' => 10,
			'rght' => 10
		),
		array(
			'id' => 13,
			'filename' => 'Lorem ipsum dolor sit amet',
			'size' => 10,
			'user_id' => 10,
			'current_version' => 10,
			'available' => 10,
			'parent_id' => 10,
			'is_folder' => 10,
			'lft' => 10,
			'rght' => 10
		),
	);

}
