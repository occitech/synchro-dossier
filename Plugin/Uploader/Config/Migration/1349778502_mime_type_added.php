<?php
class MimeTypeAdded extends CakeMigration {

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
				'uploaded_files' => array(
					'mime_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'rght'),
				),
			),
			'alter_field' => array(
				'uploaded_files' => array(
					'size' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'comment' => 'Size of all versions'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'uploaded_files' => array('mime_type',),
			),
			'alter_field' => array(
				'uploaded_files' => array(
					'size' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
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
