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
			'id' => '506458c8-97a4-4bb9-aac9-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 1,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-e04c-4464-8888-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 2,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-1a1c-48a5-87ef-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 3,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-51f8-4752-a71a-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 4,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-8bc8-41a8-b308-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 5,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-c598-4a7a-b736-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 6,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-ff68-4154-97f7-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 7,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-3e4c-4766-92f1-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 8,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-7880-432c-95cf-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 9,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
		array(
			'id' => '506458c8-b1ec-4ead-930c-471099033ae1',
			'user_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'filename' => 'Lorem ipsum dolor sit amet',
			'filesize' => 10,
			'mime_type' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lor',
			'hash' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'adapter' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-09-27 15:46:48',
			'modified' => '2012-09-27 15:46:48'
		),
	);

}
