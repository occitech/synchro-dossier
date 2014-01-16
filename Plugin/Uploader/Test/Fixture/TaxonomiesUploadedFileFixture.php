<?php
/**
 * TaxonomiesUploadedFileFixture
 *
 */
class TaxonomiesUploadedFileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'uploaded_file_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20),
		'taxonomy_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20),
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
			'id' => 1,
			'uploaded_file_id' => 1,
			'taxonomy_id' => 1
		),
		array(
			'id' => 2,
			'uploaded_file_id' => 2,
			'taxonomy_id' => 2
		),
		array(
			'id' => 3,
			'uploaded_file_id' => 3,
			'taxonomy_id' => 3
		),
		array(
			'id' => 4,
			'uploaded_file_id' => 4,
			'taxonomy_id' => 4
		),
		array(
			'id' => 5,
			'uploaded_file_id' => 5,
			'taxonomy_id' => 5
		),
		array(
			'id' => 6,
			'uploaded_file_id' => 6,
			'taxonomy_id' => 6
		),
		array(
			'id' => 7,
			'uploaded_file_id' => 7,
			'taxonomy_id' => 7
		),
		array(
			'id' => 8,
			'uploaded_file_id' => 8,
			'taxonomy_id' => 8
		),
		array(
			'id' => 9,
			'uploaded_file_id' => 9,
			'taxonomy_id' => 9
		),
		array(
			'id' => 10,
			'uploaded_file_id' => 10,
			'taxonomy_id' => 10
		),
	);

}
