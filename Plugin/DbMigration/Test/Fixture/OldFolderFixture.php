<?php

class OldFolderFixture extends CakeTestFixture {

	public $useDbConfig = 'testold';

	public $table = 'folders';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'node_left' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'node_right' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'order_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'owner_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'owner_id' => array('column' => 'owner_id', 'unique' => 0),
			'order_id' => array('column' => 'order_id', 'unique' => 0),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0),
			'node_left' => array('column' => 'node_left', 'unique' => 0),
			'node_right' => array('column' => 'node_right', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => '1',
			'name' => 'test',
			'node_left' => '25',
			'node_right' => '26',
			'parent_id' => '0',
			'order_id' => '1',
			'owner_id' => '2'
		),
		array(
			'id' => '2',
			'name' => 'mon super dossier à partager avec mes amis',
			'node_left' => '7',
			'node_right' => '12',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
		array(
			'id' => '3',
			'name' => 'ghdh',
			'node_left' => '3',
			'node_right' => '4',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
		array(
			'id' => '16',
			'name' => 'Rouge Pixel',
			'node_left' => '19',
			'node_right' => '22',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
		array(
			'id' => '17',
			'name' => 'Occitech',
			'node_left' => '13',
			'node_right' => '14',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
		array(
			'id' => '26',
			'name' => 'pouetpouet',
			'node_left' => '15',
			'node_right' => '16',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
		array(
			'id' => '27',
			'name' => 'Factures',
			'node_left' => '20',
			'node_right' => '21',
			'parent_id' => '16',
			'order_id' => '2',
			'owner_id' => null
		),
		array(
			'id' => '28',
			'name' => 'famille',
			'node_left' => '8',
			'node_right' => '11',
			'parent_id' => '2',
			'order_id' => '2',
			'owner_id' => null
		),
		array(
			'id' => '29',
			'name' => 'Enfants',
			'node_left' => '9',
			'node_right' => '10',
			'parent_id' => '28',
			'order_id' => '2',
			'owner_id' => null
		),
		array(
			'id' => '30',
			'name' => 'Kim Corp',
			'node_left' => '5',
			'node_right' => '6',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
		array(
			'id' => '31',
			'name' => 'Sportetail',
			'node_left' => '23',
			'node_right' => '24',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
		array(
			'id' => '32',
			'name' => 'coucou',
			'node_left' => '1',
			'node_right' => '2',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
		array(
			'id' => '36',
			'name' => 'Rou',
			'node_left' => '17',
			'node_right' => '18',
			'parent_id' => '0',
			'order_id' => '2',
			'owner_id' => '2'
		),
	);

}
