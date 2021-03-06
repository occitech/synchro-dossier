<?php
App::uses('FilesController', 'Uploader.Controller');
App::uses('CroogoControllerTestCase', 'Croogo.TestSuite');

class FilesControllerTest extends CroogoControllerTestCase {

	public $fixtures = array(
		'plugin.translate.i18n',
		'plugin.uploader.uploader_aros_aco',
		'plugin.uploader.uploader_aro',
		'plugin.uploader.uploader_aco',
		'plugin.sd_users.sd_users_role',
		'plugin.sd_users.sd_users_user',
		'plugin.nodes.node',
		'plugin.menus.menu',
		'plugin.settings.setting',
		'plugin.settings.language',
		'plugin.taxonomy.type',
		'plugin.taxonomy.types_vocabulary',
		'plugin.uploader.uploader_vocabulary',
		'plugin.uploader.uploader_term',
		'plugin.uploader.uploader_taxonomy',
		'plugin.uploader.uploaded_file',
		'plugin.uploader.region',
		'plugin.uploader.uploader_profile',
		'plugin.uploader.link',
		'plugin.uploader.block',
		'plugin.uploader.taxonomies_uploaded_file',
		'plugin.uploader.file_storage',
		'plugin.uploader.uploader_comment',
		'plugin.uploader.users_collaboration',
		'plugin.taxonomy.model_taxonomy'
	);

	public function tearDown() {
		return ControllerTestCase::tearDown();
	}

	public function testTagCanBeAddedToAFile() {
		$this->testAction('/uploader/files/addTags/4', array(
			'method' => 'POST',
			'data' => array(
				'Tags' => array(
					'tags' => 'foo, bar, baz, foobar'
				),
			),
		));

		$file = ClassRegistry::init('Uploader.UploadedFile')->find('first', array(
			'conditions' => array('UploadedFile.id' => 4),
			'contain' => array('FileTag', 'FileTag.Term'),
		));

		$foobarFileTagId = ClassRegistry::init('Uploader.FileTag')->getLastInsertId();

		$this->assertEquals(array(1, 2, 3, $foobarFileTagId), Hash::extract($file, 'FileTag.{n}.id'));
	}

	public function testMimeTypeIsSetWhileDownloadingAFile() {
		$FilesController = $this->generate('Uploader.Files', array(
			'models' => array(
				'UploadedFile' => array('download'),
			),
		));
		$FilesController->UploadedFile->expects($this->once())
			->method('download')
			->with('509116b6-4d00-4737-8527-2a9ad4b04a59')
			->will($this->returnValue(array(null, 'Fraise.jpg', 'image/jpeg')));

		$this->testAction('/uploader/files/download/509116b6-4d00-4737-8527-2a9ad4b04a59');

		$this->assertAttributeEquals('image/jpeg', '_contentType', $FilesController->response);
	}

	public function testAddTagToFileDoesNotUpdateAcoTreePositon() {
		$this->generate('Uploader.Files', array('components' => array('Auth' => array('user'))));
		$this->controller->Auth->expects($this->any())
			->method('user')
			->will($this->returnValue(array(
				'id' => '2',
				'role_id' => '6',
				'username' => 'aymeric',
				'password' => '935dce4494121f848ffe2d3337ed2c05192526b1',
				'name' => 'Derbois',
				'email' => 'aymeric@derbois.com',
				'website' => '',
				'activation_key' => 'd6b0ca85517794669b14460dec519714',
				'image' => null,
				'bio' => null,
				'timezone' => '0',
				'status' => 1,
				'updated' => '2012-10-31 17:21:32',
				'created' => '2012-10-31 17:21:32'
			)));
		$varsBeforeTag = $this->testAction('/browse/3', array('method' => 'GET', 'return' => 'vars'));
		$this->testAction('/uploader/files/addTags/4', array(
			'method' => 'POST',
			'data' => array(
				'Tags' => array(
					'tags' => 'foo, bar, baz, foobar'
				),
			),
		));
		$varsAfterTag = $this->testAction('/browse/3', array('method' => 'GET', 'return' => 'vars'));

		$this->assertEquals($varsBeforeTag['files'][0]['Aco'], $varsAfterTag['files'][0]['Aco']);
	}

	public function testRenameFolderDoesNotUpdateAcoTreePositon() {
		$this->generate('Uploader.Files', array(
			'components' => array('Auth' => array('user')))
		);

		$this->controller->UploadedFile->User = $this->getMockForModel('User', array('exists', 'find'));
		$this->controller->UploadedFile->User->expects($this->any())
			->method('exists')
			->with('2')
			->will($this->returnValue(true));

		$this->controller->UploadedFile->User->expects($this->any())
			->method('find')
			->with('superAdmin')
			->will($this->returnValue(array()));

		$this->controller->Auth->expects($this->any())
			->method('user')
			->with(null)
			->will($this->returnValue(array(
				'id' => '2',
				'role_id' => '6',
				'username' => 'aymeric',
				'password' => '935dce4494121f848ffe2d3337ed2c05192526b1',
				'name' => 'Derbois',
				'email' => 'aymeric@derbois.com',
				'website' => '',
				'activation_key' => 'd6b0ca85517794669b14460dec519714',
				'image' => null,
				'bio' => null,
				'timezone' => '0',
				'status' => 1,
				'updated' => '2012-10-31 17:21:32',
				'created' => '2012-10-31 17:21:32'
			)));

		$varsBeforeTag = $this->testAction('/browse', array('method' => 'GET', 'return' => 'vars'));
		$this->testAction('/uploader/files/rename/1/3/2', array(
			'method' => 'POST',
			'data' => array(
				'UploadedFile' => array(
					'id' => 3,
					'filename' => 'renamedFruits'
				),
			),
		));
		$varsAfterTag = $this->testAction('/browse', array('method' => 'GET', 'return' => 'vars'));

		$this->assertEquals($varsBeforeTag['files'][0]['ChildUploadedFile'][0]['Aco'], $varsAfterTag['files'][0]['ChildUploadedFile'][0]['Aco']);
	}

	public function testFindFunctionShouldReturnSuperAdmin() {
		$vars = $this->testAction('/uploader/files/find', array(
			'method' => 'GET',
			'return' => 'vars',
		));

		$this->assertArrayHasKey('superAdmins', $vars);
	}

	public function testFindFiltersCorrespondingFileEntries() {
		$this->testAction('/uploader/files/find/filename:fraise', array(
			'data' => array(
				'UploadedFile' => array(
					'filename' => 'raise',
				),
			),
		));

		// "hack" to be able to use it in a new testaction
		$redirectUrl = preg_replace('#http://[^/]+' . dirname($_SERVER['PHP_SELF']) . '#', '', $this->headers['Location']);

		$vars = $this->testAction($redirectUrl, array(
			'method' => 'get',
			'return' => 'vars',
		));

		$this->assertContains('raise', $vars['files'][0]['UploadedFile']['filename']);
	}

	public function testBrowsingThroughFoldersShouldSetFolderTree() {
		$vars = $this->testAction('/uploader/files/browse/3', array(
			'method' => 'get',
			'return' => 'vars',
		));

		$this->assertEquals(array(0 => __d('uploader', 'Home'), 1 => 'Photos', 3 => 'Fruits'), $vars['folderTree']);
	}

}
