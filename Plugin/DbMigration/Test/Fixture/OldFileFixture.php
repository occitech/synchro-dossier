<?php
/**
 * FileFixture
 *
 */
class OldFileFixture extends CakeTestFixture {

	public $useDbConfig = 'testold';

	public $table = 'files';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'hash' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'size' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 8),
		'mime' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'version' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 5),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'folder_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'group_id' => array('column' => 'group_id', 'unique' => 0),
			'folder_id' => array('column' => 'folder_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => '1',
			'name' => 'Apress Beginning CakePHP From Novice to Professional Jul 2008.pdf',
			'hash' => '2dd89b7bb9af0ef5bc58e0b72cff5191',
			'size' => '3867',
			'mime' => 'application/pdf; charset=binary',
			'version' => '1',
			'created' => '2010-06-17 12:01:11',
			'user_id' => '2',
			'group_id' => '3',
			'folder_id' => '3'
		),
		array(
			'id' => '2',
			'name' => 'Best Sport Discount - proposition commerciale.pdf',
			'hash' => '3f3a6548ee8024881401cea796e89686',
			'size' => '2133',
			'mime' => 'application/pdf; charset=binary',
			'version' => '1',
			'created' => '2010-06-17 12:01:11',
			'user_id' => '2',
			'group_id' => '3',
			'folder_id' => '3'
		),
		array(
			'id' => '3',
			'name' => 'biogis_100514-1001 sql.zip',
			'hash' => '79b514db5e5a440da7866bafc20de9d9',
			'size' => '1440',
			'mime' => 'application/zip; charset=binary',
			'version' => '1',
			'created' => '2010-06-17 12:01:11',
			'user_id' => '2',
			'group_id' => '3',
			'folder_id' => '3'
		),
		array(
			'id' => '4',
			'name' => 'Apress Beginning CakePHP From Novice to Professional Jul 2008.pdf',
			'hash' => '4956276cad64139cfb9657fe6bd04f45',
			'size' => '3867',
			'mime' => 'application/pdf; charset=binary',
			'version' => '2',
			'created' => '2010-06-17 14:50:34',
			'user_id' => '2',
			'group_id' => '3',
			'folder_id' => '3'
		),
		array(
			'id' => '5',
			'name' => 'Apress Beginning CakePHP From Novice to Professional Jul 2008.pdf',
			'hash' => '2b28768bbaf91882d0e4b638c787e84a',
			'size' => '3867',
			'mime' => 'application/pdf; charset=binary',
			'version' => '3',
			'created' => '2010-06-17 14:52:54',
			'user_id' => '2',
			'group_id' => '3',
			'folder_id' => '3'
		),
	);
}
