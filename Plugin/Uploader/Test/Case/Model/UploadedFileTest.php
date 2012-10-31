<?php
App::uses('UploadedFile', 'Uploader.Model');

/**
 * UploadedFile Test Case
 *
 */
class UploadedFileTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.uploader.uploaded_files_user',
		'plugin.uploader.uploaded_file',
		'plugin.uploader.user',
		'plugin.uploader.file_storage'
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
		parent::setUp();
		$this->UploadedFile = ClassRegistry::init('Uploader.UploadedFile');
		$this->data = array(
			'FileStorage' => array(
				'file' => array(
					'name' => 'Fraise.jpg',
					'type' => 'text/x-gettext-translation',
					'tmp_name' => '/tmp/phpTmnlQd',
					'error' => (int) 0,
					'size' => (int) 71881
		)));
		Configure::write('FileStorage.adapter', 'Test');
		file_put_contents('/tmp/phpTmnlQd', 'Hi !');
	}

	public function tearDown() {
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
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => 'MygreatSharing'));
		$result = $this->UploadedFile->addSharing($data, $userId);
		$this->assertEqual($result['UploadedFile']['filename'], 'MygreatSharing');
		$this->assertEqual($result['UploadedFile']['parent_id'], null);
	}

	public function testAddSharingFilenameError() {
		$userId = 1;
		$data = array('UploadedFile' => array('filename' => ''));
		$result = $this->UploadedFile->addSharing($data, $userId);
		$this->assertFalse($result);
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
				'adapter' => 'Local'
			),
			5 => array(
				'real_path' => 'Photos/Fruits/pommes.jpg',
				'remote_path' => '1/5/1-fc13ddddf3f37ecb451d76665f2f4b29d8dd0220',
				'adapter' => 'Local'
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
		$user_id = 1;
		$this->UploadedFile->upload($this->data, $user_id, 5);
		$result = $this->UploadedFile->find('first', array('conditions' => array('UploadedFile.filename' => 'Fraise.jpg')));
		$this->assertEqual($result['UploadedFile']['id'], 4);
		$this->assertEqual($result['UploadedFile']['current_version'], 1);
		$this->assertEqual($result['UploadedFile']['user_id'], 1);		
	}

	public function testUploadFileNotExist() {
		Configure::write('FileStorage.filePattern', '{user_id}/{file_id}-{version}-{filename}');
		$user_id = 1;
		$this->data['FileStorage']['file']['name'] = 'Newfile.jpg';
		$filename = Security::hash('Newfile.jpg');
		$this->UploadedFile->upload($this->data, $user_id, 1);
		
		$result = $this->UploadedFile->find('first', array('order' => 'UploadedFile.id desc'));
		$this->assertEqual($result['UploadedFile']['id'], 7);
		$this->assertEqual($result['UploadedFile']['filename'], 'Newfile.jpg');
		$this->assertEqual($result['UploadedFile']['current_version'], 1);

		$result = $this->UploadedFile->FileStorage->find('first', array('order' => 'id desc'));
		$this->assertEqual($result['FileStorage']['foreign_key'], 7);
		$this->assertEqual($result['FileStorage']['path'], '1/7-1-' . $filename);
		$this->assertEqual($result['FileStorage']['user_id'], $user_id);
	}

	public function testUploadFileNotExistButAnotherFileHasTheSameName() {
		$user_id = 1;
		$folder_id = 2;
		$this->UploadedFile->upload($this->data, $user_id, $folder_id);
		$result = $this->UploadedFile->find('first', array('order' => 'UploadedFile.id desc'));
		$this->assertEqual($result['UploadedFile']['id'], 7);
		$this->assertEqual($result['UploadedFile']['filename'], 'Fraise.jpg');
		$this->assertEqual($result['UploadedFile']['current_version'], 1);
	}

	/**
	 * Vérifie que le fichier est corrextement copier dans le dossier du
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

/**
 * Test ACOs
 */
	public function testAcoAfterAddSharing() {
		$Aco = ClassRegistry::init('Aco');

		$userId = 1;
		$data = array('UploadedFile' => array('filename' => 'MygreatSharing'));
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->addSharing($data, $userId);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert - $nbAcoBeforeInsert, 1);
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

	public function testUploadNewFile() {
		$Aco = ClassRegistry::init('Aco');

		$folderId = 1;
		$userId = 1;
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->upload($this->data, $userId, $folderId);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert - $nbAcoBeforeInsert, 1);
	}

	public function testUploadNewVersion() {
		$Aco = ClassRegistry::init('Aco');

		$folderId = 3;
		$userId = 1;
		$nbAcoBeforeInsert = $Aco->find('count');
		$this->UploadedFile->upload($this->data, $userId, $folderId);
		$nbAcoAfterInsert = $Aco->find('count');
		$this->assertEqual($nbAcoAfterInsert, $nbAcoBeforeInsert);
	}
}
