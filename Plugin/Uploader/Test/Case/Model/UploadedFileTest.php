<?php
App::import('Vendor', 'OccitechCakeTestCase');
App::uses('UploadedFile', 'Uploader.Model');
App::uses('CakeEventManager', 'Event');

/**
 * UploadedFile Test Case
 *
 */
class UploadedFileTest extends OccitechCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.uploader.taxonomies_uploaded_file',
		'plugin.uploader.uploader_taxonomy',
		'plugin.uploader.uploaded_file',
		'plugin.uploader.file_storage',
		'plugin.uploader.uploader_user',
		'plugin.uploader.roles_user',
		'plugin.uploader.uploader_profile',
		'plugin.uploader.uploader_role',
		'plugin.uploader.uploader_aco',
		'plugin.uploader.uploader_aro',
		'plugin.uploader.uploader_aros_aco',
		'plugin.uploader.uploader_comment',
		'plugin.uploader.uploader_sd_information',
		'plugin.sd_users.sd_users_collaboration',
	);

	protected $_settings = array(
		'sd.slugFilenameWhenExport' => '',
		'sd.config.maxDownloadableZipSize' => ''
	);

/**
 * Remove content of the folder and all subfolder !
 */
	protected function _rmContentDir($dir) {
		if (is_dir($dir)) {
			$contents = scandir($dir);
			foreach ($contents as $content) {
				if ($content != "." && $content != "..") {
					if (filetype($dir."/".$content) == "dir") {
						$this->_rmContentDir($dir."/".$content);
					} else {
						unlink($dir."/".$content);
					}
				}
			}
			reset($contents);
		}
	}

	protected function _runProtectedMethod($name, $args = array()) {
		$method = new ReflectionMethod(get_class($this->UploadedFile), $name);
		$method->setAccessible(true);
		return $method->invokeArgs($this->UploadedFile, $args);
	}

	public function setUp() {
		foreach ($this->_settings as $setting) {
			$this->_settings[$setting] = Configure::read($setting);
		}

		Configure::write('sd.slugFilenameWhenExport', true);

		Configure::write('Acl.database', 'test');
		parent::setUp();
		$this->UploadedFile = ClassRegistry::init('Uploader.UploadedFile');
		$this->data = array(
			'file' => array(
				'name' => 'Fraise.jpg',
				'type' => 'text/x-gettext-translation',
				'tmp_name' => '/tmp/phpTmnlQd',
				'error' => (int) 0,
				'size' => (int) 71881
		));
		Configure::write('FileStorage.adapter', 'Test');
		file_put_contents('/tmp/phpTmnlQd', 'Hi !');

		$this->detachEvent('Model.UploadedFile.beforeUpload');
		$this->detachEvent('Model.UploadedFile.afterUploadSuccess');
		$this->detachEvent('Model.UploadedFile.afterUploadFailed');
		$this->detachEvent('Model.UploadedFile.AfterSharingCreation');
	}

	public function tearDown() {
		foreach ($this->_settings as $setting) {
			Configure::write($setting, $this->_settings[$setting]);
		}

		unset($this->UploadedFile);
		$folder = Configure::read('FileStorage.testFolder');
		$this->_rmContentDir($folder);
		parent::tearDown();
	}

/**
 * Test isUniqueName validation
 */
	public function testIsUniqueNameOk() {
		$check = array('filename' => 'yesthisnameisreallyunique');
		$this->UploadedFile->data = array(
			'UploadedFile' => array(
				'parent_id' => 1,
				'is_folder' => 1,
				'user_id' => 1
			)
		);
		$result = $this->UploadedFile->isUniqueName($check);
		$this->assertTrue($result);
	}

	public function testIsUniqueNameAlreadyUsed() {
		$check = array('filename' => 'Fraise.jpg');
		$this->UploadedFile->data = array(
			'UploadedFile' => array(
				'parent_id' => 3,
				'is_folder' => 0,
				'user_id' => 1
			)
		);
		$result = $this->UploadedFile->isUniqueName($check);
		$this->assertFalse($result);
	}

	public function testIsUniqueNameButNameNotChange() {
		$check = array('filename' => 'name1.jpg');
		$this->UploadedFile->data = array(
			'UploadedFile' => array(
				'parent_id' => 8,
				'is_folder' => 1,
				'user_id' => 1
			)
		);
		$result = $this->UploadedFile->isUniqueName($check);
		$this->assertTrue($result);
	}


/**
 * Test addFolder
 */
	public function testAddFolderParentNotExist() {
		$parentId = 32;
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => 'MyBadFolder'));
		$result = $this->UploadedFile->addFolder($data, $parentId, $userId);
		$this->assertEqual($result, false);
	}

	public function testAddFolderParentIsFile() {
		$parentId = 7;
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => 'MyBadFolder'));
		$result = $this->UploadedFile->addFolder($data, $parentId, $userId);
		$this->assertEqual($result, false);
	}

	public function testAddFolderOk() {
		$parentId = 1;
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => 'MygreatFile'));

		$result = $this->UploadedFile->addFolder($data, $parentId, $userId);
		$this->assertEqual($result['UploadedFile']['filename'], 'MygreatFile');
	}

	public function testAddFolderFilenameError() {
		$parentId = 1;
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => ''));
		$result = $this->UploadedFile->addFolder($data, $parentId, $userId);
		$this->assertFalse($result);
	}

/**
 * Test addSharing
 */
	public function testAddSharingOk() {
		$this->detachEvent('Model.UploadedFile.AfterSharingCreation');
		$user['id'] = 1;
		$data = array('UploadedFile' => array('filename' => 'MygreatSharing'));
		$result = $this->UploadedFile->addSharing($data, $user);
		$this->assertEqual($result['UploadedFile']['filename'], 'MygreatSharing');
		$this->assertEqual($result['UploadedFile']['parent_id'], null);
	}

	public function testAddSharing_EventCorrectlyLaunched() {
		$data = array('UploadedFile' => array('filename' => 'MygreatSharing'));
		$expectedData = $data;
		$expectedData['UploadedFile']['id'] = 7;
		$user['id'] = 1;

		$callbackForNewMessage = $this->expectEventDispatched(
			'Model.UploadedFile.AfterSharingCreation',
			$this->isInstanceOf($this->UploadedFile),
			$this->logicalAnd($this->equalTo(array(
				'data' => $expectedData,
				'user' => $user
			)))
		);

		$result = $this->UploadedFile->addSharing($data, $user);
	}

	public function testAddSharingFilenameError() {
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => ''));
		$result = $this->UploadedFile->addSharing($data, $userId);
		$this->assertFalse($result);
	}

/**
 * Test getThreadedFolders
 */
	public function testGetThreadedFolders_AllFolders() {
		$result = $this->UploadedFile->getThreadedAllFolders();

		$this->assertEqual(count($result), 2);
	}

/**
 * Test getFoldersPath
 */
	public function testGetFoldersPathLittleFolder() {
		$result = $this->_runProtectedMethod('_getFoldersPath', array(1));
		$expected = array(
			1 => array(
				'real_path' => 'Photos/',
				'remote_path' => null,
				'adapter' => null
			),
			3 => array(
				'real_path' => 'Photos/Fruits/',
				'remote_path' => null,
				'adapter' => null
			),
			4 => array(
				'real_path' => 'Photos/Fruits/Fraise.jpg',
				'remote_path' => '1/4/1-1c082be57dd2a8b40831e1258ab2187f4eee044a',
				'adapter' => 'Test'
			),
			5 => array(
				'real_path' => 'Photos/Fruits/pommes.jpg',
				'remote_path' => '1/5/1-fc13ddddf3f37ecb451d76665f2f4b29d8dd0220',
				'adapter' => 'Test'
			)
		);
		$this->assertEqual($result, $expected);
	}

	public function testGetFoldersPathFolderNotExist() {
		$result = $this->_runProtectedMethod('_getFoldersPath', array(18));
		$this->assertEqual($result, false);
	}

	public function testGetFoldersPathFileGiven() {
		$result = $this->_runProtectedMethod('_getFoldersPath', array(7));
		$this->assertEqual($result, false);
	}

/**
 * Test _findRootDirectories()
 */
	public function testFindRootDirectories() {
		$result = $this->UploadedFile->find('rootDirectories');
		$this->assertEqual(count($result), 2);
	}

/**
 * Test getPathFile
 */
	public function testGetPathFile() {
		Configure::write('FileStorage.filePattern', '{user_id}/{file_id}-{version}-{filename}');
		$result = $this->_runProtectedMethod('_getPathFile', array(5, 2, 3, 'MygreatFile'));
		$this->assertEqual($result, '5/2-3-MygreatFile');
	}

/**
 * Test upload
 */
	public function testUploadAlreadyExist() {
		$user['id'] = 1;
		$user['role_id'] = 4;
		$this->UploadedFile->upload($this->data, $user, 5);
		$result = $this->UploadedFile->find('first', array('conditions' => array('UploadedFile.filename' => 'Fraise.jpg')));
		$this->assertEqual($result['UploadedFile']['id'], 4);
		$this->assertEqual($result['UploadedFile']['current_version'], 1);
		$this->assertEqual($result['UploadedFile']['user_id'], 1);
	}

	public function testUploadFileNotExist() {
		Configure::write('FileStorage.filePattern', '{user_id}/{file_id}-{version}-{filename}');
		$user['id'] = 1;
		$user['role_id'] = 4;
		$this->data['file']['name'] = 'Newfile.jpg';
		$filename = Security::hash('Newfile.jpg');
		$this->UploadedFile->upload($this->data, $user, 1);

		$result = $this->UploadedFile->find('first', array('order' => 'UploadedFile.id desc'));
		$this->assertEqual($result['UploadedFile']['id'], 7);
		$this->assertEqual($result['UploadedFile']['filename'], 'Newfile.jpg');
		$this->assertEqual($result['UploadedFile']['current_version'], 1);

		$result = $this->UploadedFile->FileStorage->find('first', array('order' => 'id desc'));
		$this->assertEqual($result['FileStorage']['foreign_key'], 7);
		$this->assertEqual($result['FileStorage']['path'], '1/7-1-' . $filename);
		$this->assertEqual($result['FileStorage']['user_id'], $user['id']);
	}

	public function testUploadFileNotExistButAnotherFileHasTheSameName() {
		$user['id'] = 1;
		$user['role_id'] = 4;
		$folder_id = 2;
		$this->UploadedFile->upload($this->data, $user, $folder_id);
		$result = $this->UploadedFile->find('first', array('order' => 'UploadedFile.id desc'));
		$this->assertEqual($result['UploadedFile']['id'], 7);
		$this->assertEqual($result['UploadedFile']['filename'], 'Fraise.jpg');
		$this->assertEqual($result['UploadedFile']['current_version'], 1);
	}

	/**
	 * Vérifie que le fichier est correctement copier dans le dossier du
	 * propriétaire du dossier parent
	 */
	public function testUploadAlreadyExistVerifyFileIsCreated() {
		$uploader_id = 1;
		$owner_id = 1;
		$parent_id = 3;
		Configure::write('FileStorage.filePattern', '{user_id}/{file_id}-{version}-{filename}');
		$filename = Security::hash('Fraise.jpg');

		$this->UploadedFile->upload($this->data, $uploader_id, $parent_id);
		$result = file_exists(Configure::read('FileStorage.testFolder') . DS . $owner_id . DS . '4-2-' . $filename);
		$this->assertEqual($result, true);
	}

	public function listener_SetHasErrorToTrue ($event) {
		$event->result['hasError'] = true;
		$event->result['message'] = 'really ?';
	}

	public function testUpload_EventAfterUploadFailed_CorrectlyLaunched() {
		$user = array('id' => 1, 'role_id' => 4);
		$data = array(
			'file' => array(
				'name' => 'Fraise.jpg',
				'type' => 'text/x-gettext-translation',
				'tmp_name' => '/tmp/phpTmnlQd',
				'error' => 0,
				'size' => 71881000000000
			)
		);

		$this->UploadedFile->getEventManager()->attach(
			array($this, 'listener_SetHasErrorToTrue'),
			'Model.UploadedFile.beforeUpload'
		);

		$expectedArray = array(
			'data' => $data,
			'user' => $user,
			'beforeUploadResult' => array (
				'hasError' => true,
				'message' => 'really ?'
			)
		);

		$callbackForNewMessage = $this->expectEventDispatched(
			'Model.UploadedFile.afterUploadFailed',
			$this->isInstanceOf($this->UploadedFile),
			$this->logicalAnd($this->equalTo($expectedArray))
		);

		$this->UploadedFile->upload($data, $user, 3);
	}

	public function testUpload_EventBeforeUpload_CorrectlyLaunched() {
		$user = array('id' => 1, 'role_id' => 4);
		$data = array(
			'file' => array(
				'name' => 'Fraise.jpg',
				'type' => 'text/x-gettext-translation',
				'tmp_name' => '/tmp/phpTmnlQd',
				'error' => 0,
				'size' => 71881
			)
		);

		$callbackForNewMessage = $this->expectEventDispatched(
			'Model.UploadedFile.beforeUpload',
			$this->isInstanceOf($this->UploadedFile),
			$this->logicalAnd($this->equalTo(array('data' => $data, 'user' => $user)))
		);

		$this->UploadedFile->upload($data, $user, 3);
	}

	public function testUpload_AfterUploadSucces_CorrectlyLaunched() {
		$user = array('id' => 1, 'role_id' => 4);
		$data = array(
			'file' => array(
				'name' => 'Fraise.jpg',
				'type' => 'text/x-gettext-translation',
				'tmp_name' => '/tmp/phpTmnlQd',
				'error' => 0,
				'size' => 71881
			)
		);

		$expectedData = $data;
		$expectedData['file']['id'] = 4;

		$callbackForNewMessage = $this->expectEventDispatched(
			'Model.UploadedFile.afterUploadSuccess',
			$this->isInstanceOf($this->UploadedFile),
			$this->logicalAnd($this->equalTo(array('data' => $expectedData, 'user' => $user)))
		);

		$this->UploadedFile->upload($data, $user, 3);
	}

	public function testUpload_NewVersion_FileStorageCreated() {
		$FileStorage = ClassRegistry::init('FileStorage');

		$folderId = 3;
		$user['id'] = 1;
		$user['role_id'] = 4;
		$nbAcoBeforeInsert = $FileStorage->find('count');
		$this->UploadedFile->upload($this->data, $user, $folderId);
		$nbAcoAfterInsert = $FileStorage->find('count');
		$this->assertEqual($nbAcoAfterInsert, $nbAcoBeforeInsert + 1);
	}

/**
 * Test ACOs
 */
	public function testAcoAfterAddSharing() {
		$Aco = ClassRegistry::init('Aco');

		$user['id'] = 1;
		$data = array('UploadedFile' => array('filename' => 'MygreatSharing'));
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->addSharing($data, $user);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert - $nbAcoBeforeInsert, 1);
	}

	public function testAco_AfterAddSharing_ParentIdIsCorrect() {
		$Aco = ClassRegistry::init('Aco');

		$user['id'] = 1;
		$data = array('UploadedFile' => array('filename' => 'MygreatSharing'));
		$this->UploadedFile->addSharing($data, $user);
		$result = $Aco->find('first', array('order' => 'id DESC'));
		$this->assertEqual($result['Aco']['parent_id'], 1);
	}

	public function testAcoAfterAddSharingAlreadyExist() {
		$Aco = ClassRegistry::init('Aco');

		$userId = 1;
		$data = array('UploadedFile' => array('filename' => 'Photos'));
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->addSharing($data, $userId);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert, $nbAcoBeforeInsert);
	}

	public function testAcoAfterAddFolder() {
		$Aco = ClassRegistry::init('Aco');

		$folderId = 3;
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => 'Photos'));
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->addFolder($data, $folderId, $userId);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert - $nbAcoBeforeInsert, 1);
	}

	public function testAco_AfterAddFolder_ParentIdIsCorrect() {
		$Aco = ClassRegistry::init('Aco');

		$folderId = 3;
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => 'Photos'));
		$this->UploadedFile->addFolder($data, $folderId, $userId);
		$acoAfterInsert = $Aco->find('first', array('order' => 'id DESC'));
		$parentIdExpected = 3;
		$this->assertEqual($acoAfterInsert['Aco']['parent_id'], $parentIdExpected);
	}

	public function testUpload_NewFile() {
		$Aco = ClassRegistry::init('Aco');

		$folderId = 1;
		$user['id'] = 1;
		$user['role_id'] = 4;
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->upload($this->data, $user, $folderId);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert - $nbAcoBeforeInsert, 1);
	}

	public function testUpload_AccentuationMeansNewFile() {
		$Aco = ClassRegistry::init('Aco');
		$this->data['file']['name'] = 'Fraisé.jpg';

		$folderId = 3;
		$user['id'] = 1;
		$user['role_id'] = 4;
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->upload($this->data, $user, $folderId);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert, $nbAcoBeforeInsert + 1);
	}

	public function testUpload_NewVersion() {
		$Aco = ClassRegistry::init('Aco');

		$folderId = 3;
		$user['id'] = 1;
		$user['role_id'] = 4;
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->upload($this->data, $user, $folderId);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert, $nbAcoBeforeInsert);
	}

	public function testUpload_NewVersion_AcoParentId_NotChanged() {
		$folderId = 3;
		$user['id'] = 1;
		$user['role_id'] = 4;

		$fileInfosBefore = $this->UploadedFile->findByFilenameAndParent_id($this->data['file']['name'], $folderId);
		$this->UploadedFile->upload($this->data, $user, $folderId);
		$fileInfosAfter = $this->UploadedFile->findByFilenameAndParent_id($this->data['file']['name'], $folderId);

		$this->assertEqual($fileInfosAfter['Aco']['parent_id'], $fileInfosBefore['Aco']['parent_id']);
	}

	public function testIsRootFolderOk() {
		$result = $this->UploadedFile->isRootFolder(1);

		$this->assertTrue($result);
	}

	public function testIsRootFolderNotOk() {
		$result = $this->UploadedFile->isRootFolder(5);

		$this->assertFalse($result);
	}

/**
 * Test remove method
 */
	public function testRemoveFile() {
		$file = $this->__createFileOnFileSystem('509116da-0008-4958-909a-1c21d4b04a59');

		$result = $this->UploadedFile->removeFile(5, $file['FileStorage']['id'], 1);
		$this->assertTrue($result);

		$fileDeleted = $this->UploadedFile->find('first', array(
			'conditions' => array('UploadedFile.filename' => 'pommes.jpg', 'UploadedFile.parent_id' => 3)
		));

		$this->assertTrue(empty($value));
	}

	public function testRemoveFileDeleteFilesInFileSystem() {
		$file = $this->__createFileOnFileSystem('509116da-0008-4958-909a-1c21d4b04a59');

		$success = $this->UploadedFile->removeFile(5, $file['FileStorage']['id'], 1);

		$this->assertFalse(is_file(APP . 'tmp' . DS . 'tests' . DS . 'Uploader' . DS . $file['FileStorage']['path']));
	}


	public function testRemoveFileShouldDeleteParentFolderIfEmpty() {
		$file = $this->__createFileOnFileSystem('509116da-0008-4958-909a-1c21d4b04a59');

		$success = $this->UploadedFile->removeFile(5, $file['FileStorage']['id'], 1);

		$dir = preg_replace('/\/[^\/]*$/', '', $file['FileStorage']['path']);

		$this->assertFalse(is_dir(APP . 'tmp' . DS . 'tests' . DS . 'Uploader' . DS . $dir));
	}

	public function testRemoveFolder() {
		$result = $this->UploadedFile->removeFolder(3, 1);
		$this->assertTrue($result);

		$folderDeleted = $this->UploadedFile->find('first', array(
			'conditions' => array('UploadedFile.filename' => 'Fruits', 'UploadedFile.parent_id' => 1)
		));
		$fileDeleted1 = $this->UploadedFile->find('first', array(
			'conditions' => array('UploadedFile.filename' => 'Fraises.jpg', 'UploadedFile.parent_id' => 3)
		));
		$fileDeleted2 = $this->UploadedFile->find('first', array(
			'conditions' => array('UploadedFile.filename' => 'pommes.jpg', 'UploadedFile.parent_id' => 3)
		));

		foreach (array($fileDeleted1, $fileDeleted2) as $value) {
			$this->assertTrue(empty($value));
		}
	}

	public function testRemoveFolder_ShouldRemoveNestedFolder() {
		$result = $this->UploadedFile->removeFolder(1, 1);
		$this->assertTrue($result);

		$foldersExists = $this->UploadedFile->find('all', array(
			'conditions' => array('UploadedFile.parent_id' => 1)
		));

		$this->assertFalse(!empty($foldersExists));
	}

	public function testRemoveFolder_ShouldThrowExceptionIfFileIdIsNotAFolder() {
		$this->setExpectedException('InvalidArgumentException');
		$this->UploadedFile->removeFolder(5, 1);
	}

	public function testRemoveFolder_DeleteFilesInFileSystem() {
		$files = $this->_runProtectedMethod('_getFoldersPath', array(3));
		unset($files[3]);
		if (!is_dir(APP . 'tmp' . DS . 'tests' . DS . 'Uploader' . DS . '1' . DS . '5')) {
			mkdir(APP . 'tmp' . DS . 'tests' . DS . 'Uploader' . DS . '1' . DS . '5');
		}

		foreach ($files as $file) {
			touch(APP . 'tmp' . DS . 'tests' . DS . 'Uploader' . DS . $file['remote_path']);
		}

		$success = $this->UploadedFile->removeFolder(3, 1);
		$this->assertTrue($success);

		foreach ($files as $file) {
			$this->assertFalse(is_file(APP . 'tmp' . DS . 'tests' . DS . 'Uploader' . DS . $file['remote_path']));
		}
	}

	public function testRemoveFolder_ShouldThrowExceptionIfInvalidId() {
		$this->setExpectedException('NotFoundException');
		$this->UploadedFile->removeFolder(42, 1);
	}

	public function testRemoveFolder_WhenNotOwner() {
		$result = $this->UploadedFile->removeFolder(1, 4);
		$this->assertFalse($result);
	}

	public function tesAdminCanRemoveAnyFolder() {
			$this->UploadedFile->id = 1;
			$this->assertTrue((bool) $this->UploadedFile->saveField('user_id', 4));
			$result = $this->UploadedFile->removeFolder(1, 1);
			$this->assertTrue($result);
	}

	public function testRemoveFile_WhenNotOwner() {
		$result = $this->UploadedFile->removeFile(4, '509116da-0008-4958-909a-1c21d4b04a59', 4);
		$this->assertFalse($result);
	}

	public function testRemoveFile_ShouldThrowExceptionIfInvalidId() {
		$this->setExpectedException('NotFoundException');
		$this->UploadedFile->removeFile(42, '509116da-0008-4958-909a-1c21d4b04a59', 1);
	}

	private function __createFileOnFileSystem($fileStorageId) {
		$file = $this->UploadedFile->FileStorage->find('first', array(
			'conditions' => array('FileStorage.id' => $fileStorageId)
		));

		$pathStart = (APP . 'tmp' . DS . 'tests' . DS . 'Uploader' . DS);

		if (!is_dir($pathStart . '1' . DS . $file['FileStorage']['foreign_key'])) {
			mkdir($pathStart . '1' . DS . $file['FileStorage']['foreign_key']);
		}
		touch($pathStart . $file['FileStorage']['path']);

		return $file;
	}

/**
 * test sluggifyFilename method
 */
	public function testSluggifyFilename() {
		$hasardousEncodingString = 'héllô lès côpaïns.pdf';
		$expected = 'hello-les-copains.pdf';

		$this->assertEquals($expected, $this->UploadedFile->sluggifyFilename($hasardousEncodingString));
	}

	public function testSluggifyFilenameWithoutExtension() {
		$hasardousEncodingString = 'héllô lès côpaïns';
		$expected = 'hello-les-copains';

		$this->assertEquals($expected, $this->UploadedFile->sluggifyFilename($hasardousEncodingString));
	}

	public function testSluggifyFilenameWithDotsSeparator() {
		$hasardousEncodingString = 'héllô.lès.côpaïns.pdf';
		$expected = 'hello-les-copains.pdf';

		$this->assertEquals($expected, $this->UploadedFile->sluggifyFilename($hasardousEncodingString));
	}

/**
 * Test search
 */

	public function testSearchWithoutParameters(){
		$criteria = array();
		$conditions = $this->UploadedFile->parseCriteria($criteria);

		$results = $this->UploadedFile->find('all', array('conditions' => $conditions));
		$expected = $this->UploadedFile->find('all');

		$this->assertEquals($expected, $results);
	}

	public function testSearchByFileNameOnly(){
		$criteria = array('filename' => 'Fraise');
		$conditions = $this->UploadedFile->parseCriteria($criteria);

		$results = $this->UploadedFile->find('all', array('conditions' => $conditions));
		$expected = array(
			'id' => '4',
			'filename' => 'Fraise.jpg',
			'size' => '34639',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '3',
			'is_folder' => '0',
			'lft' => '3',
			'rght' => '4',
			'mime_type' => 'image/jpeg'
		);

		$this->assertEquals($expected, $results[0]['UploadedFile']);
	}

	public function testSearchByFileExtOnly(){
		$criteria = array('extension' => 'ods');
		$conditions = $this->UploadedFile->parseCriteria($criteria);

		$results = $this->UploadedFile->find('all', array('conditions' => $conditions));
		$expected = array(
			'id' => '6',
			'filename' => '2012-comptes.ods',
			'size' => '136812534',
			'user_id' => '1',
			'current_version' => '1',
			'available' => '0',
			'parent_id' => '2',
			'is_folder' => '0',
			'lft' => '10',
			'rght' => '11',
			'mime_type' => 'application/vnd.oasis.opendocument.spreadsheet'
		);
		$this->assertEquals($expected, $results[0]['UploadedFile']);
	}

	public function testCanDownloadFolderAsZip() {
		Configure::write('sd.config.maxDownloadableZipSize', 1024*1024*1024);
		$result = $this->UploadedFile->canDownloadFolderAsZip(1);
		$this->assertTrue($result);
	}

	public function testCanDownloadFolderAsZipWhenExceedingSizeLimit() {
		Configure::write('sd.config.maxDownloadableZipSize', 1024);
		$result = $this->UploadedFile->canDownloadFolderAsZip(1);
		$this->assertFalse($result);
	}

	public function testCanDownloadFolderAsZipWithInvalidFolderId(){
		$this->setExpectedException('NotFoundException');
		$result = $this->UploadedFile->canDownloadFolderAsZip(42);
	}

	public function testCanDownloadFolderAsZipWithEmptyFolder(){
		$this->_addFolder(1, array('UploadedFile' => array('filename' => 'Empty Folder')));
		$result = $this->UploadedFile->canDownloadFolderAsZip($this->UploadedFile->getLastInsertID());
		$this->assertFalse($result);
	}

	public function testCanDownloadFolderAsZipWithFileId(){
		$this->setExpectedException('InvalidArgumentException');
		$result = $this->UploadedFile->canDownloadFolderAsZip(6);
	}

	protected function _addFolder($userId, $folderData, $parentId = null) {
		$result = $this->UploadedFile->addFolder($folderData, $parentId, $userId);
		$this->assertTrue((bool) $result);

		return $result;
	}

	public function testParentNodeShouldReturnParentIdWhenExistingButNotSetInData() {
		$this->UploadedFile->data = array($this->UploadedFile->alias => array($this->UploadedFile->primaryKey => 4));
		$parentId = $this->UploadedFile->parentNode();
		$expectedParentId = array(
			$this->UploadedFile->alias => array('id' => 3)
		);

		$this->assertEquals($expectedParentId, $parentId);
	}

	public function testFirstUploadCreateVersionOneFile() {
		$this->data['file']['name'] = 'ICertainlyDontExistYet.impossibleExtension';
		$this->UploadedFile->upload($this->data, array('id' => 1), 1);

		$uploadedFile = $this->__getLatestUploadedFiled();

		$this->assertEquals(1, $uploadedFile['UploadedFile']['current_version']);
		$this->assertEquals(1, $uploadedFile['FileStorage'][0]['file_version']);
	}

	public function testSecondUploadCreateVersionTwoFile() {
		$this->data['file']['name'] = 'ICertainlyDontExistYet.impossibleExtension';
		$this->UploadedFile->upload($this->data, array('id' => 1), 1);
		$this->UploadedFile->upload($this->data, array('id' => 1), 1);

		$uploadedFile = $this->__getLatestUploadedFiled();

		$this->assertEquals(2, $uploadedFile['UploadedFile']['current_version']);
		$this->assertEquals(2, $uploadedFile['FileStorage'][0]['file_version']);
	}

	public function testDeletingAVersionKeepTheCurrentVersionAtItsCurrentValue() {
		$this->data['file']['name'] = 'ICertainlyDontExistYet.impossibleExtension';
		$this->UploadedFile->upload($this->data, array('id' => 1), 1);
		$this->UploadedFile->upload($this->data, array('id' => 1), 1);

		$uploadedFile = $this->__getLatestUploadedFiled();

		$this->UploadedFile->removeFile(
			$uploadedFile['UploadedFile']['id'],
			$uploadedFile['FileStorage'][0]['id'],
			1
		);

		$uploadedFile = $this->__getLatestUploadedFiled();
		$this->assertEquals(2, $uploadedFile['UploadedFile']['current_version']);
	}

	public function testDeletingVersionTwoAndUploadingANewVersionCreateVersion3() {
		$this->data['file']['name'] = 'ICertainlyDontExistYet.impossibleExtension';
		$this->UploadedFile->upload($this->data, array('id' => 1), 1);
		$this->UploadedFile->upload($this->data, array('id' => 1), 1);

		$uploadedFile = $this->__getLatestUploadedFiled();

		$this->UploadedFile->removeFile(
			$uploadedFile['UploadedFile']['id'],
			$uploadedFile['FileStorage'][0]['id'],
			1
		);

		$this->UploadedFile->upload($this->data, array('id' => 1), 1);

		$uploadedFile = $this->__getLatestUploadedFiled();
		$this->assertEquals(3, $uploadedFile['UploadedFile']['current_version']);
		$this->assertEquals(3, $uploadedFile['FileStorage'][0]['file_version']);
	}

	private function __getLatestUploadedFiled()
	{
		return $this->UploadedFile->find('first', array(
			'conditions' => array(
				$this->UploadedFile->escapeField() => $this->UploadedFile->getLastInsertId(),
			),
			'contain' => array(
				'FileStorage' => array(
					'order' => array('FileStorage.file_version DESC'),
				),
			),
		));
	}

}
