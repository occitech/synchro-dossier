<?php

class OldUserFixture extends CakeTestFixture {

	public $useDbConfig = 'testold';

	public $table = 'users';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'lastname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'firstname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'sct' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'gender' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password_new' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 40, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'lastlog' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => '1',
			'lastname' => 'Zulauf',
			'firstname' => 'Etienne',
			'gender' => 'mr',
			'sct' => 'occitech',
			'email' => 'etienne@occi-tech.com',
			'password' => 'locked',
			'password_new' => null,
			'password_code' => null,
			'type' => 'user',
			'created' => '2010-02-18 14:09:01',
			'modified' => '2010-02-18 14:09:01',
			'lastlog' => '2010-02-18 14:09:01'
		),
		array(
			'id' => '2',
			'lastname' => 'Breton',
			'firstname' => 'Erwane',
			'gender' => 'mr',
			'sct' => '',
			'email' => 'contact@phea.fr',
			'password' => 'b8dd7749f7e4450dee5189d8ee577e1ee83d9d25',
			'password_new' => null,
			'password_code' => null,
			'type' => 'root',
			'created' => '2010-02-18 14:09:01',
			'modified' => '2010-06-04 10:55:25',
			'lastlog' => '2010-06-17 15:55:40'
		),
		array(
			'id' => '4',
			'lastname' => 'Ienna',
			'firstname' => 'Florence',
			'gender' => 'mlle',
			'sct' => 'Rouge Pixel',
			'email' => 'coucou@phea.fr',
			'password' => '82b45f00484e4e215cb15f3e536185ffb289d1d0',
			'password_new' => null,
			'password_code' => null,
			'type' => 'user',
			'created' => '2010-06-04 09:51:16',
			'modified' => '2010-06-04 16:02:39',
			'lastlog' => '2010-06-04 09:52:03'
		),
		array(
			'id' => '5',
			'lastname' => 'Daverio',
			'firstname' => 'Laurent',
			'gender' => 'mr',
			'sct' => 'CRI',
			'email' => 'daverio@cri.ensmp.fr',
			'password' => '8199c642d60377bc0b93afd950251d5a94bc2fb3',
			'password_new' => null,
			'password_code' => null,
			'type' => 'user',
			'created' => '2010-06-04 13:00:10',
			'modified' => '2010-06-04 13:00:10',
			'lastlog' => null
		),
		array(
			'id' => '6',
			'lastname' => 'Latscha',
			'firstname' => 'Antoine',
			'gender' => 'mr',
			'sct' => 'Sport Etail',
			'email' => 'al@phea.fr',
			'password' => '08bd71f295fdbbb8d4dd5ea3d235f92cc3f08755',
			'password_new' => null,
			'password_code' => null,
			'type' => 'user',
			'created' => '2010-06-04 16:03:41',
			'modified' => '2010-06-04 16:03:41',
			'lastlog' => null
		),
		array(
			'id' => '7',
			'lastname' => 'Chevaleyrias',
			'firstname' => 'David',
			'gender' => 'mr',
			'sct' => 'Dynr\'r',
			'email' => 'moi@phea.fr',
			'password' => 'e72ff489ed1fa01bbdadc399fb2b836baecbab3c',
			'password_new' => null,
			'password_code' => null,
			'type' => '',
			'created' => '2010-06-09 09:47:02',
			'modified' => '2010-06-09 10:26:52',
			'lastlog' => '2010-06-09 09:49:56'
		),
		array(
			'id' => '8',
			'lastname' => 'Speldosa',
			'firstname' => 'Silian',
			'gender' => 'mr',
			'sct' => '',
			'email' => 'silian@myrnbarad.org',
			'password' => 'd36d62bf18d4e3063a96667c64edc98e99096aef',
			'password_new' => null,
			'password_code' => null,
			'type' => 'user',
			'created' => '2010-06-09 11:34:55',
			'modified' => '2010-06-09 11:34:55',
			'lastlog' => null
		),
	);
}
