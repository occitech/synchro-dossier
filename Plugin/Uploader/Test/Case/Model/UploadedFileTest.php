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


///////////////////////////
/// Methods Validate    ///
///////////////////////////

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
		$check = array('filename' => 'toto.zip');
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

///////////////////////////
/// Methods for folders ///
///////////////////////////

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

	public function testCreateZip() {

	}

/////////////////////////
/// Methods for files ///
/////////////////////////

	public function testGetPathFile() {
		Configure::write('FileStorage.filePattern', '{user_id}/{file_id}-{version}-{filename}');
		$result = $this->_runProtectedMethod('_getPathFile', array(5, 2, 3, 'MygreatFile'));
		$this->assertEqual($result, '5/2-3-MygreatFile');
	}

	public function testUploadAlreadyExist() {
		$user_id = 1;
		$this->UploadedFile->upload($this->data, $user_id, 5);
		$result = $this->UploadedFile->find('first', array('conditions' => array('UploadedFile.filename' => 'Fraise.jpg')));
		$this->assertEqual($result['UploadedFile']['id'], 6);
		$this->assertEqual($result['UploadedFile']['current_version'], 2);
		$this->assertEqual($result['UploadedFile']['user_id'], 1);		
	}

	public function testUploadFileNotExist() {
		Configure::write('FileStorage.filePattern', '{user_id}/{file_id}-{version}-{filename}');
		$user_id = 1;
		$this->data['FileStorage']['file']['name'] = 'Newfile.jpg';
		$filename = Security::hash('Newfile.jpg');
		$this->UploadedFile->upload($this->data, $user_id, 1);
		
		$result = $this->UploadedFile->find('first', array('order' => 'UploadedFile.id desc'));
		$this->assertEqual($result['UploadedFile']['id'], 10);
		$this->assertEqual($result['UploadedFile']['filename'], 'Newfile.jpg');
		$this->assertEqual($result['UploadedFile']['current_version'], 1);

		$result = $this->UploadedFile->FileStorage->find('first', array('order' => 'id desc'));
		$this->assertEqual($result['FileStorage']['foreign_key'], 10);
		$this->assertEqual($result['FileStorage']['path'], '1/10-1-' . $filename);
		$this->assertEqual($result['FileStorage']['user_id'], $user_id);
	}

	public function testUploadFileNotExistButAnotherFileHasTheSameName() {
		$user_id = 1;
		$folder_id = 2;
		$this->UploadedFile->upload($this->data, $user_id, $folder_id);
		$result = $this->UploadedFile->find('first', array('order' => 'UploadedFile.id desc'));
		$this->assertEqual($result['UploadedFile']['id'], 10);
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
		$parent_id = 5;
		Configure::write('FileStorage.filePattern', '{user_id}/{file_id}-{version}-{filename}');
		$filename = Security::hash('Fraise.jpg');

		$this->UploadedFile->upload($this->data, $uploader_id, $parent_id);
		
		$result = file_exists(Configure::read('FileStorage.testFolder') . DS . $owner_id . DS . '6-2-' . $filename);
		$this->assertEqual($result, true);
	}

/////////////////////////////
/// Methods for userRight ///
/////////////////////////////

	public function testUserHasRightNoRulesFound() {
		$user_id = 5845;
		$file_id = 155144;
		$result = $this->UploadedFile->userHasRight($user_id, $file_id, 'notImportant');
		$this->assertEqual($result, false);	
	}
}
