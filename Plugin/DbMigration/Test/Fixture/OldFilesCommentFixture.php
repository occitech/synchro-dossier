<?php

class OldFilesCommentFixture extends CakeTestFixture {

	public $useDbConfig = 'testold';

	public $table = 'files_comments';

	public $fields = array(
		'comment_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'file_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'folder_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'comment_id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'folder_id' => array('column' => 'folder_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'comment_id' => '1',
			'user_id' => '4',
			'file_name' => 'Apress Beginning CakePHP From Novice to Professional Jul 2008.pdf',
			'folder_id' => '3',
			'comment' => 'Coucou, ce livre est vraiment pas mal !',
			'created' => '2009-12-23 00:00:00'
		),
		array(
			'comment_id' => '2',
			'user_id' => '5',
			'file_name' => 'Apress Beginning CakePHP From Novice to Professional Jul 2008.pdf',
			'folder_id' => '3',
			'comment' => 'Oui en effet, très bon livre !',
			'created' => '2010-12-20 00:00:00'
		),
	);

}
