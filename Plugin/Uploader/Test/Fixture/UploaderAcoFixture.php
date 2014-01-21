<?php

App::uses('RecordsGenerator', 'Lib');

/**
 * AcoFixture
 *
 */
class UploaderAcoFixture extends CakeTestFixture {

	public $name = 'Aco';

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

	public $acosTree = array(
		array(
			'id' => 1,
			'model' => null,
			'foreign_key' => null,
			'alias' => 'uploadedFileAco',
			'children' => array(
				array(
					'id' => 2,
					'model' => 'UploadedFile',
					'foreign_key' => 1,
					'alias' => null,
					'children' => array(
						array(
							'id' => 3,
							'model' => 'UploadedFile',
							'foreign_key' => 3,
							'alias' => null,
							'children' => array(
								array(
									'id' => 4,
									'model' => 'UploadedFile',
									'foreign_key' => 4,
									'alias' => null,
								),
								array(
									'id' => 5,
									'model' => 'UploadedFile',
									'foreign_key' => 5,
									'alias' => null,
								),
							)
						)
					)
				),
				array(
					'id' => 6,
					'model' => 'UploadedFile',
					'foreign_key' => 2,
					'alias' => null,
					'children' => array(
						array(
							'id' => 7,
							'model' => 'UploadedFile',
							'foreign_key' => 6,
							'alias' => null,
						)
					)
				),
				array(
					'id' => 12,
					'model' => 'UploadedFile',
					'foreign_key' => 4,
					'alias' => null,
				)
			)
		),
		array(
			'id' => 8,
			'model' => null,
			'foreign_key' => null,
			'alias' => 'controllers',
			'children' => array(
				array(
					'id' => 9,
					'model' => null,
					'foreign_key' => null,
					'alias' => 'SdUsers',
					'children' => array(
						array(
							'id' => 10,
							'model' => null,
							'foreign_key' => null,
							'alias' => 'SdUsers',
							'children' => array(
								array(
									'id' => 11,
									'model' => null,
									'foreign_key' => null,
									'alias' => 'admin_index',
								),
							),
						)
					),
				),
			),
		),
	);

	public $records = array();

	public function __construct() {
		$this->records = RecordsGenerator::generate($this->acosTree);
		parent::__construct();
	}
}
