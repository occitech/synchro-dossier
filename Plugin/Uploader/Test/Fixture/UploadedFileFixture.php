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
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null),
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
			'id' => '1',
			'filename' => 'Racine',
			'size' => '0',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '1',
			'parent_id' => null,
			'is_folder' => '1',
			'lft' => '1',
			'rght' => '18'
		),
		array(
			'id' => '2',
			'filename' => 'Dossier1',
			'size' => '0',
			'user_id' => '1',
			'current_version' => '0',
			'available' => '0',
			'parent_id' => '1',
			'is_folder' => '1',
			'lft' => '2',
			'rght' => '9'
		),
		array(
			'id' => '3',
			'filename' => 'Dossier2',
			'size' => '0',
			'user_id' => '1',
			'current_version' => '0',
			'available' => '0',
			'parent_id' => '1',
			'is_folder' => '1',
			'lft' => '10',
			'rght' => '17'
		),
		array(
			'id' => '4',
			'filename' => 'toto.zip',
			'size' => '9285482',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '3',
			'is_folder' => '0',
			'lft' => '11',
			'rght' => '12'
		),
		array(
			'id' => '5',
			'filename' => 'ssdossier1',
			'size' => '0',
			'user_id' => '1',
			'current_version' => '0',
			'available' => '0',
			'parent_id' => '3',
			'is_folder' => '1',
			'lft' => '13',
			'rght' => '16'
		),
		array(
			'id' => '6',
			'filename' => 'Fraise.jpg',
			'size' => '34639',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '5',
			'is_folder' => '0',
			'lft' => '14',
			'rght' => '15'
		),
		array(
			'id' => '7',
			'filename' => 'fond ecran occitech.png',
			'size' => '342366',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '2',
			'is_folder' => '0',
			'lft' => '3',
			'rght' => '4'
		),
		array(
			'id' => '8',
			'filename' => 'Important',
			'size' => '0',
			'user_id' => '1',
			'current_version' => '0',
			'available' => '0',
			'parent_id' => '2',
			'is_folder' => '1',
			'lft' => '5',
			'rght' => '8'
		),
		array(
			'id' => '9',
			'filename' => 'readme-linux.txt',
			'size' => '589',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '8',
			'is_folder' => '0',
			'lft' => '6',
			'rght' => '7'
		),
	);

}
