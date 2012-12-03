<?php
/**
 * FileStorageFixture
 *
 */
class FileStorageFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'file_storage';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'adapter' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'comment' => 'Gaufrette Storage Adapter Class', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
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
			'id' => '509116b6-4d00-4737-8527-2a9ad4b04a59',
			'user_id' => '1',
			'foreign_key' => '4',
			'model' => 'UploadedFile',
			'filename' => '',
			'filesize' => '34639',
			'mime_type' => 'image/jpeg',
			'extension' => null,
			'hash' => null,
			'path' => '1/4/1-1c082be57dd2a8b40831e1258ab2187f4eee044a',
			'adapter' => 'Local',
			'created' => '2012-10-31 13:16:54',
			'modified' => '2012-10-31 13:16:54'
		),
		array(
			'id' => '509116da-0008-4958-909a-1c21d4b04a59',
			'user_id' => '1',
			'foreign_key' => '5',
			'model' => 'UploadedFile',
			'filename' => '',
			'filesize' => '4385',
			'mime_type' => 'image/jpeg',
			'extension' => null,
			'hash' => null,
			'path' => '1/5/1-fc13ddddf3f37ecb451d76665f2f4b29d8dd0220',
			'adapter' => 'Local',
			'created' => '2012-10-31 13:17:30',
			'modified' => '2012-10-31 13:17:30'
		),
		array(
			'id' => '509116eb-5c70-45e1-98ac-1c21d4b04a59',
			'user_id' => '1',
			'foreign_key' => '6',
			'model' => 'UploadedFile',
			'filename' => '',
			'filesize' => '13681',
			'mime_type' => 'application/vnd.oasis.opendocume',
			'extension' => null,
			'hash' => null,
			'path' => '1/6/1-c125891ff59db64ceb67a1375453ccf85f4c0e60',
			'adapter' => 'Local',
			'created' => '2012-10-31 13:17:47',
			'modified' => '2012-10-31 13:17:47'
		),
	);

}
