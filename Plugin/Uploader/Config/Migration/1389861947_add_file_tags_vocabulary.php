<?php

App::uses('ActionsAuthorizer', 'Lib');
App::uses('AclExtras', 'Acl.Lib');

class AddFileTagsVocabulary extends CakeMigration {

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
				'taxonomies_uploaded_files' => array(
					'id' => array('type' =>'integer', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'primary'),
					'uploaded_file_id' => array('type' => 'integer', 'null' => false, 'false' => null, 'length' => 20),
					'taxonomy_id' => array('type' => 'integer', 'null' => false, 'false' => null, 'length' => 20),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'taxonomies_uploaded_files',
			),
		),
	);

	private $__actionRoles = array(
		'Uploader/Files/addTags' => array(
			'sdSuperAdmin','sdAdmin'
		),
	);

	private $__rolesActionMethod = array(
		'up'   => 'allowRolesForAction',
		'down' => 'removeRolesForAction'
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		$success = false;

		$vocabularyName = 'File Tags';
		$Vocabulary = $this->generateModel('Vocabulary');

		if ($direction == 'up') {
			$Vocabulary->create();
			$Vocabulary->set(array(
				'title' => $vocabularyName,
				'alias' => Inflector::slug(strtolower($vocabularyName), '-'),
				'multiple' => true,
			));

			$success = (bool) $Vocabulary->save();
		} else {
			$success = $Vocabulary->deleteAll(array($Vocabulary->escapeField('title') => $vocabularyName));
		}

		$AclExtras = new AclExtras();
		$AclExtras->startup();
		$AclExtras->aco_update();

		$addAco = $this->__rolesActionMethod[$direction];
		$ActionsAuthorizer = new ActionsAuthorizer();

		foreach ($this->__actionRoles as $action => $roles) {
			try {
				$ActionsAuthorizer->{$addAco}($action, $roles);
				$success = $success && true;
			} catch (Exception $e) {
				$success = $success && false;
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
