<?php
/**
 * UserFixture
 *
 */
class UploaderUserFixture extends CakeTestFixture {

	public $name = 'User';

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
			'password' => 'd1e03fcbc79398c3f93a7c875a86baae3aa99d42',
			'name' => 'admin',
			'email' => 'admin@occitech.fr',
			'website' => null,
			'activation_key' => '4a150a31c5b8e892b6e21251d4d8f884',
			'image' => null,
			'bio' => null,
			'timezone' => '0',
			'status' => 1,
			'updated' => '2012-10-31 12:51:05',
			'created' => '2012-10-31 12:51:05'
		),
		array(
			'id' => '2',
			'role_id' => '6',
			'creator_id' => '0',
			'username' => 'aymeric',
			'password' => '935dce4494121f848ffe2d3337ed2c05192526b1',
			'name' => 'Derbois',
			'email' => 'aymeric@derbois.com',
			'website' => '',
			'activation_key' => 'd6b0ca85517794669b14460dec519714',
			'image' => null,
			'bio' => null,
			'timezone' => '0',
			'status' => 1,
			'updated' => '2012-10-31 17:21:32',
			'created' => '2012-10-31 17:21:32'
		),
		array(
			'id' => '3',
			'role_id' => '4',
			'creator_id' => '0',
			'username' => 'spadm1',
			'password' => '102307eeb5d160ab5a1040437de4f7575ca508b4',
			'name' => 'spadm1',
			'email' => 'spadm1@spadm1.com',
			'website' => '',
			'activation_key' => '9354921ba996586aad6eb6c2f1977f5e',
			'image' => null,
			'bio' => null,
			'timezone' => '0',
			'status' => 1,
			'updated' => '2012-11-02 12:30:36',
			'created' => '2012-11-02 12:30:36'
		),
		array(
			'id' => '4',
			'role_id' => '6',
			'creator_id' => '0',
			'username' => 'toto',
			'password' => '935dce4494121f848ffe2d3337ed2c05192526b1',
			'name' => 'Derbois',
			'email' => 'toto@derbois.com',
			'website' => '',
			'activation_key' => 'd6b0ca85517794669b14460dec519714',
			'image' => null,
			'bio' => null,
			'timezone' => '0',
			'status' => 1,
			'updated' => '2012-10-31 17:21:32',
			'created' => '2012-10-31 17:21:32'
		),
		array(
			'id' => '5',
			'role_id' => '4',
			'creator_id' => '3',
			'username' => 'spadm2',
			'password' => '102307eeb5d160ab5a1040437de4f7575ca508b4',
			'name' => 'spadm2',
			'email' => 'spadm2@spadm2.com',
			'website' => '',
			'activation_key' => '9354921ba996586aad6eb6c2f1977f5e',
			'image' => null,
			'bio' => null,
			'timezone' => '0',
			'status' => 1,
			'updated' => '2012-11-02 12:30:36',
			'created' => '2012-11-02 12:30:36'
		)
	);

}
