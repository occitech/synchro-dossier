<?php
class AddUploaderNameFields extends CakeMigration {

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
			'create_field' => array(
				'file_storage' => array(
					'uploader_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1', 'after' => 'user_id'),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
				),
				'uploaded_files' => array(
					'uploader_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1', 'after' => 'user_id'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'after' => 'mime_type'),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'after' => 'created'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'file_storage' => array('uploader_name', 'tableParameters',),
				'uploaded_files' => array('uploader_name', 'created', 'modified',),
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
