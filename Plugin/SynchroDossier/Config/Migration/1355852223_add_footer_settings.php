<?php
class AddFooterSettings extends CakeMigration {

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
		'up' => array(),
		'down' => array(),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		if ($direction == 'up') {
			$data = array('Setting' => array(
				'key' => 'SynchroDossier.footer',
				'value' => '',
				'input_type' => 'textarea',
				'editable' => 1,
			));
			return $this->generateModel('Setting')->save($data);
		} else {
			return $this->generateModel('Setting')->deleteAll(array('key' => 'SynchroDossier.footer'));
		}
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
