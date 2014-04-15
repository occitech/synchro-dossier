<?php
/**
 * OrdersUserFixture
 *
 */
class OldOrdersUserFixture extends CakeTestFixture {

	public $useDbConfig = 'testold';

	public $table = 'orders_users';

	public $fields = array(
		'order_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => array('order_id', 'user_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'order_id' => '1',
			'user_id' => '1',
			'type' => 'superadmin'
		),
		array(
			'order_id' => '2',
			'user_id' => '2',
			'type' => 'superadmin'
		),
		array(
			'order_id' => '2',
			'user_id' => '7',
			'type' => 'admin'
		),
	);
}
