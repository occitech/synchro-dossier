<?php
/**
 * FilesControllerTest file
 *
 * PHP 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Case.Controller
 * @since         CakePHP(tm) v 1.2.0.5436
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('FilesController', 'Uploader.Controller');

App::uses('CroogoControllerTestCase', 'Croogo.TestSuite');

/**
 * FilesControllerTest class
 *
 * @package       Cake.Test.Case.Controller
 */
class FilesControllerTest extends CroogoControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.uploader.uploaded_file',
		'plugin.uploader.comment',
		'plugin.uploader.profile',
		'plugin.uploader.file_storage',
		'plugin.uploader.user',
		'plugin.uploader.role',
		'plugin.uploader.roles_user',
		'plugin.uploader.aros_aco',
		'plugin.uploader.aco',
		'plugin.uploader.aro',
		'plugin.Menus.Menu',
		'plugin.Menus.Link',
		'plugin.Blocks.Region',
		'plugin.Blocks.Block',
		'plugin.Nodes.Node',
		'plugin.Taxonomy.NodesTaxonomy',
		'plugin.Meta.Meta',
		'plugin.Taxonomy.Taxonomy',
		'plugin.Taxonomy.Type',
		'plugin.Taxonomy.Term',
		'plugin.Taxonomy.Vocabulary',
		'plugin.Taxonomy.TypesVocabulary',
		'plugin.SynchroDossier.SdInformation',
	);
		// 'plugin.Menus.Link'
	public $FilesController;
	public $user = array(
		'id' => '1',
		'role_id' => '1',
		'creator_id' => '0',
		'username' => 'admin',
		'name' => 'admin',
		'email' => 'admin@occi-tech.com',
		'website' => null,
		'activation_key' => 'cfc4efc9a8567a2c67f18af9d9401841',
		'image' => null,
		'bio' => null,
		'timezone' => '0',
		'status' => true,
		'updated' => '2012-12-21 10:22:56',
		'created' => '2012-12-21 01:24:49',
		'Role' => array(
			'id' => '1',
			'title' => 'Occitech',
			'alias' => 'admin',
			'created' => '2009-04-05 00:10:34',
			'updated' => '2009-04-05 00:10:34'
		)
	);
	public $content;
	public $result;
	public function setUp() {
		parent::setUp();
		$this->controller = new FilesController(new CakeRequest(null, false), new CakeResponse());
		$this->controller->constructClasses();
		$this->controller->Components->init($this->controller);
		$this->users = new UserFixture();
	}

	public function test_can_browse_with_system_admin() {
		$this->_assertCanBrowse($this->users->records[0]);
	}

	protected function _assertCanBrowse($user) {
		$this->assertTrue($this->controller->Auth->login($user), 'Cannot Login User');
		try {
			$this->testAction('uploader/files/browse');
			$result = $this->controller->render();
			$statusCode = $result->statusCode();
			$this->assertEquals('200', $statusCode, sprintf('Invalid Status code %s', $statusCode));
		} catch (Exception $e) {}

		$this->assertTrue(!is_null($result), 'Cannot execute browse');
	}
}
