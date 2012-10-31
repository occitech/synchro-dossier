<?php
/**
 * AcoFixture
 *
 */
class AcoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
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
			'parent_id' => null,
			'model' => '',
			'foreign_key' => null,
			'alias' => 'controllers',
			'lft' => '1',
			'rght' => '408'
		),
		array(
			'id' => '2',
			'parent_id' => '199',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Attachments',
			'lft' => '165',
			'rght' => '176'
		),
		array(
			'id' => '3',
			'parent_id' => '2',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '166',
			'rght' => '167'
		),
		array(
			'id' => '4',
			'parent_id' => '2',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '168',
			'rght' => '169'
		),
		array(
			'id' => '5',
			'parent_id' => '2',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '170',
			'rght' => '171'
		),
		array(
			'id' => '6',
			'parent_id' => '2',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '172',
			'rght' => '173'
		),
		array(
			'id' => '7',
			'parent_id' => '2',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_browse',
			'lft' => '174',
			'rght' => '175'
		),
		array(
			'id' => '8',
			'parent_id' => '197',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Blocks',
			'lft' => '73',
			'rght' => '88'
		),
		array(
			'id' => '9',
			'parent_id' => '8',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '74',
			'rght' => '75'
		),
		array(
			'id' => '10',
			'parent_id' => '8',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '76',
			'rght' => '77'
		),
		array(
			'id' => '11',
			'parent_id' => '8',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '78',
			'rght' => '79'
		),
		array(
			'id' => '12',
			'parent_id' => '8',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '80',
			'rght' => '81'
		),
		array(
			'id' => '13',
			'parent_id' => '8',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_moveup',
			'lft' => '82',
			'rght' => '83'
		),
		array(
			'id' => '14',
			'parent_id' => '8',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_movedown',
			'lft' => '84',
			'rght' => '85'
		),
		array(
			'id' => '15',
			'parent_id' => '8',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_process',
			'lft' => '86',
			'rght' => '87'
		),
		array(
			'id' => '16',
			'parent_id' => '200',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Comments',
			'lft' => '179',
			'rght' => '194'
		),
		array(
			'id' => '17',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '180',
			'rght' => '181'
		),
		array(
			'id' => '18',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '182',
			'rght' => '183'
		),
		array(
			'id' => '19',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '184',
			'rght' => '185'
		),
		array(
			'id' => '20',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_process',
			'lft' => '186',
			'rght' => '187'
		),
		array(
			'id' => '21',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'index',
			'lft' => '188',
			'rght' => '189'
		),
		array(
			'id' => '22',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'add',
			'lft' => '190',
			'rght' => '191'
		),
		array(
			'id' => '23',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'delete',
			'lft' => '192',
			'rght' => '193'
		),
		array(
			'id' => '24',
			'parent_id' => '201',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Contacts',
			'lft' => '197',
			'rght' => '208'
		),
		array(
			'id' => '25',
			'parent_id' => '24',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '198',
			'rght' => '199'
		),
		array(
			'id' => '26',
			'parent_id' => '24',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '200',
			'rght' => '201'
		),
		array(
			'id' => '27',
			'parent_id' => '24',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '202',
			'rght' => '203'
		),
		array(
			'id' => '28',
			'parent_id' => '24',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '204',
			'rght' => '205'
		),
		array(
			'id' => '29',
			'parent_id' => '24',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'view',
			'lft' => '206',
			'rght' => '207'
		),
		array(
			'id' => '30',
			'parent_id' => '199',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'FileManager',
			'lft' => '143',
			'rght' => '164'
		),
		array(
			'id' => '31',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '144',
			'rght' => '145'
		),
		array(
			'id' => '32',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_browse',
			'lft' => '146',
			'rght' => '147'
		),
		array(
			'id' => '33',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_editfile',
			'lft' => '148',
			'rght' => '149'
		),
		array(
			'id' => '34',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_upload',
			'lft' => '150',
			'rght' => '151'
		),
		array(
			'id' => '35',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete_file',
			'lft' => '152',
			'rght' => '153'
		),
		array(
			'id' => '36',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete_directory',
			'lft' => '154',
			'rght' => '155'
		),
		array(
			'id' => '37',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_rename',
			'lft' => '156',
			'rght' => '157'
		),
		array(
			'id' => '38',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_create_directory',
			'lft' => '158',
			'rght' => '159'
		),
		array(
			'id' => '39',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_create_file',
			'lft' => '160',
			'rght' => '161'
		),
		array(
			'id' => '40',
			'parent_id' => '30',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_chmod',
			'lft' => '162',
			'rght' => '163'
		),
		array(
			'id' => '41',
			'parent_id' => '204',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Languages',
			'lft' => '301',
			'rght' => '316'
		),
		array(
			'id' => '42',
			'parent_id' => '41',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '302',
			'rght' => '303'
		),
		array(
			'id' => '43',
			'parent_id' => '41',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '304',
			'rght' => '305'
		),
		array(
			'id' => '44',
			'parent_id' => '41',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '306',
			'rght' => '307'
		),
		array(
			'id' => '45',
			'parent_id' => '41',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '308',
			'rght' => '309'
		),
		array(
			'id' => '46',
			'parent_id' => '41',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_moveup',
			'lft' => '310',
			'rght' => '311'
		),
		array(
			'id' => '47',
			'parent_id' => '41',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_movedown',
			'lft' => '312',
			'rght' => '313'
		),
		array(
			'id' => '48',
			'parent_id' => '41',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_select',
			'lft' => '314',
			'rght' => '315'
		),
		array(
			'id' => '49',
			'parent_id' => '203',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Links',
			'lft' => '263',
			'rght' => '278'
		),
		array(
			'id' => '50',
			'parent_id' => '49',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '264',
			'rght' => '265'
		),
		array(
			'id' => '51',
			'parent_id' => '49',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '266',
			'rght' => '267'
		),
		array(
			'id' => '52',
			'parent_id' => '49',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '268',
			'rght' => '269'
		),
		array(
			'id' => '53',
			'parent_id' => '49',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '270',
			'rght' => '271'
		),
		array(
			'id' => '54',
			'parent_id' => '49',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_moveup',
			'lft' => '272',
			'rght' => '273'
		),
		array(
			'id' => '55',
			'parent_id' => '49',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_movedown',
			'lft' => '274',
			'rght' => '275'
		),
		array(
			'id' => '56',
			'parent_id' => '49',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_process',
			'lft' => '276',
			'rght' => '277'
		),
		array(
			'id' => '57',
			'parent_id' => '203',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Menus',
			'lft' => '253',
			'rght' => '262'
		),
		array(
			'id' => '58',
			'parent_id' => '57',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '254',
			'rght' => '255'
		),
		array(
			'id' => '59',
			'parent_id' => '57',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '256',
			'rght' => '257'
		),
		array(
			'id' => '60',
			'parent_id' => '57',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '258',
			'rght' => '259'
		),
		array(
			'id' => '61',
			'parent_id' => '57',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '260',
			'rght' => '261'
		),
		array(
			'id' => '62',
			'parent_id' => '201',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Messages',
			'lft' => '209',
			'rght' => '218'
		),
		array(
			'id' => '63',
			'parent_id' => '62',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '210',
			'rght' => '211'
		),
		array(
			'id' => '64',
			'parent_id' => '62',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '212',
			'rght' => '213'
		),
		array(
			'id' => '65',
			'parent_id' => '62',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '214',
			'rght' => '215'
		),
		array(
			'id' => '66',
			'parent_id' => '62',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_process',
			'lft' => '216',
			'rght' => '217'
		),
		array(
			'id' => '67',
			'parent_id' => '202',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Nodes',
			'lft' => '221',
			'rght' => '250'
		),
		array(
			'id' => '68',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '222',
			'rght' => '223'
		),
		array(
			'id' => '69',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_create',
			'lft' => '224',
			'rght' => '225'
		),
		array(
			'id' => '70',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '226',
			'rght' => '227'
		),
		array(
			'id' => '71',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '228',
			'rght' => '229'
		),
		array(
			'id' => '72',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_update_paths',
			'lft' => '230',
			'rght' => '231'
		),
		array(
			'id' => '73',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '232',
			'rght' => '233'
		),
		array(
			'id' => '74',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete_meta',
			'lft' => '234',
			'rght' => '235'
		),
		array(
			'id' => '75',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add_meta',
			'lft' => '236',
			'rght' => '237'
		),
		array(
			'id' => '76',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_process',
			'lft' => '238',
			'rght' => '239'
		),
		array(
			'id' => '77',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'index',
			'lft' => '240',
			'rght' => '241'
		),
		array(
			'id' => '78',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'term',
			'lft' => '242',
			'rght' => '243'
		),
		array(
			'id' => '79',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'promoted',
			'lft' => '244',
			'rght' => '245'
		),
		array(
			'id' => '80',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'search',
			'lft' => '246',
			'rght' => '247'
		),
		array(
			'id' => '81',
			'parent_id' => '67',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'view',
			'lft' => '248',
			'rght' => '249'
		),
		array(
			'id' => '82',
			'parent_id' => '197',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Regions',
			'lft' => '89',
			'rght' => '98'
		),
		array(
			'id' => '83',
			'parent_id' => '82',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '90',
			'rght' => '91'
		),
		array(
			'id' => '84',
			'parent_id' => '82',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '92',
			'rght' => '93'
		),
		array(
			'id' => '85',
			'parent_id' => '82',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '94',
			'rght' => '95'
		),
		array(
			'id' => '86',
			'parent_id' => '82',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '96',
			'rght' => '97'
		),
		array(
			'id' => '87',
			'parent_id' => '206',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Roles',
			'lft' => '397',
			'rght' => '406'
		),
		array(
			'id' => '88',
			'parent_id' => '87',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '398',
			'rght' => '399'
		),
		array(
			'id' => '89',
			'parent_id' => '87',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '400',
			'rght' => '401'
		),
		array(
			'id' => '90',
			'parent_id' => '87',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '402',
			'rght' => '403'
		),
		array(
			'id' => '91',
			'parent_id' => '87',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '404',
			'rght' => '405'
		),
		array(
			'id' => '92',
			'parent_id' => '204',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Settings',
			'lft' => '281',
			'rght' => '300'
		),
		array(
			'id' => '93',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_dashboard',
			'lft' => '282',
			'rght' => '283'
		),
		array(
			'id' => '94',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '284',
			'rght' => '285'
		),
		array(
			'id' => '95',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '286',
			'rght' => '287'
		),
		array(
			'id' => '96',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '288',
			'rght' => '289'
		),
		array(
			'id' => '97',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '290',
			'rght' => '291'
		),
		array(
			'id' => '98',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '292',
			'rght' => '293'
		),
		array(
			'id' => '99',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_prefix',
			'lft' => '294',
			'rght' => '295'
		),
		array(
			'id' => '100',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_moveup',
			'lft' => '296',
			'rght' => '297'
		),
		array(
			'id' => '101',
			'parent_id' => '92',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_movedown',
			'lft' => '298',
			'rght' => '299'
		),
		array(
			'id' => '102',
			'parent_id' => '205',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Terms',
			'lft' => '319',
			'rght' => '334'
		),
		array(
			'id' => '103',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '320',
			'rght' => '321'
		),
		array(
			'id' => '104',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '322',
			'rght' => '323'
		),
		array(
			'id' => '105',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '324',
			'rght' => '325'
		),
		array(
			'id' => '106',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '326',
			'rght' => '327'
		),
		array(
			'id' => '107',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_moveup',
			'lft' => '328',
			'rght' => '329'
		),
		array(
			'id' => '108',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_movedown',
			'lft' => '330',
			'rght' => '331'
		),
		array(
			'id' => '109',
			'parent_id' => '102',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_process',
			'lft' => '332',
			'rght' => '333'
		),
		array(
			'id' => '110',
			'parent_id' => '205',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Types',
			'lft' => '335',
			'rght' => '344'
		),
		array(
			'id' => '111',
			'parent_id' => '110',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '336',
			'rght' => '337'
		),
		array(
			'id' => '112',
			'parent_id' => '110',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '338',
			'rght' => '339'
		),
		array(
			'id' => '113',
			'parent_id' => '110',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '340',
			'rght' => '341'
		),
		array(
			'id' => '114',
			'parent_id' => '110',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '342',
			'rght' => '343'
		),
		array(
			'id' => '115',
			'parent_id' => '206',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Users',
			'lft' => '357',
			'rght' => '396'
		),
		array(
			'id' => '116',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '358',
			'rght' => '359'
		),
		array(
			'id' => '117',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '360',
			'rght' => '361'
		),
		array(
			'id' => '118',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '362',
			'rght' => '363'
		),
		array(
			'id' => '119',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_reset_password',
			'lft' => '364',
			'rght' => '365'
		),
		array(
			'id' => '120',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '366',
			'rght' => '367'
		),
		array(
			'id' => '121',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_login',
			'lft' => '368',
			'rght' => '369'
		),
		array(
			'id' => '122',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_logout',
			'lft' => '370',
			'rght' => '371'
		),
		array(
			'id' => '123',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'index',
			'lft' => '372',
			'rght' => '373'
		),
		array(
			'id' => '124',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'add',
			'lft' => '374',
			'rght' => '375'
		),
		array(
			'id' => '125',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'activate',
			'lft' => '376',
			'rght' => '377'
		),
		array(
			'id' => '126',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'edit',
			'lft' => '378',
			'rght' => '379'
		),
		array(
			'id' => '127',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'forgot',
			'lft' => '380',
			'rght' => '381'
		),
		array(
			'id' => '128',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'reset',
			'lft' => '382',
			'rght' => '383'
		),
		array(
			'id' => '129',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'login',
			'lft' => '384',
			'rght' => '385'
		),
		array(
			'id' => '130',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'logout',
			'lft' => '386',
			'rght' => '387'
		),
		array(
			'id' => '131',
			'parent_id' => '115',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'view',
			'lft' => '388',
			'rght' => '389'
		),
		array(
			'id' => '132',
			'parent_id' => '205',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'Vocabularies',
			'lft' => '345',
			'rght' => '354'
		),
		array(
			'id' => '133',
			'parent_id' => '132',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '346',
			'rght' => '347'
		),
		array(
			'id' => '134',
			'parent_id' => '132',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '348',
			'rght' => '349'
		),
		array(
			'id' => '135',
			'parent_id' => '132',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '350',
			'rght' => '351'
		),
		array(
			'id' => '136',
			'parent_id' => '132',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '352',
			'rght' => '353'
		),
		array(
			'id' => '137',
			'parent_id' => '196',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'AclAcos',
			'lft' => '31',
			'rght' => '40'
		),
		array(
			'id' => '138',
			'parent_id' => '137',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '32',
			'rght' => '33'
		),
		array(
			'id' => '139',
			'parent_id' => '137',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '34',
			'rght' => '35'
		),
		array(
			'id' => '140',
			'parent_id' => '137',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '36',
			'rght' => '37'
		),
		array(
			'id' => '141',
			'parent_id' => '137',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '38',
			'rght' => '39'
		),
		array(
			'id' => '142',
			'parent_id' => '196',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'AclActions',
			'lft' => '41',
			'rght' => '54'
		),
		array(
			'id' => '143',
			'parent_id' => '142',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '42',
			'rght' => '43'
		),
		array(
			'id' => '144',
			'parent_id' => '142',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '44',
			'rght' => '45'
		),
		array(
			'id' => '145',
			'parent_id' => '142',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '46',
			'rght' => '47'
		),
		array(
			'id' => '146',
			'parent_id' => '142',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '48',
			'rght' => '49'
		),
		array(
			'id' => '147',
			'parent_id' => '142',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_move',
			'lft' => '50',
			'rght' => '51'
		),
		array(
			'id' => '148',
			'parent_id' => '142',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_generate',
			'lft' => '52',
			'rght' => '53'
		),
		array(
			'id' => '149',
			'parent_id' => '196',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'AclAros',
			'lft' => '55',
			'rght' => '64'
		),
		array(
			'id' => '150',
			'parent_id' => '149',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '56',
			'rght' => '57'
		),
		array(
			'id' => '151',
			'parent_id' => '149',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '58',
			'rght' => '59'
		),
		array(
			'id' => '152',
			'parent_id' => '149',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '60',
			'rght' => '61'
		),
		array(
			'id' => '153',
			'parent_id' => '149',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '62',
			'rght' => '63'
		),
		array(
			'id' => '154',
			'parent_id' => '196',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'AclPermissions',
			'lft' => '65',
			'rght' => '70'
		),
		array(
			'id' => '155',
			'parent_id' => '154',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '66',
			'rght' => '67'
		),
		array(
			'id' => '156',
			'parent_id' => '154',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_toggle',
			'lft' => '68',
			'rght' => '69'
		),
		array(
			'id' => '159',
			'parent_id' => '198',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'ExtensionsHooks',
			'lft' => '101',
			'rght' => '106'
		),
		array(
			'id' => '160',
			'parent_id' => '159',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '102',
			'rght' => '103'
		),
		array(
			'id' => '161',
			'parent_id' => '159',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_toggle',
			'lft' => '104',
			'rght' => '105'
		),
		array(
			'id' => '162',
			'parent_id' => '198',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'ExtensionsLocales',
			'lft' => '107',
			'rght' => '118'
		),
		array(
			'id' => '163',
			'parent_id' => '162',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '108',
			'rght' => '109'
		),
		array(
			'id' => '164',
			'parent_id' => '162',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_activate',
			'lft' => '110',
			'rght' => '111'
		),
		array(
			'id' => '165',
			'parent_id' => '162',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '112',
			'rght' => '113'
		),
		array(
			'id' => '166',
			'parent_id' => '162',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '114',
			'rght' => '115'
		),
		array(
			'id' => '167',
			'parent_id' => '162',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '116',
			'rght' => '117'
		),
		array(
			'id' => '168',
			'parent_id' => '198',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'ExtensionsPlugins',
			'lft' => '119',
			'rght' => '126'
		),
		array(
			'id' => '169',
			'parent_id' => '168',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '120',
			'rght' => '121'
		),
		array(
			'id' => '170',
			'parent_id' => '168',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '122',
			'rght' => '123'
		),
		array(
			'id' => '171',
			'parent_id' => '168',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '124',
			'rght' => '125'
		),
		array(
			'id' => '172',
			'parent_id' => '198',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'ExtensionsThemes',
			'lft' => '127',
			'rght' => '140'
		),
		array(
			'id' => '173',
			'parent_id' => '172',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '128',
			'rght' => '129'
		),
		array(
			'id' => '174',
			'parent_id' => '172',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_activate',
			'lft' => '130',
			'rght' => '131'
		),
		array(
			'id' => '175',
			'parent_id' => '172',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '132',
			'rght' => '133'
		),
		array(
			'id' => '176',
			'parent_id' => '172',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_editor',
			'lft' => '134',
			'rght' => '135'
		),
		array(
			'id' => '177',
			'parent_id' => '172',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_save',
			'lft' => '136',
			'rght' => '137'
		),
		array(
			'id' => '178',
			'parent_id' => '172',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '138',
			'rght' => '139'
		),
		array(
			'id' => '179',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'SdUsers',
			'lft' => '2',
			'rght' => '13'
		),
		array(
			'id' => '180',
			'parent_id' => '179',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'SdUsers',
			'lft' => '3',
			'rght' => '12'
		),
		array(
			'id' => '181',
			'parent_id' => '180',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '4',
			'rght' => '5'
		),
		array(
			'id' => '182',
			'parent_id' => '180',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '6',
			'rght' => '7'
		),
		array(
			'id' => '183',
			'parent_id' => '180',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '8',
			'rght' => '9'
		),
		array(
			'id' => '184',
			'parent_id' => '180',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '10',
			'rght' => '11'
		),
		array(
			'id' => '185',
			'parent_id' => '115',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Users',
			'lft' => '390',
			'rght' => '395'
		),
		array(
			'id' => '186',
			'parent_id' => '185',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_logout',
			'lft' => '391',
			'rght' => '392'
		),
		array(
			'id' => '187',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Uploader',
			'lft' => '14',
			'rght' => '29'
		),
		array(
			'id' => '188',
			'parent_id' => '187',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Files',
			'lft' => '15',
			'rght' => '28'
		),
		array(
			'id' => '189',
			'parent_id' => '188',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'browse',
			'lft' => '16',
			'rght' => '17'
		),
		array(
			'id' => '190',
			'parent_id' => '188',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'upload',
			'lft' => '18',
			'rght' => '19'
		),
		array(
			'id' => '191',
			'parent_id' => '188',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'download',
			'lft' => '20',
			'rght' => '21'
		),
		array(
			'id' => '192',
			'parent_id' => '188',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'rename',
			'lft' => '22',
			'rght' => '23'
		),
		array(
			'id' => '193',
			'parent_id' => '188',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'createFolder',
			'lft' => '24',
			'rght' => '25'
		),
		array(
			'id' => '194',
			'parent_id' => '188',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'downloadZipFolder',
			'lft' => '26',
			'rght' => '27'
		),
		array(
			'id' => '195',
			'parent_id' => '185',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'logout',
			'lft' => '393',
			'rght' => '394'
		),
		array(
			'id' => '196',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Acl',
			'lft' => '30',
			'rght' => '71'
		),
		array(
			'id' => '197',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Blocks',
			'lft' => '72',
			'rght' => '99'
		),
		array(
			'id' => '198',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Extensions',
			'lft' => '100',
			'rght' => '141'
		),
		array(
			'id' => '199',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'FileManager',
			'lft' => '142',
			'rght' => '177'
		),
		array(
			'id' => '200',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Comments',
			'lft' => '178',
			'rght' => '195'
		),
		array(
			'id' => '201',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Contacts',
			'lft' => '196',
			'rght' => '219'
		),
		array(
			'id' => '202',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Nodes',
			'lft' => '220',
			'rght' => '251'
		),
		array(
			'id' => '203',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Menus',
			'lft' => '252',
			'rght' => '279'
		),
		array(
			'id' => '204',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Settings',
			'lft' => '280',
			'rght' => '317'
		),
		array(
			'id' => '205',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Taxonomy',
			'lft' => '318',
			'rght' => '355'
		),
		array(
			'id' => '206',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Users',
			'lft' => '356',
			'rght' => '407'
		),
		array(
			'id' => '207',
			'parent_id' => null,
			'model' => 'UploadedFile',
			'foreign_key' => '1',
			'alias' => null,
			'lft' => '409',
			'rght' => '410'
		),
		array(
			'id' => '208',
			'parent_id' => null,
			'model' => 'UploadedFile',
			'foreign_key' => '2',
			'alias' => null,
			'lft' => '411',
			'rght' => '412'
		),
		array(
			'id' => '209',
			'parent_id' => null,
			'model' => 'UploadedFile',
			'foreign_key' => '3',
			'alias' => null,
			'lft' => '413',
			'rght' => '414'
		),
		array(
			'id' => '210',
			'parent_id' => null,
			'model' => 'UploadedFile',
			'foreign_key' => '4',
			'alias' => null,
			'lft' => '415',
			'rght' => '416'
		),
		array(
			'id' => '211',
			'parent_id' => null,
			'model' => 'UploadedFile',
			'foreign_key' => '5',
			'alias' => null,
			'lft' => '417',
			'rght' => '418'
		),
		array(
			'id' => '212',
			'parent_id' => null,
			'model' => 'UploadedFile',
			'foreign_key' => '6',
			'alias' => null,
			'lft' => '419',
			'rght' => '420'
		),
	);

}
