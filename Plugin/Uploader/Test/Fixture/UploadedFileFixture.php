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
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'size' => array('type' => 'integer', 'null' => false, 'default' => null, 'comment' => 'Size of all versions'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'current_version' => array('type' => 'integer', 'null' => false, 'default' => null),
		'available' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'is_folder' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => null),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => null),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'filename' => 'Photos',
			'size' => '0',
			'user_id' => '1',
			'current_version' => '0',
			'available' => '0',
			'parent_id' => null,
			'is_folder' => '1',
			'lft' => '1',
			'rght' => '8',
			'mime_type' => null
		),
		array(
			'id' => '2',
			'filename' => 'Documents',
			'size' => '0',
			'user_id' => '1',
			'current_version' => '0',
			'available' => '0',
			'parent_id' => null,
			'is_folder' => '1',
			'lft' => '9',
			'rght' => '12',
			'mime_type' => null
		),
		array(
			'id' => '3',
			'filename' => 'Fruits',
			'size' => '0',
			'user_id' => '1',
			'current_version' => '0',
			'available' => '0',
			'parent_id' => '1',
			'is_folder' => '1',
			'lft' => '2',
			'rght' => '7',
			'mime_type' => null
		),
		array(
			'id' => '4',
			'filename' => 'Fraise.jpg',
			'size' => '34639',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '3',
			'is_folder' => '0',
			'lft' => '3',
			'rght' => '4',
			'mime_type' => 'image/jpeg'
		),
		array(
			'id' => '5',
			'filename' => 'pommes.jpg',
			'size' => '4385',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '3',
			'is_folder' => '0',
			'lft' => '5',
			'rght' => '6',
			'mime_type' => 'image/jpeg'
		),
		array(
			'id' => '6',
			'filename' => '2012-comptes.ods',
			'size' => '136812534',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '2',
			'is_folder' => '0',
			'lft' => '10',
			'rght' => '11',
			'mime_type' => 'application/vnd.oasis.opendocument.spreadsheet'
		),
	);

}
