<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
		'role_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'website' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'activation_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'image' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'bio' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'timezone' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 10, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'updated' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'role_id' => '1',
			'creator_id' => '0',
			'username' => 'admin',
			'password' => 'aa8773984093464376c678bd9ac05d347a35437a',
			'name' => 'admin',
			'email' => '',
			'website' => null,
			'activation_key' => '78348f63bcb7d556e7baf853bfaf6bac',
			'image' => null,
			'bio' => null,
			'timezone' => '0',
			'status' => 1,
			'updated' => '2012-10-16 17:56:29',
			'created' => '2012-10-16 17:56:29'
		),
		array(
			'id' => '8',
			'role_id' => '4',
			'creator_id' => '1',
			'username' => 'aymeric',
			'password' => 'c2288ab1456d78a5d97d925e9eb0adcdd9e6e75c',
			'name' => 'aymeric',
			'email' => 'aymeric@derbois.com',
			'website' => '',
			'activation_key' => 'b954bd9a9055996e1a223b714e14c801',
			'image' => null,
			'bio' => null,
			'timezone' => '0',
			'status' => 1,
			'updated' => '2012-10-17 16:11:08',
			'created' => '2012-10-17 16:11:08'
		),
		array(
			'id' => '35',
			'role_id' => '4',
			'creator_id' => '1',
			'username' => 'Toto',
			'password' => 'da6d10c0d29e4cc0643385939f8d23f6d2ace5bc',
			'name' => 'toto',
			'email' => 'toto@gmail.com',
			'website' => '',
			'activation_key' => '711b147caab5b47cba8921a71fd47ef5',
			'image' => null,
			'bio' => null,
			'timezone' => '0',
			'status' => 1,
			'updated' => '2012-10-18 16:29:05',
			'created' => '2012-10-18 16:29:05'
		)
	);

}
