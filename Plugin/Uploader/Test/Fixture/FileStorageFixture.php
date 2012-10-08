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
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '5072d10c-3358-4d1b-9a78-3df1b8a75d4a',
			'user_id' => '1',
			'foreign_key' => '4',
			'model' => 'UploadedFile',
			'filename' => '',
			'filesize' => null,
			'mime_type' => null,
			'extension' => null,
			'hash' => null,
			'path' => '1/4/1-d60854626c7bd0b73ef0d983913b74dcb0a35cc1',
			'adapter' => 'Local',
			'created' => '2012-10-08 15:11:40',
			'modified' => '2012-10-08 15:11:40'
		),
		array(
			'id' => '5072d145-06e8-470b-8577-39adb8a75d4a',
			'user_id' => '1',
			'foreign_key' => '6',
			'model' => 'UploadedFile',
			'filename' => '',
			'filesize' => null,
			'mime_type' => null,
			'extension' => null,
			'hash' => null,
			'path' => '1/6/1-1c082be57dd2a8b40831e1258ab2187f4eee044a',
			'adapter' => 'Local',
			'created' => '2012-10-08 15:12:37',
			'modified' => '2012-10-08 15:12:37'
		),
		array(
			'id' => '5072d5d9-fbec-42c2-bfff-3df1b8a75d4a',
			'user_id' => '1',
			'foreign_key' => '7',
			'model' => 'UploadedFile',
			'filename' => '',
			'filesize' => null,
			'mime_type' => null,
			'extension' => null,
			'hash' => null,
			'path' => '1/7/1-f934126919617299847ffc0e9e8b073cc6b93604',
			'adapter' => 'Local',
			'created' => '2012-10-08 15:32:09',
			'modified' => '2012-10-08 15:32:09'
		),
		array(
			'id' => '5072d5f1-cc94-4f16-a879-2da5b8a75d4a',
			'user_id' => '1',
			'foreign_key' => '9',
			'model' => 'UploadedFile',
			'filename' => '',
			'filesize' => null,
			'mime_type' => null,
			'extension' => null,
			'hash' => null,
			'path' => '1/9/1-cbd80f94787c1dfd8d4db1ade961aa18adf24c56',
			'adapter' => 'Local',
			'created' => '2012-10-08 15:32:33',
			'modified' => '2012-10-08 15:32:33'
		),
	);

}
