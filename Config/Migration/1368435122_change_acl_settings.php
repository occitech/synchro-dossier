<?php
class ChangeAclSettings extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'This migration is irreversible. Down direction WILL HAVE NO EFFECT.';

	protected $_aclSettingNewValues = array(
		'Access Control.multiRole' => 1,
		'Access Control.rowLevel' =>  1,
		'Access Control.models' =>'["*"]'
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
		if ($direction === 'up') {

			$Setting = $this->generateModel('Setting');
			$aclSettings = $Setting->find('all', array('conditions' => array('key' => array(
				'Access Control.multiRole',
				'Access Control.rowLevel',
				'Access Control.models'
			))));

			foreach ($aclSettings as &$aclSetting) {
				$aclSetting['Setting']['value'] = $this->_aclSettingNewValues[$aclSetting['Setting']['key']];
			}

			$success = $Setting->saveMany($aclSettings);
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
