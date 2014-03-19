<?php
class OldUsersUserFixture extends CakeTestFixture {

	public $useDbConfig = 'testold';

	public $table = 'users_users';

	public $fields = array(
		'order_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'owner_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => array('order_id', 'user_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'order_id' => 1,
			'owner_id' => 1,
			'user_id' => 4,
			'created' => '2010-06-09 09:47:02'
		),
		array(
			'order_id' => 1,
			'owner_id' => 1,
			'user_id' => 5,
			'created' => '2010-06-09 09:47:02'
		),
		array(
			'order_id' => 1,
			'owner_id' => 1,
			'user_id' => 6,
			'created' => '2010-06-09 09:47:02'
		),
		array(
			'order_id' => 1,
			'owner_id' => 1,
			'user_id' => 7,
			'created' => '2010-06-09 09:47:02'
		),
	);
}
