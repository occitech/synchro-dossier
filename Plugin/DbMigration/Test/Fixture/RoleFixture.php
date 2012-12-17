<?php

class RoleFixture extends CakeTestFixture {

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'role_alias' => array('column' => 'alias', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => '1',
			'title' => 'Occitech',
			'alias' => 'admin',
			'created' => '2009-04-05 00:10:34',
			'updated' => '2009-04-05 00:10:34'
		),
		array(
			'id' => '2',
			'title' => 'Registered',
			'alias' => 'registered',
			'created' => '2009-04-05 00:10:50',
			'updated' => '2009-04-06 05:20:38'
		),
		array(
			'id' => '3',
			'title' => 'Public',
			'alias' => 'public',
			'created' => '2009-04-05 00:12:38',
			'updated' => '2009-04-07 01:41:45'
		),
		array(
			'id' => '4',
			'title' => 'SuperAdmin',
			'alias' => 'sdSuperAdmin',
			'created' => '2012-12-04 18:19:34',
			'updated' => '2012-12-04 18:19:34'
		),
		array(
			'id' => '5',
			'title' => 'Admin',
			'alias' => 'sdAdmin',
			'created' => '2012-12-04 18:19:34',
			'updated' => '2012-12-04 18:19:34'
		),
		array(
			'id' => '6',
			'title' => 'Utilisateur',
			'alias' => 'sdUtilisateur',
			'created' => '2012-12-04 18:19:34',
			'updated' => '2012-12-04 18:19:34'
		),
	);

}
