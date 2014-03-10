<?php
/**
 * ProfileFixture
 *
 */
class ProfileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'firstname' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'society' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'language_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => '1',
			'name' => 'spadm1',
			'firstname' => '',
			'society' => '',
			'user_id' => 3,
			'language_id' => 2
		),
		array(
			'id' => '2',
			'name' => 'spadm2',
			'firstname' => '',
			'society' => '',
			'user_id' => 5,
			'language_id' => 1
		),
		array(
			'id' => '3',
			'name' => 'toto',
			'firstname' => 'tata',
			'society' => '',
			'user_id' => 4,
			'language_id' => 1
		)
	);
}
