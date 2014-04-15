<?php
class UploadedFile extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'uploaded_files' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'filename' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'size' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'filename'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'size'),
					'current_version' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'user_id'),
					'available' => array('type' => 'integer', 'null' => false, 'default' => '0', 'after' => 'current_version'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'available'),
					'is_folder' => array('type' => 'integer', 'null' => false, 'default' => '0', 'after' => 'parent_id'),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'is_folder'),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'lft'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'uploaded_files'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
