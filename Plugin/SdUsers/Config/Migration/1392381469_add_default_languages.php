<?php
class AddDefaultLanguages extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Irreversible migrations to add default languages. Down direction won\'t have any effect.';

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

		if ($direction == 'up') {
			$Language = $this->generateModel('Language');
			$records = array(
				array(
					'title' => 'English',
					'native' => 'English',
					'alias' => 'eng',
					'status' => 1
				),
				array(
					'title' => 'French',
					'native' => 'Français',
					'alias' => 'fra',
					'status' => 1
				),
			);
			foreach ($records as $record) {
				if (!$Language->hasAny(array('alias' => $record['alias']))) {
					$success = $success && $Language->save($record);
				}
			}
		} else {
			echo $this->description . "\n";
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
