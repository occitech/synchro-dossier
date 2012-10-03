<?php
/**
 * UploadedFilesUserFixture
 *
 */
class UploadedFilesUserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'uploaded_file_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'rights' => array('type' => 'integer', 'null' => false, 'default' => null),
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
			'user_id' => 1,
			'uploaded_file_id' => 1,
			'rights' => 1
		),
		array(
			'id' => 2,
			'user_id' => 2,
			'uploaded_file_id' => 2,
			'rights' => 2
		),
		array(
			'id' => 3,
			'user_id' => 3,
			'uploaded_file_id' => 3,
			'rights' => 3
		),
		array(
			'id' => 4,
			'user_id' => 4,
			'uploaded_file_id' => 4,
			'rights' => 4
		),
		array(
			'id' => 5,
			'user_id' => 5,
			'uploaded_file_id' => 5,
			'rights' => 5
		),
		array(
			'id' => 6,
			'user_id' => 6,
			'uploaded_file_id' => 6,
			'rights' => 6
		),
		array(
			'id' => 7,
			'user_id' => 7,
			'uploaded_file_id' => 7,
			'rights' => 7
		),
		array(
			'id' => 8,
			'user_id' => 8,
			'uploaded_file_id' => 8,
			'rights' => 8
		),
		array(
			'id' => 9,
			'user_id' => 9,
			'uploaded_file_id' => 9,
			'rights' => 9
		),
		array(
			'id' => 10,
			'user_id' => 10,
			'uploaded_file_id' => 10,
			'rights' => 10
		),
	);

}
