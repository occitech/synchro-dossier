<?php
class AddMissingAros extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'This is an irreversible migration. Down direction will have no effect';

	protected $_missingAdminAro = array(
		'model' => 'User',
		'foreign_key' => 1,
		'parent_id' => 1,
		'alias' => 'admin'
	);

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
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
		$success = true;

		$Aro = ClassRegistry::init('Aro');
		if ($direction == 'up') {
			$aro = $Aro->find('first', array('conditions' => array(
				$this->_missingAdminAro
			)));

			if (empty($aro)) {
				$success = $success && $Aro->save($this->_missingAdminAro);
			}
		}
		return $success;
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
